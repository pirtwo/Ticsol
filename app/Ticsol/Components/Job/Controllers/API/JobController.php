<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Ticsol\Components\Models\Job;
use App\Ticsol\Components\Job\Requests;
use App\Ticsol\Components\Job\Exceptions;

class JobController extends Controller{    
    
    /**
     * Method: GET
     */
    public function list(Request $req)
    {
        try{
            $page = $req->query('page') ?? null;
            $perPage = $req->query('perPage') ?? 20;
            if($page == null)
                return job::all();
            return Job::paginate($perPage);
        }catch(Exception $e){            
            return response()->json(['message'=>'An error ocured while proccessing your request.'], 500);            
        }
    }

    /**
     * Method: GET
     */
    public function view(Request $req, $id)
    {
        try{
            return Job::find($id);
        }catch(Exception $e){
            return response()->json(['message'=>'An error ocured while proccessing your request.'], 500);   
        }
    }

    /**
     * Method: POST
     */
    public function create(Requests\CreateJob $req)
    {
        try{           
            $job = new Job();
            $job->client_id = 1;
            $job->creator_id = 1;
            $job->fill($req->all());
            $job->save();
            return $job;
        }catch(\Exception $e){
            return response()->json(['message'=>'An error ocured while proccessing your request.'], 500);   
        }
    }

    /**
     * Method: POST
     */
    public function update(Requests\UpdateJob $req, $id)
    {
        try{
            $job = Job::find($id);
            if($job == null)
                throw new Exceptions\JobNotFound();
            $job->update($req->all());
            return $job;
        }catch(Exception $e){
            return response()->json(['message'=>'An error ocured while proccessing your request.'], 500);   
        }
    }

}