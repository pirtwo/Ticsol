<?php

namespace App\Ticsol\Components\Timesheet\Requests;

use App\Ticsol\Components\Timesheet\Rules;
use Illuminate\Foundation\Http\FormRequest;

class CreateTimesheet extends FormRequest
{

    protected $locale;

    public function __construct()
    {
        $this->locale = 'en_AU';
    }

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
            'assigned_id' => 'nullable|numeric|exists:ts_users,id',
            'locale' => 'required|in:en_US,en_AU',
            'year' => 'required|date_format:Y',
            'week_number' => [
                'required',
                'numeric',
                new Rules\ValidWeekNumber($this->input('locale'), $this->input('year'))],
            'week_start' => [
                'required',
                'date_format:Y-m-d',
                new Rules\ValidWeekStart($this->input('locale'), $this->input('year'), $this->input('week_number'))],
            'week_end' => [
                'required',
                'date_format:Y-m-d',
                'after:week_start',
                new Rules\ValidWeekStart($this->input('locale'), $this->input('year'), $this->input('week_number')),
            ],
            'total_hours' => 'required|string',
            'status' => 'required|string|in:submitted,draft',

            // Timesheet items
            'items' => 'required|array',
            'items.*.user_id' => 'required_with:items|numeric',
            'items.*.job_id' => 'required_with:items|numeric',
            'items.*.status' => 'required_with:items|string|in:tentative,confirmed',
            'items.*.type' => 'required_with:items|string|in:timesheet',
            'items.*.event_type' => 'required_with:items|string|in:leave,scheduled,RDO',
            'items.*.start' => 'required_with:items|date',
            'items.*.end' => 'required_with:items|date',
            'items.*.break_length' => 'required_with:items|string',
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}
