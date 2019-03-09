<?php

namespace App\Ticsol\Components\Schedule\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUnavailable extends FormRequest
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
            'unavailables'                 => 'required|array',
            'unavailables.*.user_id'       => 'required|numeric',            
            'unavailables.*.type'          => 'required|string|in:schedule',  
            'unavailables.*.start'         => 'required|date',
            'unavailables.*.end'           => 'required|date',             
        ];
    }

    public function messages()
    {
        return[
            //
        ];
    }
}
