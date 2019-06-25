<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Ticsol\Base\Exceptions\NotFound;
use App\Ticsol\Components\Models\Timesheet;
use App\Ticsol\Components\Models\Schedule;
use App\Ticsol\Components\Models\Request as ReqModel;
use App\Ticsol\Components\Timesheet\Requests;
use App\Ticsol\Components\Timesheet\Repository;
use App\Ticsol\Components\Timesheet\Events as TimesheetEvents;
use App\Ticsol\Components\Request\Events as RequestEvents;
use App\Ticsol\Base\Criteria\ClientCriteria;
use App\Ticsol\Base\Criteria\CommonCriteria;
use App\Ticsol\Components\Timesheet\Criterias\TimesheetCriteria;

class TimesheetController extends Controller
{
    /**
     * Schedule items repository.
     * @var Repository\ScheduleRepository
     */
    protected $repository;

    public function __construct(Repository\TimesheetRepository $rep)
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
        $this->authorize('list', Timesheet::class);

        $page =
        $request->query('page') ?? null;
        $perPage =
        $request->query('perPage') ?? 15;
        $with =
        $request->query('with') != null ? explode(',', $request->query('with')) : [];

        $this->repository->pushCriteria(new CommonCriteria($request));
        $this->repository->pushCriteria(new TimesheetCriteria($request));
        $this->repository->pushCriteria(new ClientCriteria($request));

        if ($page == null) {
            $list = $this->repository->all($with);
        } else {
            $list = $this->repository->paginate($perPage, $with);
        }

        return $list;
    }

    /**
     * Store a newly created resource in storage.
     *
     * Type         : timesheet
     * Status       : submitted, approved, rejected, draft
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateTimesheet $request)
    {
        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('create', Timesheet::class);

        $timesheet = new Timesheet();
        $req = new ReqModel();

        $client_id =
        $request->user()->client_id;
        $creator_id =
        $request->user()->id;
        $status =
        $request->input('status');
        $items =
        $request->input('items');

        try {

            DB::beginTransaction();

            // populate new request model            
            $req->client_id = $client_id;
            $req->user_id = $creator_id;
            $req->type = 'timesheet';
            $req->fill($request->only(['status', 'assigned_id']));
            $req->save();

            // populate new timesheet model
            $timesheet->client_id = $client_id;
            $timesheet->creator_id = $creator_id;
            $timesheet->request_id = $req->id;
            $timesheet->fill($request->only([
                'year',
                'week_number',
                'week_start',
                'week_end',
                'total_hours',
            ]));
            $timesheet->save();

            // populate timesheet items
            foreach ($items as &$item) {
                $schedule = new Schedule();
                $schedule->client_id = $client_id;
                $schedule->creator_id = $creator_id;
                $schedule->fill($item);
                $schedule->timesheet()->associate($timesheet);
                $schedule->save();
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['code' => 1005, 'error' => true, 'message' => 'Internal Server Error.'], 500);
        }

        event(new TimesheetEvents\TimesheetCreated($timesheet));
        event(new RequestEvents\RequestCreated($req));

        return $timesheet;
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $with =
        $request->query('with') != null ? explode(',', $request->query('with')) : [];

        $timesheet = $this->repository->find($id, $with);
        if ($timesheet == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('view', $timesheet);

        return $timesheet;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UpdateTimesheet $request, $id)
    {
        $client_id =
        $request->user()->client_id;
        $creator_id =
        $request->user()->id;
        $timesheet =
        $this->repository->find($id);

        if ($timesheet == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('update', $timesheet);

        try {
            DB::beginTransaction();

            $timesheet->update($request->only(['total_hours']));

            if ($request->input('status') == 'draft') {
                $timesheet->request()
                    ->update(['status' => 'draft', 'assigned_id' => null]);
            } else {
                $timesheet->request()
                    ->update($request->only(['status', 'assigned_id']));
            }

            if ($request->has('items')) {
                $items = $request->input('items');
                $timesheet->schedules()->delete();

                // populate timesheet items
                foreach ($items as &$item) {
                    $schedule = new Schedule();
                    $schedule->client_id = $client_id;
                    $schedule->creator_id = $creator_id;
                    $schedule->fill($item);
                    $schedule->timesheet()->associate($timesheet);
                    $schedule->save();
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['code' => 1005, 'error' => true, 'message' => 'Internal Server Error.'], 500);
        }

        event(new TimesheetEvents\TimesheetUpdated($timesheet));
        event(new RequestEvents\RequestUpdated($timesheet->request));

        return $timesheet;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //
    }
}
