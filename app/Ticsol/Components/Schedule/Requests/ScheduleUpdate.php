<?php

namespace App\Ticsol\Components\Schedule\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleUpdate extends FormRequest
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
            'user_id'       => 'nullable|numeric',
            'job_id'        => 'nullable|numeric',
            'type'          => 'nullable|string|in:schedule,timesheet',
            'status'        => 'nullable|string|in:tentative,confirmed,submitted',            
            'start'         => 'nullable|date',
            'end'           => 'nullable|date',
            'offsite'       => 'nullable|boolean',
            'break_length'  => 'nullable|numeric'
        ];
    }

    public function messages()
    {
        return[
            //
        ];
    }
}
