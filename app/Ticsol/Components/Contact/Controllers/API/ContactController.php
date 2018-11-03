<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Ticsol\Base\Exceptions\NotFound;
use App\Ticsol\Components\Models\Contact;
use App\Ticsol\Components\Contact\Requests;
use App\Ticsol\Components\Contact\Repository;
use App\Ticsol\Base\Criteria\ClientCriteria;
use App\Ticsol\Base\Criteria\CommonCriteria;

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
    public function store(Requests\ContactCreate $request)
    {
        try {
            $creatorId = $request->user()->id;
            $clientId = $request->user()->client_id;

            $contact = new contact();
            $contact->client_id = $clientId;
            $contact->creator_id = $creatorId;
            $contact->fill($request->all());

            DB::beginTransaction();

            try {
                $contact->save();
                if ($request->filled('addresses')) {
                    $addresses = $request->input('addresses');
                    foreach ($addresses as &$address) {
                        $address['client_id'] = $clientId;
                        $address['creator_id'] = $creatorId;
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
                throw new NotFound();
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
            $creatorId = $request->user()->id;
            $clientId = $request->user()->client_id;
            $contact = $this->repository->findBy('id', $id);
            if ($contact == null) {
                throw new NotFound();
            }

            DB::beginTransaction();

            try {
                $contact->update($request->all());
                if ($request->filled('addresses')) {
                    $addresses = $request->input('addresses');
                    foreach ($addresses as &$address) {
                        $address['client_id'] = $clientId;
                        $address['creator_id'] = $creatorId;
                        unset($address['deleted_at']);
                        unset($address['created_at']);
                        unset($address['updated_at']);
                        $contact->addresses()->updateOrCreate($address, $address);
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
