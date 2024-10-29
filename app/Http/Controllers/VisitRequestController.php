<?php
namespace App\Http\Controllers;

use App\Models\VisitRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use openapi\Attributes as OA;

class VisitRequestController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    // Create a visit request
    #[OA\Post(
        path: '/api/visitRequests/create',
        description: 'create a visit request',
        tags: ['Create a visist request']
    )]
    #[OA\Response(
        response: 201,
        description: 'Create a visist request',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'Visit request created successfully'),

                new OA\Property(
                    property: 'data',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'property_id', type: 'unsignedBigInteger', example: 1),
                        new OA\Property(property: 'user_id', type: 'unsignedBigInteger', example: 2),
                        new OA\Property(property: 'requested_at', type: 'dateTime', example: 21-12-2000),
                        new OA\Property(property: 'status', type: 'string', example: 'pending')
                    ]
                )
            ]
        )
    )]
    public function create(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'requested_at' => 'required|date',
        ]);

        $visitRequest = VisitRequest::create([
            'property_id' => $request->property_id,
            'user_id' => Auth::id(),
            'requested_at' => $request->requested_at,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Visit request created successfully',
            'data' => $visitRequest,
        ], 201); // 201 Created status
    }

    // Get visit requests for a user
    /**
     * @return Response
     */
    #[OA\Get(
        path: '/api/visitRequests',
        description: 'All visit requests',
        tags: ['Visit Requests']
    )]
    #[OA\Response(
        response: 200,
        description: 'All visit requests',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),


                new OA\Property(
                    property: 'data',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'property_id', type: 'unsignedBigInteger', example: 1),
                        new OA\Property(property: 'user_id', type: 'unsignedBigInteger', example: 2),
                        new OA\Property(property: 'requested_at', type: 'dateTime', example: 21-12-2000),
                        new OA\Property(property: 'status', type: 'string', example: 'pending')
                    ]
                )
            ]
        )
    )]
    public function index()
    {
        $visitRequests = VisitRequest::where('user_id', Auth::id())->get();

        return response()->json([
            'success' => true,
            'data' => $visitRequests,
        ]);
    }

    // Update the status of a visit request
    /**
     * @param Request $request
     * @param $id
     * @return Response
     */
    #[OA\Patch(
        path: '/api/visitRequests/{id}',
        description: 'Update visit request',
        tags: ['Update Visit Request']
    )]
    #[OA\Response(
        response: 200,
        description: 'Update visit request',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property:'message' , type:'string' , example: 'Visit request status updated successfully'),


                new OA\Property(
                    property: 'data',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'property_id', type: 'unsignedBigInteger', example: 1),
                        new OA\Property(property: 'user_id', type: 'unsignedBigInteger', example: 2),
                        new OA\Property(property: 'requested_at', type: 'dateTime', example: 21-12-2000),
                        new OA\Property(property: 'status', type: 'string', example: 'pending')
                    ]
                )
            ]
        )
    )]
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:approved,rejected',
        ]);

        $visitRequest = VisitRequest::findOrFail($id);
        $visitRequest->status = $request->status;
        $visitRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Visit request status updated successfully',
            'data' => $visitRequest,
        ]);
    }
}
