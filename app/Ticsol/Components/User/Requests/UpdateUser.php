<?php

namespace App\Ticsol\Components\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
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
            'firstname'                 => 'string',            
            'lastname'                  => 'string',   
            'email'                     => 'email|unique:ts_users,email',
            'password'                  => 'min:8',
            'confirm_password'          => 'required_with:password|same:password',

            // settings 
            'ical'                      => 'boolean',            
            'theme'                     => 'string|in:default,urban,jungle,beach,night',
            'schedule_view'             => 'string|in:user,job',              
            'schedule_range'            => 'string|in:Week,Month',  
              
            
            // profile picture
            'avatar'                    => 'nullable|mimes:jpg,jpeg,png|max:5120'
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}
