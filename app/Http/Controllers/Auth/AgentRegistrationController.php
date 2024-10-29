<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Agent;
use App\Models\User;
use Google\Service\ArtifactRegistry\Hash;
use Google\Service\Docs\Response;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;


class AgentRegistrationController extends Controller
{
    /**
     * Registers a new agent
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    #[OA\Post(
        path: '/api/agents/register',
        description: 'Registers a new agent',

        tags: ['Agent']
    )]
    #[OA\Response(
        response: 200,
        description: 'Agent is registered successfully',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'Agent created successfully'),
                new OA\Property(
                    property: 'user',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                        new OA\Property(property: 'email', type: 'string', example: 'john.doe@example.com')
                    ]
                )
            ]
        )
    )]
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
        $user = User::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Agent is created successfully',
            'user' => $user
        ], 201);
    }
     /**
     * Method that handles the login of an agent.
     *
     * @param Request $request
     * @return Response
     */
    #[OA\Post(
        path: '/api/agents/login',
        description: 'logins an existing agent',
        tags: ['login']
    )]
    #[OA\Response(
        response: 200,
        description: 'Agent is logged in successfully',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'token', type: 'string', example: 'adcxzvbhfredfgh'),

                new OA\Property(
                    property: 'user',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                        new OA\Property(property: 'email', type: 'string', example: 'john.doe@example.com')
                    ]
                )
            ]
        )
    )]
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => ['required', 'exists:users'],
            'password' => ['required']
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'These are not valid credentials',
            ], 401);
        }

        $token = $user->createToken('apitoken')->plainTextToken;

        return response()->json([
            'agent' => $user,
            'token' => $token,
        ], 200);
    }

    /**
     * Method that handles the logout of an agent.
     *
     * @param Request $request
     * @return Response
     */
    #[OA\Post(
        path: '/api/agents/logout',
        description: 'logout an existing agent',
        tags: ['logout']
    )]
    #[OA\Response(
        response: 200,
        description: 'Agent is logged out successfully',
        content: new OA\JsonContent(
            type: 'object',
            properties: [

                new OA\Property(property: 'message', type: 'string', example: 'You are logged out successfully'),

            ]
        )
    )]
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'You are logged out',
        ], 200);
    }
}

