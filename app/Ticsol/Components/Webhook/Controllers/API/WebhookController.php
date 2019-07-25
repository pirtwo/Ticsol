<?php

namespace App\Ticsol\Components\Controllers\API;

use App\Http\Controllers\Controller;
use App\Ticsol\Components\Models\Webhook;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    /**
     * subscribe to hook.
     */
    public function subscribe(Request $request)
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
    public function delete(Request $request)
    {
        $hookUrl = $request->input("hookUrl");
        $webhook = Webhook::where("url", $hookUrl)->first();
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
