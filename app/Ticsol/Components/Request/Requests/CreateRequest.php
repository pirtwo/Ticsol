<?php

namespace App\Ticsol\Components\Request\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
                'required_if:type,reimbursement', 
                'integer',
                Rule::exists('ts_jobs', 'id')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
            ],

            'form_id'           => [
                'nullable', 
                'integer',
                Rule::exists('ts_forms', 'id')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }), 
            ],

            'assigned_id'       => [
                'required', 
                'integer', 
                Rule::exists('ts_users', 'id')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
            ],

            'schedule_id'       => [
                'nullable', 
                'integer', 
                Rule::exists('ts_schedules', 'id')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
            ],
            
            'type'              => 'required|string|in:leave,reimbursement',            
            'status'            => 'required|string|in:submitted',            
            
            // Leave            
            'leave_type'        => 'required_if:type,leave|string|in:annual,long service,sick,bereavement,maternity/paternity,study,other',
            'from'              => 'required_if:type,leave|date',
            'till'              => 'required_if:type,leave|date|after:from',

            // Reimbursement
            'details'           => 'required_if:type,reimbursement|string|between:1,1000',
            'amount'            => 'required_if:type,reimbursement|numeric',
            'tax'               => 'required_if:type,reimbursement|string|in:Incl,Excl',
            'date'              => 'required_if:type,reimbursement|date',
            
            // Attachments
            'attachments'       => 'nullable|array|max:5',
            'attachments.*'     => 'mimes:jpg,jpeg,png,doc,docx,xls,xlsx,pdf|max:5120',
        ];
    }

    public function messages()
    {
        return[
            //
        ];
    }
}
