<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * method: GET
     * return change password view.
     */
    public function showChangePasswordForm()
    {
        return view("auth.passwords.change");
    }

    /**
     * method: POST
     * change current user password.
     */
    public function changePassword(Request $req)
    {
        $user = $req->user();

        $req->validate($this->rules($user->password), $this->validationErrorMessageS());

        $user->password = Hash::make($req->input("newPassword"));

        $user->save();

        return redirect("home");
    }

    /**
     * return request validation rules.
     */
    protected function rules($currentPasswordHash)
    {
        return [
            "password" => [
                "required",
                "string",
                function ($attribute, $value, $fail) use ($currentPasswordHash) {
                    if (!Hash::check($value, $currentPasswordHash)) {
                        $fail($attribute . ' is invalid.');
                    }
                },
            ],
            "newPassword" => "required|string|min:8",
            "confirmPassword" => "required|string|same:newPassword",
        ];
    }

    /**
     * return request validation errors.
     */
    protected function validationErrorMessageS()
    {
        return [];
    }
}
