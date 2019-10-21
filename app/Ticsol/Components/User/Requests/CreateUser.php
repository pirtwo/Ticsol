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
            'firstname'                 => 'required|string',            
            'lastname'                  => 'required|string',   
            'email'                     => 'required|email|unique:ts_users,email',
            'teams'                     => 'nullable|array',
            'teams.*'                   => 'numeric|exists:ts_teams,id',
            'roles'                     => 'nullable|array',
            'roles.*'                   => 'numeric|exists:ts_roles,id'                      
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}
