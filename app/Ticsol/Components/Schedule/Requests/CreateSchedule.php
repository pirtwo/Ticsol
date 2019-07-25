<?php

namespace App\Ticsol\Components\Schedule\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSchedule extends FormRequest
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
            'event_type'                    => 'required|string|in:unavailable,scheduled,RDO',
            'user_id'                       => 'required|numeric|exists:ts_users,id',

            // Schedule item rules            
            'job_id'                        => 'required_if:event_type,scheduled|numeric|exists:ts_jobs,id',            
            'status'                        => 'required_if:event_type,scheduled|string|in:tentative,confirmed',            
            'start'                         => 'required_if:event_type,scheduled|date',
            'end'                           => 'required_if:event_type,scheduled|date|after:start',
            'billable'                      => 'nullable|boolean',
            'offsite'                       => 'nullable|boolean',

            // Unavailable hours rules
            'unavailables'                  => 'required_if:event_type,unavailable|array',
            'unavailables.*.start'          => 'required_with:unavailables|date',
            'unavailables.*.end'            => 'required_with:unavailables|date|after:start',  
        ];
    }

    public function messages()
    {
        return[
            //
        ];
    }
}
