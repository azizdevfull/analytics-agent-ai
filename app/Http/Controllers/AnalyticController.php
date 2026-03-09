<?php

namespace App\Http\Controllers;

use App\Ai\Agents\AnalyticsAgent;
use App\Ai\Agents\AnalyticsWithoutRememberAgent;
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
        // with remember conversation
        // $response = AnalyticsAgent::make()->forUser($user)->prompt($request->input('message'));

        // without remember conversation
        $response = AnalyticsWithoutRememberAgent::make()->prompt($request->input('message'));

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
    public function orderAnalyticStream(Request $request)
    {
        $request->validate([
            'conversation_id' => 'nullable|string',
            'message' => 'required|string|max:1000',
        ]);
        $user = User::first();
        $agent = new AnalyticsAgent;
        $conversationId = $request->input('conversation_id');

        if ($conversationId) {
            $agent->continue($request->input('conversation_id'), $user);
        } else {
            $agent->forUser($user);
        }
        return $agent->stream($request->input('message'));

    }
}
