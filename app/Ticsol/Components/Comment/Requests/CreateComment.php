<?php

namespace App\Ticsol\Components\Comment\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateComment extends FormRequest
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
            'body'              => 'required|string',   
            'entity'            => 'required|string|in:job,request,timesheet',         
            'job_id'            => 'required_if:entity,job|integer|exists:ts_jobs,id',
            'request_id'        => 'required_if:entity,request|integer|exists:ts_requests,id', 
            'timesheet_id'      => 'required_if:entity,timesheet|integer|exists:ts_timesheets,id', 
            'parent_id'         => 'nullable|integer|exists:ts_comments,id',          
        ];
    }

    public function messages()
    {
        return[
            //
        ];
    }
}
