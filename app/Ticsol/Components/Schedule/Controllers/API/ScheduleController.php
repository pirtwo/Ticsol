<?php

namespace App\Ticsol\Components\Controllers\API;

use App\Http\Controllers\Controller;
use App\Ticsol\Base\Criteria\ClientCriteria;
use App\Ticsol\Base\Criteria\CommonCriteria;
use App\Ticsol\Base\Exceptions\NotFound;
use App\Ticsol\Components\Models\Schedule;
use App\Ticsol\Components\Schedule\Criterias\ScheduleCriteria;
use App\Ticsol\Components\Schedule\Events;
use App\Ticsol\Components\Schedule\Repository;
use App\Ticsol\Components\Schedule\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{

    /**
     * Schedule items repository.
     * @var Repository\ScheduleRepository
     */
    protected $repository;

    public function __construct(Repository\ScheduleRepository $rep)
    {
        $this->repository = $rep;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('list', Schedule::class);

        $page =
        $request->query('page') ?? null;
        $perPage =
        $request->query('perPage') ?? 15;
        $with =
        $request->query('with') != null ? explode(',', $request->query('with')) : [];

        $this->repository->pushCriteria(new CommonCriteria($request));
        $this->repository->pushCriteria(new ScheduleCriteria($request));
        $this->repository->pushCriteria(new ClientCriteria($request));

        if ($page == null) {
            return $this->repository->all($with);
        } else {
            return $this->repository->paginate($perPage, $with);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * Type         : schedule
     * Status       : tentative, confirmed
     * Event_type   : leave, unavailable, scheduled, RDO
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateSchedule $request)
    {
        $clientId = $request->user()->client_id;
        $creatorId = $request->user()->id;
        $eventType = $request->input('event_type');
        $userId = $request->input('user_id');

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('create', [Schedule::class, $userId, $eventType]);

        $schedule = new Schedule();
        $unavCollec = collect([]);

        if ($eventType == 'scheduled') {

            // check if date is available
            $start = $request->input("start");
            $end = $request->input("end");

            if ($this->isBooked($start, $end, $userId, $clientId)) {
                return response()->json(['error' => true, 'message' => "{$start} till {$end} is not available."], 400);
            }

            // setup schedule item
            $schedule->client_id = $clientId;
            $schedule->creator_id = $creatorId;
            $schedule->type = 'schedule';

            $schedule->fill($request->all());
            $schedule->save();

        } else {

            // setup unavalilable hours

            $unavailables =
            $request->input('unavailables');

            try {

                DB::beginTransaction();

                foreach ($unavailables as &$unavailable) {
                    $unav = new Schedule();
                    $unav->client_id = $clientId;
                    $unav->creator_id = $creatorId;
                    $unav->type = 'schedule';
                    $unavailable['event_type'] = 'unavailable';
                    $unavailable['user_id'] = $request->input('user_id');
                    $unavailable['status'] = 'confirmed';
                    $unav->fill($unavailable);
                    $unav->save();
                    $unavCollec->push($unav);
                }

                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['code' => 1005, 'error' => true, 'message' => 'Internal Server Error.'], 500);
            }
        }

        if ($eventType == 'scheduled') {
            event(new Events\ScheduleCreated($request->user(), $eventType, $schedule));
            return $schedule;
        } else {
            event(new Events\ScheduleCreated($request->user(), $eventType));
            return $unavCollec;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schedule = $this->repository->findBy('id', $id);
        if ($schedule == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('view', $schedule);

        return $schedule;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UpdateSchedule $request, $id)
    {
        $schedule = $this->repository->findBy('id', $id);
        if ($schedule == null) {
            throw new NotFound();
        }

        $clientId = $schedule->client_id;
        $userId = $schedule->user_id;
        $type = $schedule->type;
        $eventType = $schedule->event_type;

        Validator::make(['type' => $schedule->type, 'event_type' => $schedule->event_type], [
            'type' => 'in:schedule',
            'event_type' => 'in:unavailable,scheduled,RDO',
        ])->validate();

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('update', $schedule);

        // check if date is available
        if ($eventType === "scheduled" && ($request->has("start") || $request->has("end"))) {
            $start = $request->input("start", $schedule->start);
            $end = $request->input("end", $schedule->end);

            if ($this->isBooked($start, $end, $userId, $clientId)) {
                return response()->json(['error' => true, 'message' => "{$start} till {$end} is not available."], 400);
            }
        }

        $schedule->update($request->all());

        event(new Events\ScheduleUpdated($schedule));

        return $schedule;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $schedule = $this->repository->findBy('id', $id);
        if ($schedule == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('delete', $schedule);

        $clientId = $schedule->client_id;
        $result = $this->repository->delete('id', $id, false);

        event(new Events\ScheduleDeleted($id, $clientId));

        return $result;
    }


    /**
     * returns true if there is at least one leave or unavailable event
     * between start and end dates.
     */
    private function isBooked($start, $end, $userId, $clientId)
    {
        $unavailable = Schedule::where("client_id", $clientId)
            ->where("user_id", $userId)
            ->where(function ($query) {
                $query->where("event_type", "leave")
                    ->orWhere("event_type", "unavailable");
            })
            ->where(function ($query) use ($start, $end) {
                $query->where([
                    ['start', '<', $start],
                    ['end', '>', $end],
                ])
                    ->orWhere([
                        ['start', '>=', $start],
                        ['start', '<=', $end],
                        ['end', '<=', $end],
                        ['end', '>=', $start],
                    ])
                    ->orWhere([
                        ['start', '<', $start],
                        ['end', '>=', $start],
                        ['end', '<=', $end],
                    ])
                    ->orWhere([
                        ['start', '>=', $start],
                        ['start', '<=', $end],
                        ['end', '>', $end],
                    ]);
            })->first();

        return $unavailable != null;
    }
}
