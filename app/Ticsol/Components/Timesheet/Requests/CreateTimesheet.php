<?php

namespace App\Ticsol\Components\Timesheet\Requests;

use App\Ticsol\Components\Timesheet\Rules;
use Illuminate\Foundation\Http\FormRequest;

class CreateTimesheet extends FormRequest
{
    protected $clientId = null;
    protected $user;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->clientId = $this->user()->client_id;
        $this->user = $this->user();
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
            'locale'            => 'required|in:en-US,en-AU',
            'year'              => 'required|date_format:Y|regex:/^\d{4}$/',
            'total_hours'       => 'required|string',
            'status'            => 'required|string|in:submitted,draft',

            'assigned_id'       => [
                'nullable', 
                'numeric',
                Rule::exists('ts_users')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }), 
            ],             
            'week_number'       => [
                'required',
                'numeric',
                new Rules\WeekRange(
                    $this->input('locale'),
                    $this->input('year')
                ),
                new Rules\WeekUnique(
                    $this->user,
                    $this->input('year')
                ),
            ],
            'week_start'        => [
                'required',
                'date_format:Y-m-d',
                new Rules\ValidWeekStart(
                    $this->input('locale'),
                    $this->input('year'),
                    $this->input('week_number')
                ),
            ],
            'week_end'          => [
                'required',
                'date_format:Y-m-d',
                new Rules\ValidWeekEnd(
                    $this->input('locale'),
                    $this->input('year'),
                    $this->input('week_start')
                ),
            ],
            
            // Timesheet items
            'items'                 => 'required|array',            
            'items.*.status'        => 'required_with:items|string|in:tentative,confirmed',
            'items.*.type'          => 'required_with:items|string|in:timesheet',
            'items.*.event_type'    => 'required_with:items|string|in:leave,scheduled,RDO',
            'items.*.start'         => 'required_with:items|date',
            'items.*.end'           => 'required_with:items|date',
            'items.*.billable'      => 'required_with:items|boolean',
            'items.*.break_length'  => 'required_with:items|string',

            'items.*.user_id'       => [
                'required_with:items', 
                'numeric',
                Rule::exists('ts_users')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
            ],
            
            'items.*.job_id'        => [
                'required_with:items', 
                'numeric',
                Rule::exists('ts_jobs')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
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
