<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Ticsol\Base\Exceptions\NotFound;
use App\Ticsol\Components\Role\Events;
use App\Ticsol\Components\Role\Requests;
use App\Ticsol\Components\Role\Repository;
use App\Ticsol\Base\Criteria\ClientCriteria;
use App\Ticsol\Base\Criteria\CommonCriteria;

use App\Ticsol\Components\Models\Role;
use App\Ticsol\Components\Models\ACL;
use App\Ticsol\Components\Models\Resource;
use App\Ticsol\Components\Models\Permission;

class RoleController extends Controller
{

    /**
     * Schedule items repository.
     * @var Repository\RoleRepository
     */
    protected $repository;

    public function __construct(Repository\RoleRepository $rep)
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
        try {
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
    public function store(Requests\CreateRole $request)
    {
        try {
            $creatorId = $request->user()->id;
            $clientId = $request->user()->client_id;
            $permissions = $request->input('permissions');

            $role = new Role();
            $role->client_id = $clientId;
            $role->creator_id = $creatorId;
            $role->fill($request->all());

            DB::beginTransaction();
            try {
                $role->save();
                foreach ($permissions as $key => $value) {                    
                    $pos = strpos($value, '-');
                    $prmName = substr($value, 0, $pos);
                    $resName = substr($value, $pos + 1, \strlen($value));
                    $res = Resource::where('name', $resName)->first();
                    $prm = Permission::where('name', $prmName)->first();

                    $acl = ACL::firstOrNew([                                            
                        'role_id'       => $role->id,
                        'resource_id'   => $res->id,
                        'permission_id' => $prm->id
                    ]);

                    if ($acl->id == null) {
                        $acl->name = $value;
                        $acl->client_id = $clientId;
                        $acl->creator_id = $creatorId;
                        $acl->save();
                    }                    
                }
                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
            }
            event(new Events\RoleCreated($role));
            return $role;
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
    public function show($id, Request $request)
    {
        try {
            $with = $request->query('with') != null ? explode(',', $request->query('with')) : [];
            $role = $this->repository->find($id, $with);
            if ($role == null) {
                throw new NotFound();
            }
            return $role;
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
    public function update(Requests\UpdateRole $request, $id)
    {
        try {
            $creatorId = $request->user()->id;
            $clientId = $request->user()->client_id;
            $users = $request->input('users');
            $permissions = $request->input('permissions');

            $role = $this->repository->findBy('id', $id);
            if ($role == null) {
                throw new NotFound();
            }

            DB::beginTransaction();           
            
            try {                
                $role->update($request->all());

                if($permissions !== null){
                    $role->permissions()->delete();
                    foreach ($permissions as $key => $value) {                    
                        $pos = strpos($value, '-');
                        $prmName = substr($value, 0, $pos);
                        $resName = substr($value, $pos + 1, \strlen($value));
                        $res = Resource::where('name', $resName)->first();
                        $prm = Permission::where('name', $prmName)->first();

                        $acl = ACL::firstOrNew([                                            
                            'role_id'       => $role->id,
                            'resource_id'   => $res->id,
                            'permission_id' => $prm->id
                        ]);

                        if ($acl->id == null) {
                            $acl->name = $value;
                            $acl->client_id = $clientId;
                            $acl->creator_id = $creatorId;
                            $acl->save();
                        }                    
                    }
                }

                if($users != null){
                    $role->users()->sync($users);
                }
                
                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
            }
            event(new Events\RoleUpdated($role));
            return $role;
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
    public function delete($id)
    {
        try {
            return $this->repository->delete('id', $id);
        } catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
        }
    }
}
