<?php

namespace App\Ticsol\Components\Controllers\API;

use App\Http\Controllers\Controller;
use App\Ticsol\Components\Models\User;
use App\Ticsol\Components\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller{
    
    public function __construct()
    {
       
    }
    
    public function get(){        
        return Response()->json(User::all(), 201);       
    }

    public function client($id)
    {        
        $client = Client::where('id', $id)->firstOrFail();
        return response()->json($client->users, 201);        
    }
}