<?php

namespace App\Http\Controllers\Agent;

use App\Models\Agent;
use App\Http\Controllers\Controller;
use Google\Service\Dataflow\Parameter;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
class AgentController extends Controller
{



    /**
     * creates a new agent in the database
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    #[OA\Post(
        path: '/api/agents',
        description: 'creating an agent',
        tags: ['Store agents'],
        security : [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john.doe@example.com')
                ]
            )
        )
    )]
    #[OA\Parameter(
        name: 'Authorization',
        in: 'header',
        description: 'Bearer {token}',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Response(
        response: 201,
        description: 'Create an agent',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'agent is created successfully!'),

                new OA\Property(
                    property: 'agent',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                        new OA\Property(property: 'email', type: 'email', example: 'john.doe@example.com')
                    ]
                )
            ]
        )
    )]


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:agents',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'profile_picture' => 'nullable|string',
        ]);

        $agent = Agent::create($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Agent is created successfully',
            'agent' => $agent
        ], 201);
    }

}
