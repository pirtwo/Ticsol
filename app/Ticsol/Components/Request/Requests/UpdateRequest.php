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
            'status'            => 'required|string|in:submitted,approved,rejected',

            // Leave     
            'meta.status'       => 'nullable|string|in:tentative,confirmed',       
            'meta.leave_type'   => 'nullable|string|in:annual,long service,sick,bereavement,maternity/paternity,study,other',
            'meta.from'         => 'nullable|date',
            'meta.till'         => 'nullable|date|after:meta.from',

            // Reimbursement
            'meta.details'      => 'nullable|string',
            'meta.amount'       => 'nullable|numeric',
            'meta.tax'          => 'nullable|string',
            'meta.date'         => 'nullable|date'  
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}
