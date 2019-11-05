<?php

namespace App\Ticsol\Components\Role\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRole extends FormRequest
{
    protected $roleId;
    protected $clientId = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->clientId = $this->user()->client_id;
        $this->roleId = $this->route('id');
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
            'users' => 'nullable|array',
            'permissions' => 'nullable|array',            

            'name' => [
                'required',
                Rule::unique('ts_roles')->where(function ($query) {
                    $query->where('client_id', $this->clientId)
                        ->where('id', '!=', $this->roleId);
                }),
            ],

            'users.*' => [
                'integer',
                Rule::exists('ts_users', 'id')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
            ],
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}
