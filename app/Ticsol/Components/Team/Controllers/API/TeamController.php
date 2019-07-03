<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Ticsol\Base\Exceptions\NotFound;
use App\Ticsol\Components\Models\Team;
use App\Ticsol\Components\Team\Requests;
use App\Ticsol\Components\Team\Repository;
use App\Ticsol\Base\Criteria\ClientCriteria;
use App\Ticsol\Base\Criteria\CommonCriteria;

class TeamController extends Controller
{
    /**
     * Team items repository.
     * @var Repository\TeamRepository
     */
    protected $repository;

    public function __construct(Repository\TeamRepository $rep)
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
        //$this->authorize('list', Team::class);

        $page =
        $request->query('page') ?? null;
        $perPage =
        $request->query('perPage') ?? 15;
        $with =
        $request->query('with') != null ? explode(',', $request->query('with')) : [];

        $this->repository->pushCriteria(new CommonCriteria($request));
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
    public function store(Requests\CreateTeam $request)
    {

        $clientId = $request->user()->client_id;
        $creatorId = $request->user()->id;

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        //$this->authorize('create');
        
        try {
            
            DB::beginTransaction();
            
            $team = new Team();
            $team->client_id = $clientId;
            $team->creator_id = $creatorId;
            $team->fill($request->only(['name']));
            $team->save();

            $team->users()->sync($request->input('users', [])); 
            
            DB::commit();
            
        } catch (\Ecxeption $e) {
            DB::rollback();
            return response()->json(['code' => 1005, 'error' => true, 'message' => 'Internal Server Error.'], 500);
        }

        return $team;   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $with =
        $request->query('with') != null ? explode(',', $request->query('with')) : [];

        $team = $this->repository->find($id, $with);
        if ($team == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        //$this->authorize('view', $team);

        return $team;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UpdateTeam $request, $id)
    {

        $team = $this->repository->find($id);
        if ($team == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        //$this->authorize('update');
        
        try {
            
            DB::beginTransaction();
            
            $team = $this->repository
                ->update($request->input('name'));

            $team->sync($request->input('users', [])); 
            
            DB::commit();
            
        } catch (\Ecxeption $e) {
            DB::rollback();
            return response()->json(['code' => 1005, 'error' => true, 'message' => 'Internal Server Error.'], 500);
        }

        return $team;   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $team = $this->repository->find($id);
        if ($team == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        //$this->authorize('delete');

        return $this->repository->delete('id', $id, false);
    }
}
