<?php

namespace App\Ticsol\Components\Schedule\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTimesheet extends FormRequest
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
            'request_id'                 => 'required|numeric|exists:ts_requests,id',
            'status'                     => 'nullable|string|in:submitted,draft',
            'timesheets'                 => 'nullable|array',
            'timesheets.*.user_id'       => 'required_with:timesheets|numeric',
            'timesheets.*.job_id'        => 'required_with:timesheets|numeric',
            'timesheets.*.type'          => 'required_with:timesheets|string|in:timesheet',  
            'timesheets.*.start'         => 'required_with:timesheets|date',
            'timesheets.*.end'           => 'required_with:timesheets|date',            
            'timesheets.*.break_length'  => 'required_with:timesheets|numeric'
        ];
    }

    public function messages()
    {
        return[
            //
        ];
    }
}
