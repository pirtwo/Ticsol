<?php

namespace App\Ticsol\Components\Schedule\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSchedule extends FormRequest
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
            'user_id'       => 'nullable|numeric|exists:ts_users,id',
            'job_id'        => 'nullable|numeric|exists:ts_jobs,id',            
            'status'        => 'nullable|string|in:tentative,confirmed',            
            'start'         => 'nullable|date',
            'end'           => 'nullable|date|after:start',
            'billable'      => 'nullable|boolean',
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
