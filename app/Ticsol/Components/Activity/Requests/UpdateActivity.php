<?php

namespace App\Ticsol\Components\Activity\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActivity extends FormRequest
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
            'schedule_id'   => 'required|numeric|exists:ts_schedules,id',
            'job_id'        => 'required|numeric|exists:ts_jobs,id',
            'from'          => 'required|date',
            'till'          => 'nullable|date',
            'desc'          => 'required|string|max:1000'
        ];
    }

    public function messages()
    {
        return[
            //
        ];
    }
}
