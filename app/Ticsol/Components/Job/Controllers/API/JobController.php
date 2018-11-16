<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Controllers\Controller;
use App\Ticsol\Base\Exceptions\NotFound;
use App\Ticsol\Components\Models\Job;
use App\Ticsol\Components\Job\Requests;
use App\Ticsol\Components\Job\Repository;
use App\Ticsol\Base\Criteria\CommonCriteria;
use App\Ticsol\Base\Criteria\ClientCriteria;


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

            $this->authorize('list', Job::class);

            $page =
            $request->query('page', null);
            $perPage =
            $request->query('perpage', 20);
            $with =
            $request->query('with') != null ? explode(',', $request->query('with')) : [];
           
            $this->repository->pushCriteria(new CommonCriteria($request));
            $this->repository->pushCriteria(new ClientCriteria($request));

            if ($page == null) {
                return $this->repository->all($with);
            } else {
                return $this->repository->paginate($perPage, $with);
            }
        
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'This action is unauthorized.'], 401);   
        } catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
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
            $this->authorize('create', Job::class);
            try{

                DB::beginTransaction();  

                $job = new Job();
                $job->client_id = $request->user()->client_id;
                $job->creator_id = $request->user()->id;            
                $job->fill($request->all());
                $job->save();
                if ($request->filled('contacts')) {
                    $job->contacts()->sync($request->input('contacts'));
                }    

                DB::commit();

            }catch(\Exception $e){                
                DB::rollback();                
                return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
            }
            return $job;
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'This action is unauthorized.'], 401);   
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
            $job = Job::find($id);
            if ($job == null) {
                throw new NotFound();
            }

            $this->authorize('view', $job);

            return $job;

        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'This action is unauthorized.'], 401);   
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
    public function update(Requests\UpdateJob $request, $id)
    {
        try {
            
            $job = $this->repository->findBy('id', $id);
            if ($job == null) {
                throw new NotFound();
            }

            $this->authorize('update', $job);

            try{

                DB::beginTransaction();

                $job->update($request->all());
                if ($request->filled('contacts')) {
                    $job->contacts()->sync($request->input('contacts'));
                }   

                DB::commit();
                
            }catch(\Exception $e){                
                DB::rollback();                
                return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
            }   
            return $job;
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'This action is unauthorized.'], 401);        
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
    public function destroy($id)
    {
        //
    }
}
