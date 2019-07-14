<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Ticsol\Base\Exceptions\NotFound;
use App\Ticsol\Components\Models\Schedule;
use App\Ticsol\Components\Models\Request as RequestModel;
use App\Ticsol\Components\Request\Events as RequestEvents;
use App\Ticsol\Components\Schedule\Events as ScheduleEvents;
use App\Ticsol\Components\Timesheet\Events as TimesheetEvents;
use App\Ticsol\Components\Request\Requests;
use App\Ticsol\Components\Request\Repository;
use App\Ticsol\Base\Criteria\ClientCriteria;
use App\Ticsol\Base\Criteria\CommonCriteria;
use App\Ticsol\Components\Request\Criterias\RequestCriteria;

class RequestController extends Controller
{
    /**
     * Schedule items repository.
     * @var Repository\RequestRepository
     */
    protected $repository;

    public function __construct(Repository\RequestRepository $rep)
    {
        $this->repository = $rep;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('list', RequestModel::class);

        $page =
        $request->query('page') ?? null;
        $perPage =
        $request->query('perPage') ?? 20;
        $with =
        $request->query('with') != null ? explode(',', $request->query('with')) : [];

        $this->repository->pushCriteria(new CommonCriteria($request));
        $this->repository->pushCriteria(new RequestCriteria($request));
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
     * @param  \App\Ticsol\Components\Request\Requests  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateRequest $request)
    {
        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('create', RequestModel::class);

        $req = new RequestModel();
        $schedule = new Schedule();
        $clientId = $request->user()->client_id;
        $userId = $request->user()->id;

        try {
            DB::beginTransaction();

            $req->client_id = $clientId;
            $req->user_id = $userId;
            $req->fill($request->all()); 

            // check for attachments
            $attachments = $this->storeAttachments($request->attachments, "{$clientId}/{$userId}");            

            // set meta data
            if ($request->input("type") === "leave") {
                $req->meta = [
                    "leave_type" => $request->input("leave_type"),
                    "from" => $request->input("from"),
                    "till" => $request->input("till"),
                    "attachments" => $attachments
                ];
            } elseif ($request->input("type") === "reimbursement") {
                $req->meta = [
                    "details" => $request->input("details"),
                    "amount" => $request->input("amount"),
                    "tax" => $request->input("tax"),
                    "date" => $request->input("date"),
                    "attachments" => $attachments
                ];
            }

            // check the request type
            // if type == leave then create the related schedule item.
            if ($request->input('type') == 'leave') {
                $schedule->client_id = $clientId;
                $schedule->creator_id = $userId;
                $schedule->fill([
                    'user_id' => $request->user()->id,
                    'status' => 'tentative',
                    'type' => 'schedule',
                    'event_type' => 'leave',
                    'start' => $request->input('from'),
                    'end' => $request->input('till'),
                ]);
                $schedule->save();
                $req->schedule()->associate($schedule);
            }

            $req->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['code' => 1005, 'error' => true, 'message' => 'Internal Server Error.'], 500);
        }

        event(new RequestEvents\RequestCreated($req));

        if ($request->input('type') == 'leave') {
            event(new ScheduleEvents\ScheduleCreated($request->user(), 'leave'));
        }

        return $req;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $req = $this->repository->find($id);
        if ($req == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('view', $req);

        return $req;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Ticsol\Components\Request\Requests\UpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UpdateRequest $request, $id)
    {
        $clientId = $request->user()->client_id;
        $userId = $request->user()->id;

        $req = $this->repository->find($id);
        if ($req == null) {
            throw new NotFound();
        }
        
        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('update', $req);

        try {
            DB::beginTransaction();

            if ($req->type == 'leave') {
                $schedule = $req->schedule;
                $schedule->update([
                    'status' => $request->input('leave_status', 'tentative'),
                    'start' => $request->input('from'),
                    'end' => $request->input('till'),
                ]);
            }

            $req->update($request->all());

            // check for attachments
            $attachments = $this->storeAttachments($request->attachments, "{$clientId}/{$userId}"); 

            // set meta data
            if ($request->input("type") === "leave") {
                $req->meta = [
                    "leave_type" => $request->input("leave_type"),
                    "from" => $request->input("from"),
                    "till" => $request->input("till"),
                    "attachments" => $attachments
                ];
            } elseif ($request->input("type") === "reimbursement") {
                $req->meta = [
                    "details" => $request->input("details"),
                    "amount" => $request->input("amount"),
                    "tax" => $request->input("tax"),
                    "date" => $request->input("date"),
                    "attachments" => $attachments
                ];
            }

            $req->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['code' => 1005, 'error' => true, 'message' => 'Internal Server Error.'], 500);
        }        

        event(new RequestEvents\RequestUpdated($req));

        if ($req->type == 'timesheet') {
            event(new TimesheetEvents\TimesheetUpdated($req->timesheet));
        }

        return $req;
    }    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function approve(Request $request, $id)
    {
        $req = $this->repository->find($id);
        if ($req == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE Approve
        //----------------------------
        $this->authorize('approve', $req);

        $req->update(['status' => 'approved']);

        return $req;
    }

    public function reject(Request $request, $id)
    {
        $req = $this->repository->find($id);
        if ($req == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE Approve
        //----------------------------
        $this->authorize('approve', $req);

        $req->update(['status' => 'rejected']);

        return $req;
    }

    public function attachment(Request $request, $reqId, $fileId)
    {
        $req = $this->repository->find($reqId);
        if ($req == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE Approve
        //----------------------------
        //$this->authorize('attachment', $req);

        $attachment = collect($req->meta["attachments"]);
        
        $attachment = $attachment->first(function ($value, $key) use ($fileId) {
            return $value["id"] === $fileId;
        });

        return Storage::download($attachment["path"]);

    }

    protected function storeAttachments($files, $directory)
    {
        $fs = new \Illuminate\Filesystem\Filesystem();
        $attachments = [];

        for ($i = 0; $i < \sizeof($files); $i++) {            
            $attachment = new \stdClass;            
            $path = $files[$i]->store($directory);
            
            // populate and add to attachments
            $attachment->id = $fs->name($path);
            $attachment->extension = $fs->extension($path);
            $attachment->size = Storage::size($path);
            $attachment->path = $path;
            $attachments[] = $attachment;
        }

        return $attachments;
    }
}
