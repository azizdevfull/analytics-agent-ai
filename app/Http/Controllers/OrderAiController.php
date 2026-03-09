<?php

namespace App\Http\Controllers;

use App\Ai\Agents\OrderAgent;
use App\Models\User;
use Illuminate\Http\Request;

class OrderAiController extends Controller
{
    public function store(Request $reques)
    {
        $reques->validate([
            'message' => 'required|string',
        ]);
        $user = User::first();
        $response = (new OrderAgent())->forUser($user)
            ->prompt($reques->input('message'));

        return response()->json([
            'conversation_id' => $response->conversationId,
            'response' => (string) $response,
        ]);

    }
    public function continue(Request $request)
    {

    }
}
