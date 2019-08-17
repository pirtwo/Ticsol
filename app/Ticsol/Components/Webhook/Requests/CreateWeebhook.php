<?php

namespace App\Ticsol\Components\Webhook\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateWebhook extends FormRequest
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
            'url'                 => 'required|string|url',            
            'event'               => [
                'required',
                'string',
                'in:job:created,leave:created,leave:approved,leave:rejected,reimbursement:created,reimbursement:approved,reimbursement:rejected,timsheet:created,timesheet:approved,timesheet:rejected'
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
