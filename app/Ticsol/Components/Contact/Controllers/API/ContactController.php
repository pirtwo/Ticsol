<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Ticsol\Components\Models\Contact;
use App\Ticsol\Components\Contact\Requests;
use App\Ticsol\Components\Contact\Exceptions;
use App\Ticsol\Components\Contact\Repository;

class ContactController extends Controller
{
    /**
     * Contacts repository.
     * @var Repository\ContactRepository
     */
    protected $repository;

    public function __construct(Repository\ContactRepository $rep)
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
    public function store(Requests\ContactCreate $request)
    {
        try {
            $contact = new contact();
            // $contact->client_id =
            //     $req->user()->client_id;
            // $contact->creator_id =
            //     $req->user()->id;
            $contact->client_id = 1;
            $contact->creator_id = 1;
            $contact->fill($request->all());

            DB::beginTransaction();

            try {
                $contact->save();
                if ($request->filled('addresses')) {
                    $addresses = $request->input('addresses');
                    foreach ($addresses as &$address) {
                        $address['client_id'] = 1;
                        $address['creator_id'] = 1;
                    }
                    $contact->addresses()->createMany($addresses);
                }
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => 'An error ocured while proccessing your request.'], 500);
            }

            DB::commit();

            return $contact;
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
    public function show($id, Request $request)
    {
        try {
            $with = $request->query('with') != null ? explode(',', $request->query('with')) : [];
            $contact = $this->repository->find($id, $with);
            if ($contact == null) {
                throw new Exceptions\ContactNotFound();
            }
            return $contact;
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
    public function update(Requests\ContactUpdate $request, $id)
    {
        try
        {
            $contact = $this->repository->findBy('id', $id);
            if ($contact == null) {
                throw new Exceptions\FormNotFound();
            }

            DB::beginTransaction();

            try {
                $contact->update($request->all());
                if ($request->filled('addresses')) {
                    $addresses = $request->input('addresses');
                    foreach ($addresses as &$address) {
                        $address['client_id'] = 1;
                        $address['creator_id'] = 1;
                        unset($address['deleted_at']);
                        unset($address['created_at']);
                        unset($address['updated_at']);
                        $contact->addresses()->updateOrCreate(['id' => $address['id']], $address);
                    }
                }
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => 'An error ocured while proccessing your request.'], 500);
            }

            DB::commit();

            return $contact;
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
