<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
/**
 * specifices all the agents in the database
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
{
    public function index()
    {
        $agents = Agent::all();
        return response()->json([

            'status' => 'success',
            'message' => 'Agents retrieved successfully',
            'agesnts' => $agents
        ]);
    }
    /**
     * creates a new agent in the database
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

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
            'message' => 'Agent created successfully',
            'agent' => $agent
        ], 201);
    }

    /**
     * retrieves a specific agent from the database
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function show(string $id)
    {
        $agent = Agent::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Agent retrieved successfully',
            'agent' => $agent
        ], 200);
    }
    /**
     * updates a specific agent in the database
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(Request $request, string $id)
    {
        $agent = Agent::findOrFail($id);

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
            'message' => 'Agent updated successfully',
            'agent' => $agent
        ], 200);
    }
    /**
     * deletes a specific agent from the database
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy(string $id)
    {
        $agent = Agent::findOrFail($id);
        $agent->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Agent deleted successfully',
        ], 200);
    }
}
