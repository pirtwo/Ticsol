<?php

namespace App\Ticsol\Components\Controllers\API;

use App\Http\Controllers\Controller;
use App\Ticsol\Base\Criteria\ClientCriteria;
use App\Ticsol\Base\Criteria\CommonCriteria;
use App\Ticsol\Base\Exceptions\NotFound;
use App\Ticsol\Components\Models\User;
use App\Ticsol\Components\User\Events;
use App\Ticsol\Components\User\Repository;
use App\Ticsol\Components\User\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    /**
     * Schedule items repository.
     * @var Repository\UserRepository
     */
    protected $repository;

    public function __construct(Repository\UserRepository $rep)
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
        $this->authorize('list', User::class);

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
    public function store(Requests\CreateUser $request)
    {
        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('create', User::class);
    }

    /**
     * show the current user info.
     */
    public function me(Request $request)
    {
        $with =
        $request->query('with') != null ? explode(',', $request->query('with')) : [];

        $user = $this->repository
            ->find($request->user()->id, $with);

        return $user;
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

        $user = $this->repository->find($id, $with);
        if ($user == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('view', $user);

        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UpdateUser $request, $id)
    {
        $user = $this->repository->findBy('id', $id);
        if ($user == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('update', $user);

        $user->fill($request->all());
        $meta = $user->meta;

        // save new avatar
        $defaultAvatar = "/img/avatar/default.png";
        $oldAvatar = $meta["avatar"];

        if ($request->file("avatar")) {
            $meta["avatar"] = "/storage/" . $request->file('avatar')->store('img/avatar', 'public');
        } else {
            $meta["avatar"] = $defaultAvatar;
        }

        // delete old avatar
        if ($oldAvatar != $defaultAvatar) {
            Storage::disk("public")->delete(\substr($oldAvatar, 9)); 
        }

        $user->meta = $meta;
        $user->save();

        event(new Events\UserUpdated($user));

        return $user;
    }

    /**
     * enable user account.
     */
    public function enable(Request $request, $id)
    {
        $user = $this->repository->findBy('id', $id);
        if ($user == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('activation', $user);

        $user->isactive = true;
        $user->save();

        return $user;
    }

    /**
     * disable user account.
     */
    public function disable(Request $request, $id)
    {
        $user = $this->repository->findBy('id', $id);
        if ($user == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('activation', $user);

        $user->isactive = false;
        $user->save();

        return $user;
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
