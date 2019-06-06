<?php

namespace App\Ticsol\Components\Request\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'job_id'            => 'required_if:type,reimbursement|integer|exists:ts_jobs,id',
            'form_id'           => 'nullable|integer|exists:ts_forms,id',
            'assigned_id'       => 'required|integer|exists:ts_users,id',
            'schedule_id'       => 'nullable|integer|exists:ts_schedules,id',
            'type'              => 'required|string|in:leave,reimbursement',            
            'status'            => 'required|string|in:submitted',            
            
            // Leave            
            'meta.leave_type'   => 'required_if:type,leave|string|in:annual,long service,sick,bereavement,maternity/paternity,study,other',
            'meta.from'         => 'required_if:type,leave|date',
            'meta.till'         => 'required_if:type,leave|date|after:meta.from',

            // Reimbursement
            'meta.details'      => 'required_if:type,reimbursement|string',
            'meta.amount'       => 'required_if:type,reimbursement|numeric',
            'meta.tax'          => 'required_if:type,reimbursement|string',
            'meta.date'         => 'required_if:type,reimbursement|date'            
        ];
    }

    public function messages()
    {
        return[
            //
        ];
    }
}
