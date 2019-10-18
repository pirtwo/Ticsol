<?php

namespace App\Ticsol\Components\Client\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClient extends FormRequest
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
            'qbs_classes'               => 'array', 
            'qbs_departments'           => 'array',    

            'hour_per_day'              => 'string',    
            'schedule_view'             => 'string|in:employee,job',
            'schedule_range'            => 'string|in:week,month',
            'business_hours'            => 'array',            
            'business_hours.*.day'      => 'required_with:business_hours|numeric|between:0,6', //Sunday as 0 and Saturday as 6
            'business_hours.*.start'    => 'required_with:business_hours|string',
            'business_hours.*.end'      => 'required_with:business_hours|string',
            'business_hours.*.isopen'   => 'required_with:business_hours|boolean'
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}
