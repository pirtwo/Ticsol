<?php

namespace App\Ticsol\Components\Comment\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComment extends FormRequest
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
            'body'   => 'required|string',   
        ];
    }

    public function messages()
    {
        return[
            //
        ];
    }
}
