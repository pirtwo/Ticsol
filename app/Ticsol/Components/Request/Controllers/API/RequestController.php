<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Ticsol\Base\Exceptions\NotFound;
use App\Ticsol\Components\Models\Schedule;
use App\Ticsol\Components\Models\Request as RequestModel;
use App\Ticsol\Components\Request\Events as RequestEvents;
use App\Ticsol\Components\Schedule\Events as ScheduleEvents;
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
        $client_id = $request->user()->client_id;
        $user_id = $request->user()->id;

        try {
            DB::beginTransaction();

            $req->client_id = $client_id;
            $req->user_id = $user_id;
            $req->fill($request->all());
            $req->save();

            if ($request->input('type') == 'leave') {                
                $schedule->client_id = $client_id;
                $schedule->creator_id = $user_id;
                $schedule->fill([
                    'user_id' => $request->user()->id,
                    'status' => 'tentative',
                    'type' => 'schedule',
                    'event_type' => 'leave',
                    'start' => $request->input('meta.from'),
                    'end' => $request->input('meta.till'),
                ]);
                $schedule->save();
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['code' => 1005, 'error' => true, 'message' => 'Internal Server Error.'], 500);
        }

        event(new RequestEvents\RequestCreated($req));

        if ($request->input('type') == 'leave')
            event(new ScheduleEvents\ScheduleCreated($request->user(), 'leave'));

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
        $req = $this->repository->find($id);
        if ($req == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('update', $req);

        if ($request->has('status')) {
            if ($request->input('status') == 'approved' || $request->input('status') == 'rejected') {

                //----------------------------
                //      AUTHORIZE ACTION
                //----------------------------
                $this->authorize('approve', $req);
            }
        }

        $req->update($request->all());
        event(new Events\RequestUpdated($req));
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
}
