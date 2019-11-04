<?php

namespace App\Ticsol\Components\Comment\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateComment extends FormRequest
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
            'body' => 'required|string',
            'entity' => 'required|string|in:job,request,timesheet',

            'job_id' => [
                'required_if:entity,job',
                'integer',
                Rule::exists('ts_jobs')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
            ],

            'request_id' => [
                'required_if:entity,request',
                'integer',
                Rule::exists('ts_requests')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
            ],

            'timesheet_id' => [
                'required_if:entity,timesheet',
                'integer',
                Rule::exists('ts_timesheets')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
            ],
            
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('ts_comments')->where(function ($query) {
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
