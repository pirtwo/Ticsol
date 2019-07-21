<?php

namespace App\Ticsol\Components\Request\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'job_id'            => 'nullable|integer',
            'form_id'           => 'nullable|integer',
            'assigned_id'       => 'nullable|integer',
            'schedule_id'       => 'nullable|integer',
            'status'            => 'required|string|in:submitted,suspended,draft,approved,rejected',

            // Leave     
            'leave_status'      => 'required_if:type,leave|string|in:tentative,confirmed',       
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
