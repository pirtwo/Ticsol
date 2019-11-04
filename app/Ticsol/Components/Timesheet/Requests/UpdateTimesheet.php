<?php

namespace App\Ticsol\Components\Timesheet\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTimesheet extends FormRequest
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
            'total_hours'               => 'required|string',   
            'status'                    => 'required|string|in:submitted,draft',
            'items'                     => 'nullable|array',
            'items.*.status'            => 'required_with:items|string|in:tentative,confirmed',  
            'items.*.type'              => 'required_with:items|string|in:timesheet',
            'items.*.event_type'        => 'required_with:items|string|in:leave,scheduled,RDO',    
            'items.*.start'             => 'required_with:items|date',
            'items.*.end'               => 'required_with:items|date', 
            'items.*.billable'          => 'required_with:items|boolean',           
            'items.*.break_length'      => 'required_with:items|string',

            'items.*.user_id'           => [
                'required_with:items', 
                'numeric',
                Rule::exists('ts_users')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
            ],
            
            'items.*.job_id'            => [
                'required_with:items', 
                'numeric',
                Rule::exists('ts_jobs')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
            ],            

            'assigned_id'               => [
                'nullable', 
                'numeric',
                Rule::exists('ts_users')->where(function ($query) {
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
