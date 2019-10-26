<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ticsol\Components\Models\Client;
use App\Ticsol\Components\Client\Repository;
use App\Ticsol\Components\Client\Requests;

class ClientController extends Controller
{
    protected $repository;

    public function __construct(Repository\ClientRepository $rep)
    {
        $this->repository = $rep;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $with =
        $request->query('with') != null ? explode(',', $request->query('with')) : [];

        $client = $this->repository->find($id, $with);

        if($client === null)
            return;

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('view', $client);

        return $client;
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UpdateClient $request, $id)
    {
        $client = Client::where("id", $id)->first();

        if($client === null)
            return;

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('update', $client);        

        $client = $this->setupQBsSettings($request, $client);
        $client = $this->steupBillingSettings($request, $client);
        $client = $this->steupBillingDefaults($request, $client);
        $client = $this->steupReimbursementSettings($request, $client);
        $client = $this->setupGeneralSettings($request, $client);

        $client->save();

        return $client;            
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function setupQBsSettings($request, $client)
    {
        $settings = $client->qbs !== null ? $client->qbs : [];

        if($request->has("qbs_classes")){
            $settings["classes"] = $request->input("qbs_classes");
        }

        if($request->has("qbs_departments")){
            $settings["departments"] = $request->input("qbs_departments");
        }

        $client->qbs = $settings;

        return $client;
    }

    private function steupBillingSettings($request, $client)
    {
        $settings = $client->billing_settings !== null ? $client->billing_settings : [];

        if($request->has("billing_hours_round")){
            $settings["hours_round"] = $request->input("billing_hours_round");
        }
        if($request->has("billing_hours_interval")){
            $settings["hours_interval"] = $request->input("billing_hours_interval");
        }
        if($request->has("billing_days_round")){
            $settings["days_round"] = $request->input("billing_days_round");
        }
        if($request->has("billing_days_interval")){
            $settings["days_interval"] = $request->input("billing_days_interval");
        }
        if($request->has("billing_hours_in_day")){
            $settings["hours_in_day"] = $request->input("billing_hours_in_day");
        }
        if($request->has("billing_allow_prepaid_jobs")){
            $settings["allow_prepaid_jobs"] = $request->input("billing_allow_prepaid_jobs");
        }
        if($request->has("billing_revenue_accounts")){
            $settings["revenue_accounts"] = $request->input("billing_revenue_accounts");
        }
        if($request->has("billing_income_in_adv_account_id")){
            $settings["income_in_adv_account_id"] = $request->input("billing_income_in_adv_account_id");
        }

        $client->billing_settings = $settings;

        return $client;
        
    }

    private function steupBillingDefaults($request, $client)
    {
        $defaults = $client->billing_defaults !== null ? $client->billing_defaults : [];

        if($request->has("billing_defaults_payment_type")){
            $defaults["payment_type"] = $request->input("billing_defaults_payment_type");
        }
        if($request->has("billing_defaults_allow_over_billing")){
            $defaults["allow_over_billing"] = $request->input("billing_defaults_allow_over_billing");
        }
        if($request->has("billing_defaults_job_fallback_rate")){
            $defaults["job_fallback_rate"] = $request->input("billing_defaults_job_fallback_rate");
        }
        if($request->has("billing_defaults_unit_type")){
            $defaults["unit_type"] = $request->input("billing_defaults_unit_type");
        }
        if($request->has("billing_defaults_revenue_account_id")){
            $defaults["revenue_account_id"] = $request->input("billing_defaults_revenue_account_id");
        }
        if($request->has("billing_defaults_company_rate")){
            $defaults["company_rate"] = $request->input("billing_defaults_company_rate");
        }

        $client->billing_defaults = $defaults;

        return $client;
    }

    private function steupReimbursementSettings($request, $client)
    {
        $settings = $client->reimbursement_settings !== null ? $client->reimbursement_settings : [];

        if($request->has("reimbursement_rate")){
            $settings["rate"] = $request->input("reimbursement_rate");
        }
        if($request->has("reimbursement_measure")){
            $settings["measure"] = $request->input("reimbursement_measure");
        }
        if($request->has("reimbursement_expence_account_id")){
            $settings["expence_account_id"] = $request->input("reimbursement_expence_account_id");
        }

        $client->reimbursement_settings = $settings;

        return $client;
    }

    /**
     * Setup the client general settings.
     */
    private function setupGeneralSettings($request, $client)
    {
        $meta = $client->meta !== null ? $client->meta : [];

        if($request->has("schedule_view")){
            $meta["schedule_view"] = $request->input("schedule_view");
        }

        if($request->has("schedule_range")){
            $meta["schedule_range"] = $request->input("schedule_range");
        }

        if($request->has("business_hours")){
            $meta["business_hours"] = $request->input("business_hours");
        }

        if($request->has("hour_per_day")){
            $meta["hour_per_day"] = $request->input("hour_per_day");
        }

        $client->meta = $meta;

        return $client;
    }
}
