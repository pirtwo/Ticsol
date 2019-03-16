<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Access\AuthorizationException;
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
        try {

            //$this->authorize('list', Comment::class);

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
    public function store(Requests\CreateComment $request)
    {
        try {
            //$this->authorize('create', Comment::class);           

            $comment = new Comment();
            $comment->client_id = $request->user()->client_id;
            $comment->creator_id = $request->user()->id; 
            
            if($request->input('entity') == 'job'){
                $comment->fill($request->only('parent_id', 'job_id'));
            }
            else if($request->input('entity') == 'request'){
                $comment->fill($request->only('parent_id', 'request_id'));
            }
            $comment->body = \strip_tags($request->input('body'));

            $comment->save();
            event(new Events\CommentCreated($comment));    
            return $comment;
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
           //
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
    public function update(Requests\UpdateComment $request, $id)
    {
        try {            
            $comment = $this->repository->findBy('id', $id);
            if ($comment == null) {
                throw new NotFound();
            }

            $this->authorize('update', $comment);
            
            $comment->update($request->only('body'));
            event(new Events\CommentCreated($comment));  
            return $comment;
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
    public function delete($id)
    {
        try {
            
            $comment = $this->repository->findBy('id', $id);
            if ($comment == null) {
                throw new NotFound();
            }

            $this->authorize('delete', $comment);
            
            $this->repository->delete('id', $id, false);
            
            return true;
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'This action is unauthorized.'], 401);        
        } catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
        }
    }
}
