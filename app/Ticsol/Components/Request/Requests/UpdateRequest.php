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
            'job_id'        => 'nullable|integer',
            'form_id'       => 'nullable|integer',
            'assigned_id'   => 'required|integer',
            'schedule_id'   => 'nullable|integer',
            'type'          => 'required|string|in:leave,reimbursement,timesheet',
            'status'        => 'required|string|in:approved,submitted,rejected'
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}
