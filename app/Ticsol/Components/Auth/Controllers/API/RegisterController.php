<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Ticsol\Base\Mail\DemoEmail;
use App\Ticsol\Components\Models\Role;
use App\Ticsol\Components\Models\User;
use App\Ticsol\Components\Models\Invitation;


class RegisterController extends Controller
{

    /**
     * Method: POST
     * @param Request
     */
    public function register(Request $request)
    {
        try {
            $token = Invitation::where('email', $request->json('email'))
                ->where('token', $request->json('token'))
                ->first();
            if ($token != null) {

                DB::transaction(function () {
                    $user = User::create([
                        'client_id' => $token->user->client_id,
                        'name' => $request->json('username'),
                        'email' => $request->json('email'),
                        'password' => bcrypt($request->json('password')),
                        'isowner' => false,                        
                    ]);
                    $role = Role::where('name', 'employee')->firstOrFail();
                    $user->roles->attach($role->role_id);
                });
                // return success message

            } else {
                throw new AuthException('Invalid invitation token.');
            }
        } catch (AuthException $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 400);
        }
    }

    public function send()
    {
        try {
            Mail::to('ahmad.f1111@gmail.com')->send(new DemoEmail());            
        } catch (Exception $e) {            
            return response()->json($e->getMessage(), 500);            
        }
    }
}
