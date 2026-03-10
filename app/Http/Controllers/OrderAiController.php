<?php

namespace App\Http\Controllers;

use App\Ai\Agents\OrderAgent;
use Illuminate\Http\Request;

class OrderAiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $response = (new OrderAgent())
            ->prompt($request->input('message'));

        return response()->json([
            'response' => (string) $response,
        ]);
    }
}
