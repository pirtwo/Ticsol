<?php

namespace App\Ticsol\Components\Request\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'job_id'            => [
                'nullable', 
                'integer', 
                Rule::exists('ts_jobs')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
            ],

            'form_id'           => [
                'nullable', 
                'integer',
                Rule::exists('ts_forms')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }), 
            ],

            'assigned_id'       => [
                'nullable', 
                'integer', 
                Rule::exists('ts_users')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
            ],

            'schedule_id'       => [
                'nullable', 
                'integer', 
                Rule::exists('ts_schedules')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
            ],

            'status'            => 'required|string|in:submitted,suspended,draft,approved,rejected',
            'type'              => 'required|string|in:leave,reimbursement',     

            // Leave     
            'leave_status'      => 'string|in:tentative,confirmed',       
            'leave_type'        => 'string|in:annual,long service,sick,bereavement,maternity/paternity,study,other',
            'from'              => 'date',
            'till'              => 'date|after:from',

            // Reimbursement
            'details'           => 'string|between:1,1000',
            'amount'            => 'numeric',
            'tax'               => 'string|in:Incl,Excl',
            'date'              => 'date',

            // Attachments
            'attachments'       => 'nullable|array|max:5',
            'attachments.*'     => 'mimes:jpg,jpeg,png,doc,docx,xls,xlsx,pdf|max:5120'
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}
