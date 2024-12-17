<?php

namespace App\Http\Controllers;

use App\Models\VisitRequest;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class VisitRequestController extends Controller
{
      /**
     * List all visit requests for the authenticated user.
     *

     */
    #[OA\Get(
        path: '/api/visit-requests',
        description: 'Get all Visit Requests',
        tags: ['All visit Requests'],
        security : [["bearerAuth" => []]],
    )]
    #[OA\Get(
        path: '/api/agents/visit-requests',
        description: 'Get all visit Requests',
        tags: ['All visit Requests'],
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
        description: 'Get all visitRequests',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'visitRequests are fetched successfully!'),

                new OA\Property(
                    property: 'data',
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'visitRequests',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'status', type: 'string'),
                                new OA\Property(property: 'visitor_name', type: 'string'),

                            ]

                        )
                    ]
                    )
            ]
        )
    )]

    public function list()
    {
        $visitRequests = VisitRequest::where('user_id', auth()->id())
            ->with('property')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Visit requests fetched successfully',
            'visit_requests' => $visitRequests
        ]);
    }

    /**
     * Store a new visit request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**

     */
    #[OA\Post(
        path: '/api/visit-requests',
        description: 'Store a visit request',
        tags: ['Store a visit request'],
        security : [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [

                    new OA\Property(property: 'property_type', type: 'string',  example: 'villa'),
                    new OA\Property(property: 'visitor_name', type: 'string',  example: 'MO'),
                    new OA\Property(property: 'visitor_email', type: 'email',  example: 'monzeromer@gmail.com'),
                    new OA\Property(property: 'visit_date', type: 'date',  example: "21-10-2020"),

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
        description: 'Create a visit request',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'visit request is created successfully!'),

                new OA\Property(
                    property: 'data',
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'visitRequests',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'status', type: 'string'),
                                new OA\Property(property: 'visitor_name', type: 'string'),

                            ]

                        )
                    ]
                    )



            ]

                )

    )]
    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'visitor_name' => 'required|string|max:255',
            'visitor_email' => 'required|email',
            'visit_date' => 'required|date|after:today',
        ]);

        $visitRequest = VisitRequest::create([
            'user_id' => auth()->id(),
            'property_id' => $request->property_id,
            'visitor_name' => $request->visitor_name,
            'visitor_email' => $request->visitor_email,
            'visit_date' => $request->visit_date,
            'requested_at' => now(), // Automatically set the current timestamp
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Visit request created successfully',
            'visit_request' => $visitRequest
        ], 201);
    }

    /**
     * Delete a specific visit request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */

    #[OA\Delete(
        path: '/api/visti-requests/{id}',
        description: 'Delete visit request',
        tags: ['Deletion of a visit request'],
        security : [["bearerAuth" => []]],
        parameters: [new OA\Parameter(
            name: "id",
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
        description: 'Deletion of a visit request',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'vsist request is deleted successfully!'),


            ]
        )
    )]
    public function destroy($id)
    {
        $visitRequest = VisitRequest::findOrFail($id);

        if ($visitRequest->user_id !== auth()->id()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $visitRequest->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Visit request is deleted successfully'
        ]);
    }


    #[OA\Put(
        path: '/api/agents/visit-request/approve/{id}',
        description: 'Approve Visit Request',
        tags: ['Approve Visit Request'],
        security : [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [

                    new OA\Property(property: 'access_code', type: 'string', example: '980olkj')
                ]
            )
                ),
        parameters: [new OA\Parameter(
            name: "id",
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
        description: 'Approving a visit request',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'The visit request has been approved successfully!'),

                new OA\Property(
                    property: 'viist_request',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'visitor_name', type: 'string', example: 'mo'),
                        new OA\Property(property: 'visitor_email', type: 'string', example: 'monzeromer048@gmail.com'),

                    ]
                )
            ]
        )
    )]
    public function approveVisitRequest(Request $request, string $id)
    {
        //
        $visitRequest = VisitRequest::findOrFail($id);
        $fields = $request->validate([
            'access_code' => 'required | min:6',

        ]);
        $visitRequest->update([
            'access_code' => $request->access_code,
            'status' => 'approved'

        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'The visitRequest has been approved',
            'visit_request' => $visitRequest,
            
        ] , 200);

    }
}
