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
            // QBs settings
            'qbs_classes'                           => 'array', 
            'qbs_departments'                       => 'array',  
            
            // Billing Settings
            'billing_hours_round'                   => 'string|in:up,nearest',
            'billing_hours_interval'                => 'numeric|in:6,15,30,60',
            'billing_days_round'                    => 'string|in:up,nearest',
            'billing_days_interval'                 => 'numeric|in:0.25,0.5,1',
            'billing_hours_in_day'                  => 'numeric|between:1,24',
            'billing_allow_prepaid_jobs'            => 'nullable|boolean',
            'billing_revenue_accounts'              => 'nullable|array',
            'billing_income_in_adv_account_id'      => 'nullable|numeric',

            // Billing Defaults
            'billing_defaults_payment_type'         => 'string|in:prepaid,inArrears',
            'billing_defaults_allow_over_billing'   => 'boolean',
            'billing_defaults_job_fallback_rate'    => 'string|in:sameRate,companyDefault',
            'billing_defaults_unit_type'            => 'string|in:minutes,days',
            'billing_defaults_revenue_account_id'   => 'nullable|numeric',
            'billing_defaults_company_rate'         => 'numeric',

            // Reimbursement Settings
            'reimbursement_rate'                    => 'numeric',
            'reimbursement_measure'                 => 'string|in:kilometres,miles',
            'reimbursement_expence_account_id'      => 'nullable|numeric',

            // General settings   
            'schedule_view'             => 'string|in:user,job',
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
