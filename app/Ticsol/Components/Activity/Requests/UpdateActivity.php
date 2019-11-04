<?php

namespace App\Ticsol\Components\Activity\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateActivity extends FormRequest
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
            'from'          => 'required|date',
            'till'          => 'nullable|date',
            'desc'          => 'required|string|max:1000',

            'schedule_id' => [
                'required',
                'numeric',
                Rule::exists('ts_schedules')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
            ],

            'job_id' => [
                'required',
                'numeric',
                Rule::exists('ts_jobs')->where(function ($query) {
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
