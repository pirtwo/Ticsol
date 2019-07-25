<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Ticsol\Base\Exceptions\NotFound;
use App\Ticsol\Components\Models\Schedule;
use App\Ticsol\Components\Schedule\Events;
use App\Ticsol\Components\Schedule\Requests;
use App\Ticsol\Components\Schedule\Repository;
use App\Ticsol\Base\Criteria\ClientCriteria;
use App\Ticsol\Base\Criteria\CommonCriteria;
use App\Ticsol\Components\Schedule\Criterias\ScheduleCriteria;

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
        $client_id = $request->user()->client_id;
        $creator_id = $request->user()->id;
        $eventType = $request->input('event_type');

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('create', [Schedule::class, $request->input('user_id'), $eventType]);

        $schedule = new Schedule();
        $unavCollec = collect([]);

        if ($eventType == 'scheduled') {

            // Schedule item setup

            $schedule->client_id = $client_id;
            $schedule->creator_id = $creator_id;
            $schedule->type = 'schedule';

            $schedule->fill($request->all());
            $schedule->save();

        } else {

            // Unavailable hours setup

            $unavailables =
            $request->input('unavailables');

            try {

                DB::beginTransaction();

                foreach ($unavailables as &$unavailable) {
                    $unav = new Schedule();
                    $unav->client_id = $client_id;
                    $unav->creator_id = $creator_id;
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

        Validator::make(['type' => $schedule->type, 'event_type' => $schedule->event_type], [
            'type' => 'in:schedule',
            'event_type' => 'in:unavailable,scheduled,RDO',
        ])->validate();

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('update', $schedule);

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

        return $this->repository->delete('id', $id, false);
    }
}
