<?php

namespace App\Ticsol\Components\Contact\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactCreate extends FormRequest
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
            'user_id'               => 'nullable|numeric|exists:ts_users,id',
            'group'                 => 'required|string|in:user,customer',
            'firstname'             => 'required|string',
            'lastname'              => 'required|string',
            'telephone'             => 'nullable|string',
            'mobilephone'           => 'required|string',
            'addresses'             => 'nullable|array',
            'addresses.*.unit'      => 'nullable|string',
            'addresses.*.number'    => 'required_with:addresses|string',
            'addresses.*.street'    => 'required_with:addresses|string',
            'addresses.*.suburb'    => 'required_with:addresses|string',
            'addresses.*.country'   => 'required_with:addresses|string',
            'addresses.*.postcode'  => 'required_with:addresses|string',
        ];
    }

    public function messages()
    {
        return[
            //
        ];
    }
}
