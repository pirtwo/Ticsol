<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Ticsol\Base\Exceptions\NotFound;
use App\Ticsol\Components\Models\Contact;
use App\Ticsol\Components\Models\Address;
use App\Ticsol\Components\Contact\Events;
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
    public function store(Requests\ContactCreate $request)
    {
        try {

            $contact = new contact();
            $creatorId = 
                $request->user()->id;
            $clientId = 
                $request->user()->client_id;

            DB::beginTransaction();

            try {
                $contact->client_id = $clientId;
                $contact->creator_id = $creatorId;
                $contact->fill($request->all());
                $contact->save();

                if ($request->filled('addresses')) {
                    $items = $request->input('addresses');
                    foreach ($items as &$item) {
                        $address = new Address();                        
                        $address->client_id = $clientId;
                        $address->creator_id = $creatorId;
                        $address->fill($item);
                        $address->contact()->associate($contact);
                        $address->save();
                    }                    
                }

                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
            }
            
            event(new Events\ContactCreated($contact));
            return $contact;

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
            $contact = $this->repository->find($id, $with);
            if ($contact == null) {
                throw new NotFound();
            }
            return $contact;
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
    public function update(Requests\ContactUpdate $request, $id)
    {
        try
        {
            $contact = $this->repository->findBy('id', $id);
            $creatorId = $request->user()->id;
            $clientId = $request->user()->client_id;
            
            if ($contact == null) {
                throw new NotFound();
            }

            DB::beginTransaction();

            try {
                $contact->update($request->all());
                
                if ($request->has('addresses')) {
                    $items = $request->input('addresses');
                    $contact->addresses()->delete();

                    foreach ($items as &$item) {
                        $address = new Address();
                        $address->client_id = $clientId;
                        $address->creator_id = $creatorId;
                        $address->fill($item);
                        $address->contact()->associate($contact);
                        $address->save();                
                    }                    
                }

                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
            }
            
            event(new Events\ContactUpdated($contact));
            return $contact;

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
