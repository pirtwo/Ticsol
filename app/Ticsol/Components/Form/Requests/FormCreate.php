<?php

namespace App\Ticsol\Components\Form\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormCreate extends FormRequest
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
            "name"          => "required|string",
            "parent_id"     => "nullable|numeric|exists:ts_forms,id",
            "schema"        => "array",
            "billable"      => "boolean"
        ];
    }

    public function messages()
    {
        return[
            //
        ];
    }
}
