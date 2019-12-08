<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Ticsol\Components\Models\Comment;
use App\Ticsol\Components\Comment\Events;
use App\Ticsol\Components\Comment\Requests;
use App\Ticsol\Components\Comment\Repository;
use App\Ticsol\Components\Comment\Criterias\CommentCriteria;
use App\Ticsol\Base\Criteria\CommonCriteria;
use App\Ticsol\Base\Criteria\ClientCriteria;

class CommentController extends Controller
{
    /**
     * Comment items repository.
     * @var Repository\CommentRepository
     */
    protected $repository;

    public function __construct(Repository\CommentRepository $rep)
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
        $this->authorize('list', Comment::class);

        $id =
        $request->query('id');
        $entity =
        $request->query('entity');
        $page =
        $request->query('page', null);
        $perPage =
        $request->query('perPage', 20);
        $with = ['creator', 'childs.creator'];

        $this->repository->pushCriteria(new CommonCriteria($request));
        $this->repository->pushCriteria(new CommentCriteria($request, $entity, $id));
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
    public function store(Requests\CreateComment $request)
    {
        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('create', Comment::class);

        $comment = new Comment();
        $comment->client_id = $request->user()->client_id;
        $comment->creator_id = $request->user()->id;

        if ($request->input('entity') == 'job') {
            $comment->fill($request->only('parent_id', 'job_id'));
        } else if ($request->input('entity') == 'request') {
            $comment->fill($request->only('parent_id', 'request_id'));
        } else if ($request->input('entity') == 'timesheet') {
            $comment->fill($request->only('parent_id', 'timesheet_id'));
        }

        $comment->body = \strip_tags($request->input('body'));

        $comment->save();
        event(new Events\CommentCreated($comment));
        return $comment;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comment = $this->repository->find($id);
        if ($comment == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('view', $comment);

        return $comment;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UpdateComment $request, $id)
    {
        $comment = $this->repository->findBy('id', $id);
        if ($comment == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('update', $comment);

        $comment->update($request->only('body'));
        event(new Events\CommentCreated($comment));
        return $comment;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $comment = $this->repository->findBy('id', $id);
        
        if ($comment == null) {
            throw new NotFound();
        }
        
        $clientId = $comment->client_id;


        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('delete', $comment);

        $this->repository->delete('id', $id, false);

        event(new Events\CommentDeleted($id, $clientId));

        return response()->json(["success" => true]);
    }
}
