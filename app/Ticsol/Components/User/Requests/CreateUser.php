<?php

namespace App\Ticsol\Components\User\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateUser extends FormRequest
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
            'firstname'                 => 'required|string',            
            'lastname'                  => 'required|string',   
            'email'                     => 'required|email|unique:ts_users,email',
            'teams'                     => 'nullable|array',            
            'roles'                     => 'nullable|array',

            'teams.*'                   => [
                'numeric',
                Rule::exists('ts_teams', 'id')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
            ],
            
            'roles.*'                   => [
                'numeric',
                Rule::exists('ts_roles', 'id')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
            ],
            
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
