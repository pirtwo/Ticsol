<?php

namespace App\Ticsol\Components\Schedule\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTimesheet extends FormRequest
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
            'status'                     => 'required|string|in:submitted,draft',
            'timesheets'                 => 'required|array',
            'timesheets.*.user_id'       => 'required|numeric',
            'timesheets.*.job_id'        => 'required|numeric',
            'timesheets.*.type'          => 'required|string|in:timesheet',  
            'timesheets.*.start'         => 'required|date',
            'timesheets.*.end'           => 'required|date',            
            'timesheets.*.break_length'  => 'required|numeric'
        ];
    }

    public function messages()
    {
        return[
            //
        ];
    }
}
