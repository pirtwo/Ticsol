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
        try {   
            
            $list = null;

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

            //============================
            // ==== Check Permissions ====
            //============================
            //$this->authorize('list');

            return $list;

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
            $timesheet = new Timesheet();

            $client_id =
            $request->user()->client_id;
            $creator_id =
            $request->user()->id;
            $status =
            $request->input('status');
            $items =
            $request->input('items');

            DB::beginTransaction();
            try {
                // create new request model
                $req = new ReqModel();
                $req->client_id = $client_id;
                $req->user_id = $creator_id;
                $req->type = 'timesheet';
                $req->fill($request->only(['status', 'assigned_id']));
                $req->save();

                // create new timesheet model                
                $timesheet->client_id = $client_id;
                $timesheet->creator_id = $creator_id;
                $timesheet->request_id = $req->id;
                $timesheet->fill($request->only([
                    'week_start', 
                    'week_end', 
                    'total_hours'
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
                return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
            }    
            
            return $timesheet;

        } catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id = null)
    {
        try {
            
            $item = null;
            
            $with =
            $request->query('with') != null ? explode(',', $request->query('with')) : [];

            $this->repository->pushCriteria(new CommonCriteria($request));
            $this->repository->pushCriteria(new TimesheetCriteria($request));
            $this->repository->pushCriteria(new ClientCriteria($request));

            if($id != null)
                $item = $this->repository->find($id, $with);
            else 
                $item = $this->repository->first($with);

            //============================
            // ==== Check Permissions ====
            //============================
            //$this->authorize('view', $item);
            
            return $item;
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
            $client_id =
                $request->user()->client_id;
            $creator_id =
                $request->user()->id;
            $timesheet = 
                $this->repository->find($id);

            if($timesheet == null)
                throw new NotFound();

            //============================
            // ==== Check Permissions ====
            //============================
            //$this->authorize('update', $timesheet);

            try{                
                DB::beginTransaction();
                
                $timesheet->update($request->only(['total_hours']));
                $timesheet->request()
                    ->update($request->only(['status', 'assigned_id']));

                if($request->has('items')){
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
                
            }catch (\Exception $e) {
                DB::rollback();
                return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
            }  
            
            return $timesheet;

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
            //
        } catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
        }
    }
}
