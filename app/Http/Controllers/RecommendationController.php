<?php

namespace App\Http\Controllers;

use App\Models\Recommendation;
use Illuminate\Http\Request;
use openapi\Attributes as OA;



class RecommendationController extends Controller
{
    /**
     * @param $propertyId
     * @return Response
     */
    #[OA\Get(
        path: '/api/recommendations/{propertyId}',
        description: 'Get Recommendations',
        tags: ['All Recommendations'],
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
        response: 200,
        description: 'Get all recommendations',
        content: new OA\JsonContent(
            type: 'object',
            properties: [

                new OA\Property(property: 'recommended_properties', type: 'string', example: '[property1 , property2]'),


            ]
        )
    )]
    public function fetchRecommendations($propertyId)
    {
        $recommendation = Recommendation::where('property_id', $propertyId)->first();

        if ($recommendation) {
            return response()->json([
                'recommended_properties' => json_decode($recommendation->recommended_properties)
            ]);
        }

        // If no recommendations found, generate and return new recommendations
        $newRecommendation = Recommendation::generateRecommendations($propertyId);
        return response()->json([
            'recommended_properties' => json_decode($newRecommendation->recommended_properties)
        ]);
    }
     /**
     * @param $propertyId
     * @param Request $request
     * @return Response
     */
    #[OA\Post(
        path: '/api/recommendations/{propertyId}/update',
        description: 'Update Recommendations',
        tags: ['Update Recommendations'],
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
        response: 200,
        description: 'Update a recommendations',
        content: new OA\JsonContent(
            type: 'object',
            properties: [

                new OA\Property(property: 'message', type: 'string', example: 'Recommendation is updated successfully'),


            ]
        )
    )]


    public function updateRecommendations(Request $request, $propertyId)
    {
        // Here you can handle user activity that would affect recommendations,
        // e.g., a user viewed or liked an apartment.

        // For now, let's regenerate recommendations as a simple example.
        Recommendation::where('property_id', $propertyId)->delete();
        Recommendation::generateRecommendations($propertyId);

        return response()->json(['message' => 'Recommendation is updated successfully']);
    }
}
