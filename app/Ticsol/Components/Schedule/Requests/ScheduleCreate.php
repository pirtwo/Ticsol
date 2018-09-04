<?php

namespace App\Ticsol\Components\Schedule\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleCreate extends FormRequest
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
            'user_id'       => 'required|numeric',
            'job_id'        => 'required|numeric',
            'type'          => 'required|string|in:schedule,timesheet',
            'status'        => 'required|string|in:tentative,confirmed,submitted',            
            'start'         => 'required|date',
            'end'           => 'required|date',
            'offsite'       => 'nullable|boolean',
            'break_length'  => 'required|numeric'
        ];
    }

    public function messages()
    {
        return[
            //
        ];
    }
}
