<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Google\Service\Dataflow\Parameter;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class AgentController extends Controller



{


    /**
 * specifices all the agents in the database
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
 #[OA\Get(
        path: '/api/agents',
        description: 'getting all agents',
        tags: ['All agents'],
        security : [["bearerAuth" => []]],

    )]
    #[OA\Parameter(
        name: 'Authorization',
        in: 'header',
        description: 'Bearer {token}',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Response(
        response: 200,
        description: 'Fetching all agents',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'agents are fetched successfully!'),

                new OA\Property(
                    property: 'agents',
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
    public function index()
    {
        $agents = Agent::all();
        return response()->json([

            'status' => 'success',
            'message' => 'Agents are retrieved successfully',
            'agents' => $agents
        ]);
    }
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
                        new OA\Property(property: 'email', type: 'string', example: 'john.doe@example.com')
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

    /**
     * retrieves a specific agent from the database
     * @param Agent $agent
     * @return \Illuminate\Http\JsonResponse
     */
    #[OA\Get(
        path: '/api/agents/{agent}',
        description: 'Show the details of a single user',
        tags: ['Details of an agent'],
        security : [["bearerAuth" => []]],
        parameters: [new OA\Parameter(
            name: "agent",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )],
    )]
    #[OA\Parameter(
        name: 'Authorization',
        in: 'header',
        description: 'Bearer {token}',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Response(
        response: 200,
        description: 'Show the details of a single user',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'agent details are shown successfully!'),

                new OA\Property(
                    property: 'agent',
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

    public function show(Agent $agent)
    {

        return response()->json([
            'status' => 'success',
            'message' => 'Agent retrieved successfully',
            'agent' => $agent
        ], 200);
    }
    /**
     * updates a specific agent in the database
     * @param Request $request
     * @param Agent $agent
     * @return \Illuminate\Http\JsonResponse
     */
    #[OA\Patch(
        path: '/api/agents/{agent}',
        description: 'update the details of a single user',
        tags: ['Updating agent'],
        security : [["bearerAuth" => []]],
        parameters: [new OA\Parameter(
            name: "agent",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john.doe@example.com'),
                    new OA\Property(property: 'phone', type: 'string', format: 'email', example: 'john.doe@example.com'),
                    new OA\Property(property: 'bio', type: 'string', format: 'email', example: 'john.doe@example.com'),
                    new OA\Property(property: 'profile_pic', type: 'string', format: 'email', example: 'john.doe@example.com'),
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
        response: 200,
        description: 'Update the  details of a single user',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'agent details are updated successfully!'),

                new OA\Property(
                    property: 'agent',
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

    public function update(Request $request, Agent $agent)
    {


        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:agents,email,' . $agent->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'profile_picture' => 'nullable|string',
        ]);

        $agent->update($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Agent is updated successfully',
            'agent' => $agent
        ], 200);
    }
    /**
     * deletes a specific agent from the database
     * @param Agent $agent
     * @return \Illuminate\Http\JsonResponse
     */
    #[OA\Delete(
        path: '/api/agents/{agent}',
        description: 'Delete a single user',
        tags: ['Deleting an Agent'],
        security : [["bearerAuth" => []]],
        parameters: [new OA\Parameter(
            name: "agent",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )],
    )]
    #[OA\Parameter(
        name: 'Authorization',
        in: 'header',
        description: 'Bearer {token}',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Response(
        response: 200,
        description: 'Delete a single user',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'agent is deleted successfully!'),

            ]
        )
    )]

    public function destroy(Agent $agent)
    {

        $agent->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Agent is deleted successfully',
        ], 200);
    }
}
