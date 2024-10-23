<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Agent;
use Google\Service\ArtifactRegistry\Hash;
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
            'password' => ['required', 'confirmed', 'min:8'],
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
     /**
     * Method that handles the login of an agent.
     *
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => ['required', 'exists:users'],
            'password' => ['required']
        ]);

        $agent = Agent::where('email', $request->email)->first();

        if (!$agent || !Hash::check($request->password, $agent->password)) {
            return response()->json([
                'message' => 'These are not valid credentials',
            ], 401);
        }

        $token = $agent->createToken('apitoken')->plainTextToken;

        return response()->json([
            'agent' => $agent,
            'token' => $token,
        ], 200);
    }

    /**
     * Method that handles the logout of an agent.
     *
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'You are logged out',
        ], 200);
    }
}

