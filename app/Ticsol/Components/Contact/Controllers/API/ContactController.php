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
        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('list', Contact::class);

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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\ContactCreate $request)
    {
        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('create', [Contact::class, $request->input('group'), $request->input('user_id')]);

        $contact = new contact();
        $creatorId =
        $request->user()->id;
        $clientId =
        $request->user()->client_id;

        try {
            DB::beginTransaction();

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
            return response()->json(['code' => 1005, 'error' => true, 'message' => 'Internal Server Error.'], 500);
        }

        event(new Events\ContactCreated($contact));
        return $contact;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $with = $request->query('with') != null ? explode(',', $request->query('with')) : [];
        $contact = $this->repository->find($id, $with);

        if ($contact == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('view', $contact);

        return $contact;
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
        $contact = $this->repository->findBy('id', $id);
        $creatorId = $request->user()->id;
        $clientId = $request->user()->client_id;

        if ($contact == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('update', $contact);

        try {
            DB::beginTransaction();

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
            return response()->json(['code' => 1005, 'error' => true, 'message' => 'Internal Server Error.'], 500);
        }

        event(new Events\ContactUpdated($contact));
        return $contact;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = $this->repository->findBy('id', $id);

        if ($contact == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('delete', $contact);

        return $this->repository->delete('id', $id, false);
    }
}
