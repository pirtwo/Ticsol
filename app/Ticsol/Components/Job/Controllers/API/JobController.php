<?php

namespace App\Ticsol\Components\Controllers\API;

use App\Http\Controllers\Controller;
use App\Ticsol\Base\Criteria\ClientCriteria;
use App\Ticsol\Base\Criteria\CommonCriteria;
use App\Ticsol\Base\Exceptions\NotFound;
use App\Ticsol\Components\Job\Criterias;
use App\Ticsol\Components\Job\Events;
use App\Ticsol\Components\Job\Repository;
use App\Ticsol\Components\Job\Requests;
use App\Ticsol\Components\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    /**
     * Schedule items repository.
     * @var Repository\JobRepository
     */
    protected $repository;

    public function __construct(Repository\JobRepository $rep)
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
        $this->authorize('list', Job::class);

        $page =
        $request->query('page', null);
        $perPage =
        $request->query('perPage', 20);
        $with =
        $request->query('with') != null ? explode(',', $request->query('with')) : [];

        $this->repository->pushCriteria(new CommonCriteria($request));
        $this->repository->pushCriteria(new Criterias\JobCriteria($request));
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
    public function store(Requests\CreateJob $request)
    {
        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('create', Job::class);

        try {

            DB::beginTransaction();

            $job = new Job();
            $job->client_id = $request->user()->client_id;
            $job->creator_id = $request->user()->id;
            $job->fill($request->all());

            // setup settings
            $job = $this->setupQBsSettings($request, $job);

            // setup billing
            $job = $this->setupBillingSettings($request, $job);

            $job->save();
            
            if ($request->filled('contacts')) {
                $job->contacts()->sync($request->input('contacts'));
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['code' => 1005, 'error' => true, 'message' => 'Internal Server Error.'], 500);
        }

        event(new Events\JobCreated($job));

        // dispatch webhooks
        $this->dispatchWebhooks($job, "job:created");

        return $job;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job = Job::find($id);
        if ($job == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('view', $job);

        return $job;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UpdateJob $request, $id)
    {
        $job = $this->repository->findBy('id', $id);
        if ($job == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('update', $job);

        try {

            DB::beginTransaction();

            $job->update($request->all());
            if ($request->filled('contacts')) {
                $job->contacts()->sync($request->input('contacts'));
            }

            // setup settings
            $job = $this->setupQBsSettings($request, $job);

            // setup billing
            $job = $this->setupBillingSettings($request, $job);

            $job->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['code' => 1005, 'error' => true, 'message' => 'Internal Server Error.'], 500);
        }

        event(new Events\JobUpdated($job));
        return $job;
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

    private function dispatchWebhooks($job, $event)
    {
        $data = $job->toArray();

        $hooks = $job->client
            ->webhooks()
            ->where("event", $event)
            ->get();

        foreach ($hooks as $hook) {
            $hook->fire($data);
        }
    }

    private function setupQBsSettings($request, $job)
    {
        $settings = $job->qbs ? $job->qbs : [];

        if ($request->has("qbs_id")) {
            $settings["id"] = $request->input("qbs_id");
        }

        $job->qbs = $settings;

        return $job;
    }

    /** setup job billing settings. */
    private function setupBillingSettings($request, $job)
    {
        $billing = $job->billing ? $job->billing : [];

        if ($request->has("payment_type")) {
            $billing["payment_type"] = $request->input("payment_type");
        }

        if ($request->has("rate")) {
            $billing["rate"] = $request->input("rate");
        }

        if ($request->has("unit")) {
            $billing["unit"] = $request->input("unit");
        }

        if ($request->has("unit_type")) {
            $billing["unit_type"] = $request->input("unit_type");
        }        

        if ($request->has("allow_over_billing")) {
            $billing["allow_over_billing"] = $request->input("allow_over_billing");
        }

        if ($request->has("job_fallback_rate")) {
            $billing["job_fallback_rate"] = $request->input("job_fallback_rate");
        }

        if ($request->has("revenue_account_id")) {
            $billing["revenue_account_id"] = $request->input("revenue_account_id");
        }

        $job->billing = $billing;

        return $job;
    }
}
