<?php

namespace App\Http\Controllers;

use App\Ai\Agents\OrderAgent;
use App\Models\User;
use Illuminate\Http\Request;

class OrderAiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);
        $user = User::first();
        $response = (new OrderAgent())->forUser($user)
            ->prompt($request->input('message'));

        return response()->json([
            'conversation_id' => $response->conversationId,
            'message' => (string) $response,  // "response" emas "message"
        ]);

    }
    public function continue(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|string',
            'message' => 'required|string',
        ]);
        $user = User::first();
        $response = (new OrderAgent())
            ->continue(conversationId: $request->input('conversation_id'), as: $user)
            ->prompt($request->input('message'));

        return response()->json([
            'conversation_id' => $response->conversationId,
            'response' => (string) $response,
        ]);

    }
    // Streaming versiya (real-time)
    public function stream(Request $request)
    {
        $request->validate([
            'conversation_id' => 'nullable|string',
            'message' => 'required|string|max:1000',
        ]);
        $user = User::first();
        $agent = new OrderAgent;

        if ($conversationId = $request->input('conversation_id')) {
            $agent->continue($conversationId, as: $user);
        } else {
            $agent->forUser($user);
        }

        return $agent->stream($request->input('message'));
    }
}
