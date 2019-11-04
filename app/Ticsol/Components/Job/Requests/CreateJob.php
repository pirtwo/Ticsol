<?php

namespace App\Ticsol\Components\Job\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateJob extends FormRequest
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
            'qbs_id'        => 'integer',
            'title'         => 'required|string|between:1,100',
            'code'          => 'required|string|between:1,100',
            'isactive'      => 'required|boolean',
            'contacts'      => 'nullable|array',
            'meta'          => 'nullable',

            'parent_id'     => [
                'nullable', 
                'integer',
                Rule::exists('ts_jobs')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
            ],
            
            'form_id' => [
                'nullable',
                'integer',
                Rule::exists('ts_forms')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
            ],
            
            'contacts.*'    => [
                'integer',
                Rule::exists('ts_contacts')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                })
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
