<?php

namespace App\Ticsol\Components\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token'                     => 'required|string',
            'name'                      => 'required|string',   
            'firstname'                 => 'required|string',            
            'lastname'                  => 'required|string',   
            'email'                     => 'required|email',
            'password'                  => 'required|min:8',
            'confirm_password'          => 'required|same:password',            
            'meta'                      => 'nullable|array',               
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}
