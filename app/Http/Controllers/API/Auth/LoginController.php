<?php

namespace App\Http\Controllers\api\auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $data = [
            'grant_type' => 'password',
            'client_id'  => env('Password_Client_ID'),
            'client_secret' => env('Password_Client_Secret'),
            'username' => $request->get('username'),
            'password' => $request->get('password')
        ];

        $new_req = Request::create('/oauth/token', 'POST', $data);
    }
}
