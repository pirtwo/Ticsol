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
            'firstname'                 => 'nullable|string',            
            'lastname'                  => 'nullable|string',   
            'email'                     => 'nullable|email',
            'password'                  => 'nullable|min:8',
            'confirm_password'          => 'required_with:password|same:password',
            'meta'                      => 'nullable|array',     
            
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
