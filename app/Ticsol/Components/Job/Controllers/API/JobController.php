<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Ticsol\Components\Models\Job;
use App\Ticsol\Components\Job\Requests;
use App\Ticsol\Components\Job\Exceptions;
use App\Ticsol\Components\Job\Repository;

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
        try {
            $page =
            $request->query('page') ?? null;
            $perPage =
            $request->query('perPage') ?? 20;
            $with =
            $request->query('with') != null ? explode(',', $request->query('with')) : [];

            if ($page == null) {
                return $this->repository->all($with);
            } else {
                return $this->repository->paginate($perPage, $with);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error ocured while proccessing your request.'], 500);
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
        try {
            $job = new Job();
            // $job->client_id =
            //     $req->user()->client_id;
            // $job->creator_id =
            //     $req->user()->id;
            $job->client_id = 1;
            $job->creator_id = 1;
            $job->fill($request->all());
            $job->save();
            return $job;
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error ocured while proccessing your request.'], 500);
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
            $job = Job::find($id);
            if ($job == null) {
                throw new Exceptions\JobNotFound();
            }
            return $job;
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error ocured while proccessing your request.'], 500);
        }
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
        try
        { 
            $job = $this->repository->findBy('id', $id);
            if ($job == null) {
                throw new Exceptions\JobNotFound();
            }

            $job->update($request->all());
            return $job;
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error ocured while proccessing your request.'], 500);
        }
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
