<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Agent;

use Illuminate\Support\Facades\Validator;


class AgentRegistrationController extends Controller
{
    /**
     * Registers a new agent
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:agents|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'profile_picture' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Create the agent
        $agent = Agent::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Agent created successfully',
            'agent' => $agent
        ], 201);
    }
}

