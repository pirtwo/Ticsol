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
            'user_id'       => 'numeric|exists:ts_users,id',
            'job_id'        => 'numeric|exists:ts_jobs,id',
            'type'          => 'string|in:schedule',
            'event_type'    => 'string|in:leave,unavailable hours,scheduled,RDO',
            'status'        => 'string|in:tentative,confirmed',            
            'start'         => 'date',
            'end'           => 'date',
            'offsite'       => 'boolean',
            'break_length'  => 'numeric'
        ];
    }

    public function messages()
    {
        return[
            //
        ];
    }
}
