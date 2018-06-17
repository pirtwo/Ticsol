<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Ticsol\Components\Models\Job;
use App\Ticsol\Components\Job\Requests;
use App\Ticsol\Components\Job\Exceptions;
use App\Ticsol\Components\Job\Repository;

class JobController extends Controller{  
    
    /**
     * @var Repository\JobRepository
     */
    protected $repository;

    public function __construct(Repository\JobRepository $rep)
    {
        $this->repository = $rep;
    }
    
    /**
     * Method: GET
     */
    public function list(Request $req)
    {
        try{
            $page = 
                $req->query('page') ?? null;
            $perPage = 
                $req->query('perPage') ?? 20;
                
            if($page == null){
                return $this->repository->all();
            }else{
                return $this->repository->paginate($perPage);
            }           
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
            $job->client_id = 
                $req->user()->client_id;
            $job->creator_id = 
                $req->user()->id;
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