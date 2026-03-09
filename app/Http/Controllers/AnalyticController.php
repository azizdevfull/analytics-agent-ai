<?php

namespace App\Http\Controllers;

use App\Ai\Agents\AnalyticsAgent;
use App\Models\User;
use Illuminate\Http\Request;

class AnalyticController extends Controller
{
    public function orderAnalytic(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);
        $user = User::first();
        $response = AnalyticsAgent::make()->forUser($user)->prompt($request->input('message'));

        return response()->json([
            'conversation_id' => $response->conversationId,
            'message' => (string) $response,
        ]);
    }

    public function orderAnalyticContinue(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|string',
            'message' => 'required|string|max:1000',
        ]);
        $user = User::first();
        $agent = new AnalyticsAgent;
        $response = $agent
            ->continue($request->input('conversation_id'), $user)
            ->prompt($request->input('message'));

        return response()->json([
            'conversation_id' => $response->conversationId,
            'message' => (string) $response,
        ]);

    }
}
