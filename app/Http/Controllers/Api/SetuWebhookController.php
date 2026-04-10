<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SetuWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Setu Webhook:', $request->all());

        return response()->json([
            'status' => 'received'
        ]);
    }
}
