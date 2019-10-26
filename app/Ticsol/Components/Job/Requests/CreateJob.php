<?php

namespace App\Ticsol\Components\Job\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateJob extends FormRequest
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
            'qbs_id'    => 'integer',
            'parent_id' => 'nullable|integer',
            'form_id'   => 'nullable|integer',
            'title'     => 'required|string|between:1,100',
            'code'      => 'required|string|between:1,100',
            'isactive'  => 'required|boolean',
            'contacts'  => 'nullable|array',
            'meta'      => 'nullable'
        ];
    }

    public function messages()
    {
        return[
            "title.required" => "job title is required.",
            "code.required" => "job code is required",
        ];
    }
}
