<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Ticsol\Base\Exceptions\NotFound;
use App\Ticsol\Components\Models\Form;
use App\Ticsol\Components\Form\Requests;
use App\Ticsol\Components\Form\Repository;
use App\Ticsol\Base\Criteria\ClientCriteria;
use App\Ticsol\Base\Criteria\CommonCriteria;

class FormController extends Controller
{
    /**
     * Forms repository.
     * @var Repository\FormRepository
     */
    protected $repository;

    public function __construct(Repository\FormRepository $rep)
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

            $this->repository->pushCriteria(new ClientCriteria($request));
            $this->repository->pushCriteria(new CommonCriteria($request));   

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
    public function store(Request $request)
    {
        try {
            $form = new Form();
            $form->client_id = $request->user()->client_id;
            $form->creator_id = $request->user()->id;
            $form->fill($request->all());
            $form->save();
            return $form;
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
            $form = Form::find($id);
            if ($form == null) {
                throw new NotFound();
            }
            return $form;
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
    public function update(Request $request, $id)
    {
        try
        { 
            $form = $this->repository->findBy('id', $id);
            if ($form == null) {
                throw new NotFound();
            }

            $form->update($request->all());
            return $form;
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
