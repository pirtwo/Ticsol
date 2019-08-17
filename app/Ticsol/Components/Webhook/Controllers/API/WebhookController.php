<?php

namespace App\Ticsol\Components\Controllers\API;

use App\Http\Controllers\Controller;
use App\Ticsol\Components\Models\Webhook;
use App\Ticsol\Components\Webhook\Requests;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    /**
     * returns a list of hooks.
     */
    public function index(Request $request)
    {  
        return $request->user()->webhooks;
    }

    /**
     * subscribe to hook.
     */
    public function subscribe(Requests\CreateWebhook $request)
    {
        $webhook = new Webhook();
        $webhook->client_id = $request->user()->client_id;
        $webhook->user_id = $request->user()->id;

        $webhook->fill($request->all());
        $webhook->save();

        return $webhook;
    }

    /**
     * delete the hook.
     */
    public function delete($id)
    {
        $webhook = Webhook::find($id);
        $webhook->delete();

        return response()->json([
            "success" => "success",
        ]);
    }

    /**
     * serve sample data.
     */
    public function pollForTrigger()
    {
        return response()->json([
            "name" => "Sample Name",
        ]);
    }
}
