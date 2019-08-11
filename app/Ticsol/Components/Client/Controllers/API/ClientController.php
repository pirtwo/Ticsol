<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ticsol\Components\Models\Client;
use App\Ticsol\Components\Client\Repository;
use App\Ticsol\Components\Client\Requests;

class ClientController extends Controller
{
    protected $repository;

    public function __construct(Repository\ClientRepository $rep)
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

        $client = $this->repository->find($id, $with);

        if($client === null)
            return;

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        //$this->authorize('view', $client);

        return $client;
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UpdateClient $request, $id)
    {
        $client = Client::where("id", $id)->first();

        if($client === null)
            return;

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        //$this->authorize('update', $client);        

        $client = $this->setupSettings($request, $client);

        $client->save();

        return $client;       
        
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

    /**
     * Setup the client settings.
     */
    private function setupSettings($request, $client)
    {
        $meta = $client->meta !== null ? $client->meta : [];

        if($request->has("schedule_view")){
            $meta["schedule_view"] = $request->input("schedule_view");
        }

        if($request->has("schedule_range")){
            $meta["schedule_range"] = $request->input("schedule_range");
        }

        if($request->has("business_hours")){
            $meta["business_hours"] = $request->input("business_hours");
        }

        if($request->has("hour_per_day")){
            $meta["hour_per_day"] = $request->input("hour_per_day");
        }

        $client->meta = $meta;

        return $client;
    }
}
