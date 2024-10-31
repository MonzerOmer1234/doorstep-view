<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ApiKeyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: '/api/api-keys',
        description: 'getting The api key that is not revoked',
        tags: ['Api Key'],
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
        description: 'getting The api key that is not revoked',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'Api key is fetched successfully!'),

                new OA\Property(
                    property: 'api_key',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'key', type: 'string', example:  'xazsdscdssertyyu'),
                        new OA\Property(property: 'type', type: 'string', example: 'type 1'),
                        new OA\Property(property: 'is_revoked', type: 'boolean', example: 'false')
                    ]
                )
            ]
        )
    )]
    public function index()
    {
        //
        $apiKey = ApiKey::where('is_revoked', false)->first();
        return response()->json([
            'status' => 'success',
            'message' => 'API key retrieved successfully',
            'api_key' => $apiKey
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
