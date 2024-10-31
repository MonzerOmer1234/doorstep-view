<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class FeedbackController extends Controller
{
    /**
     * submitting feedback
     * @param Request $request
     * @return Response
     */
    #[OA\Post(
        path: '/api/feedback',
        description: 'Submit Feedbacks',
        tags: ['Submit Feedback'],
        security : [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'user_id', type: 'integer', example: 1),
                    new OA\Property(property: 'property_id', type: 'integer', example: 1),
                    new OA\Property(property: 'rating', type: 'string', example: "5"),


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
        description: 'Submit Feedback',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'agents are fetched successfully!'),

                new OA\Property(
                    property: 'feedback',
                    type: 'object',
                    properties: [

                        new OA\Property(property: 'rating', type: 'string', example: '5'),
                        new OA\Property(property: 'comment', type: 'string', example: 'This is a feeedback')
                    ]
                )
            ]
        )
    )]
    public function submitFeedback(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'property_id' => 'required|exists:properties,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create new feedback entry
        $feedback = Feedback::create([
            'user_id' => $request->user_id,
            'property_id' => $request->proprty_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Feedback submitted successfully!',
                'feedback' => $feedback
            ], 201);
    }

     /**
     * Method to retrieve feedback for an apartment
     * @param string $apartmentId
     * @return Response
     */
    #[OA\Get(
        path: '/api/properties/{propertyId}/feedback',
        description: 'Get feedbacks for property',
        tags: ['Get Feedback about property'],
        security : [["bearerAuth" => []]],
        parameters: [new OA\Parameter(
            name: "propertyId",
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
        response: 201,
        description: 'Get feedbacks for property',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'Feedbacks for this property is fetched successfully!'),

                new OA\Property(
                    property: 'feedback',
                    type: 'object',
                    properties: [

                        new OA\Property(property: 'rating', type: 'string', example: '5'),
                        new OA\Property(property: 'comment', type: 'string', example: 'This is a nice villa')
                    ]
                )
            ]
        )
    )]
     public function getFeedbackForProperty($propertyId)
     {
         // Retrieve feedback for the specified apartment
         $feedbacks = Feedback::where('property_id', $propertyId)->with('user')->get();

         if ($feedbacks->isEmpty()) {
             return response()->json([
                'status' => 'fail',
                'message' => 'No feedback found for this property.'],
                404);
         }

         return response()->json([
            'status' => 'success',
            'message' => 'feedbacks for this property is fetched successfully',
            'feedbacks' => $feedbacks,
            ] , 200);
     }
}
