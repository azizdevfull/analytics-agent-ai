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
        $user = User::first(); // auth user
        $response = (new OrderAgent())
            ->forUser($user)
            ->prompt($request->input('message'));

        return response()->json([
            'conversation_id' => $response->conversationId,
            'response' => (string) $response,
        ]);
    }
    public function continue(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|string',
            'message' => 'required|string',
        ]);
        $user = User::first(); // auth user
        $response = (new OrderAgent())
            ->continue($request->input('conversation_id'), $user)
            ->prompt($request->input('message'));

        return response()->json([
            'conversation_id' => $response->conversationId,
            'response' => (string) $response,
        ]);

    }


    public function stream(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|string',
            'message' => 'required|string',
        ]);
        $user = User::first(); // auth user
        $response = (new OrderAgent())
            ->forUser($user)
            ->stream($request->input('message'));

        return $response;

    }
}
