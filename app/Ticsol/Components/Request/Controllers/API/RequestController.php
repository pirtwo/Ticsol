<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Ticsol\Base\Exceptions\NotFound;
use App\Ticsol\Components\Models\Request as RequestModel;
use App\Ticsol\Components\Request\Events;
use App\Ticsol\Components\Request\Requests;
use App\Ticsol\Components\Request\Repository;
use App\Ticsol\Components\Request\Notifications;
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
        try {
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
            
        } catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
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
        try {
            $req = new RequestModel();            
            $req->client_id = $request->user()->client_id;
            $req->user_id = $request->user()->id;
            $req->status = 'submitted';
            $req->fill($request->all());
            $req->save();
            event(new Events\RequestCreated($req));
            return $req;
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
            $req = $this->repository->find($id);
            if ($req == null) {
                throw new NotFound();
            }
            return $req;
        } catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
        }
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
        try {
            $req = $this->repository->find($id);
            if ($req == null) {
                throw new NotFound();
            }

            $req->update($request->all());
            event(new Events\RequestUpdated($req));
            return $req;
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
