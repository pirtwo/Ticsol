<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Ticsol\Base\Exceptions\NotFound;
use App\Ticsol\Components\Models\Schedule;
use App\Ticsol\Components\Models\Request as ReqModel;
use App\Ticsol\Components\Schedule\Requests;
use App\Ticsol\Components\Schedule\Repository;
use App\Ticsol\Base\Criteria\ClientCriteria;
use App\Ticsol\Base\Criteria\CommonCriteria;
use App\Ticsol\Components\Schedule\Criterias\TimesheetCriteria;

class TimesheetController extends Controller
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
        try {
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
                return $this->repository->all($with);
            } else {
                return $this->repository->paginate($perPage, $with);
            }
        } catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
        }
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
        try {            
            $client_id =
            $request->user()->client_id;
            $creator_id =
            $request->user()->id;
            $status =
            $request->input('status');
            $timesheets =
            $request->input('timesheets');

            DB::beginTransaction();
            try {
                $req = new ReqModel();
                $req->client_id = $client_id;
                $req->user_id = $creator_id;
                $req->fill([                    
                    'type' => 'timesheet',
                    'status' => 'draft'
                ]);                  
                $req->save();

                foreach ($timesheets as &$timesheet) {
                    $ts = new Schedule();
                    $ts->client_id = $client_id;
                    $ts->creator_id = $creator_id;                    
                    $timesheet['request_id'] = $req->id;                    
                    $timesheet['event_type'] = 'scheduled';
                    $timesheet['status'] = \strtolower($status);
                    $ts->fill($timesheet);
                    $ts->save();
                } 

                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
            }
            
            return $req->timesheets()->get();
        } catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
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
        try {
            $req = ReqModel::find($id);
            if ($req == null) {
                throw new NotFound();
            }
            return $req->timesheets()->get();
        } catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
        }
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
        try {
            $schedule = $this->repository->findBy('id', $id);
            if ($schedule == null) {
                throw new NotFound();
            }

            $schedule->update($request->all());
            return $schedule;
        } catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            return $this->repository->delete('id', $id);
        } catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
        }
    }
}
