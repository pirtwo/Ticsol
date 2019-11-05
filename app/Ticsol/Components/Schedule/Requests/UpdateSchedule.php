<?php

namespace App\Ticsol\Components\Schedule\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSchedule extends FormRequest
{
    protected $clientId = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->clientId = $this->user()->client_id;
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
            'status'        => 'nullable|string|in:tentative,confirmed',            
            'start'         => 'nullable|date',
            'end'           => 'nullable|date|after:start',
            'billable'      => 'nullable|boolean',
            'offsite'       => 'nullable|boolean',
            'break_length'  => 'nullable|numeric',

            'user_id'       => [
                'nullable', 
                'numeric', 
                Rule::exists('ts_users', 'id')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
            ],

            'job_id'        => [
                'nullable', 
                'numeric', 
                Rule::exists('ts_jobs', 'id')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
            ],                 
        ];
    }

    public function messages()
    {
        return[
            //
        ];
    }
}
