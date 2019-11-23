<?php

namespace App\Ticsol\Components\Form\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Ticsol\Base\Rules;
use App\Ticsol\Components\Models\Form;

class FormUpdate extends FormRequest
{
    protected $form;
    protected $parent;
    protected $clientId = null;
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $formId = $this->route()->parameter('id');
        $parentId = $this->input('parent_id', null);

        $this->clientId = $this->user()->client_id;        
        $this->form = Form::where('id', $formId)->first();
        $this->parent = Form::where('id', $parentId)->first();
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
            "name"          => "string",            
            "schema"        => "array",
            "billable"      => "boolean",
            
            "parent_id"     => [
                "nullable",
                "numeric",
                Rule::exists('ts_forms', 'id')->where(function ($query) {
                    $query->where('client_id', $this->clientId);
                }),
                new Rules\HierarchyDepth($this->parent, 3),
                new Rules\HierarchyCycle($this->form),
                new Rules\HierarchyFatherMove($this->form)
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
