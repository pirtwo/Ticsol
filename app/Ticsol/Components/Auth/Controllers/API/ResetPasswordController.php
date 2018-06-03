<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Cookie\CookieJar;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

class ResetPasswordController extends Controller{

    /**
     * Method: POST.
     * @param Request
     */
    public function resetToken(Request $request)
    {
        try {
            $user = User::where('name', $request->json('username'))
                ->where('email', $request->json('email'))
                ->first();
            if ($user != null) {
                // create or update token for user
                // send email to user email address
            } else {
                throw new AuthException('Invalid username or email address.');
            }
        } catch (AuthException $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Method: POST
     * @param Request
     */
    public function resetPassword(Request $request)
    {
        try {
            $token = PasswordReset::where('token', $request->json('token'))
                ->first();
            if ($token != null) {
                $token->user()->update(['password', password_hash($request->json('password'))]);
                $token->forceDelete();
                //return success message
            } else {
                throw new AuthException('Invalid password reset token.');
            }
        } catch (Exception $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 400);
        }
    }    
}