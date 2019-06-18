<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Ticsol\Base\Exceptions\NotFound;
use App\Ticsol\Components\Models\Activity;
use App\Ticsol\Components\Activity\Events;
use App\Ticsol\Components\Activity\Requests;
use App\Ticsol\Components\Activity\Repository;
use App\Ticsol\Components\Activity\Criterias\ActivityCriteria;
use App\Ticsol\Base\Criteria\ClientCriteria;
use App\Ticsol\Base\Criteria\CommonCriteria;

class ActivityController extends Controller
{

    /**
     * @var Repository\ActivityRepository
     */
    protected $repository;

    public function __construct(Repository\ActivityRepository $rep)
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
        $this->authorize('list', Activity::class);

        $page =
        $request->query('page') ?? null;
        $perPage =
        $request->query('perPage') ?? 20;
        $with =
        $request->query('with') != null ? explode(',', $request->query('with')) : [];

        $this->repository->pushCriteria(new CommonCriteria($request));
        $this->repository->pushCriteria(new ActivityCriteria($request));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateActivity $request)
    {
        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('create', Activity::class);

        $activity = new Activity();
        $activity->client_id = $request->user()->client_id;
        $activity->creator_id = $request->user()->id;
        $activity->fill($request->all());
        $activity->save();
        event(new Events\ActivityCreated($activity));
        return $activity;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $activity = $this->repository->find($id);
        if ($activity == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('view', $activity);

        return $activity;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UpdateActivity $request, $id)
    {
        $activity = $this->repository->find($id);

        if ($activity == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('update', $activity);

        $activity->update($request->all());

        event(new Events\ActivityUpdated($activity));

        return $activity;
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
