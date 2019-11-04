<?php

namespace App\Ticsol\Components\User\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
{
    protected $clientId = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->clientId = $this->user()->client_id;
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
            'avatar'                    => 'nullable|mimes:jpg,jpeg,png|max:5120',

            // QBs
            'qbs_id'                    => 'nullable|numeric',
            'qbs_vendor_id'             => 'nullable|numeric',
            'qbs_budgeted_cost_rate'    => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}
