<?php

namespace App\Http\Controllers;

use App\Models\Search;
use App\Models\SearchLog;
use Illuminate\Http\Request;
use openapi\Attributes as OA;


class SearchController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    #[OA\Get(
        path: '/api/search/properties',
        description: 'Search Properties',
        tags: ['Search Properties'],
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
        description: 'Search Properties',
        content: new OA\JsonContent(
            type: 'object',
            properties: [

                new OA\Property(
                    property: 'properties',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'location', type: 'string', example:  'Egypt'),
                        new OA\Property(property: 'property_type', type: 'string', example: 'villa'),
                        new OA\Property(property: 'bedrooms', type: 'integer', example: 3),
                        new OA\Property(property: 'bathrooms', type: 'integer', example: 10),

                    ]
                )


            ]
        )
    )]
    public function search(Request $request)
    {
        $filters = $request->query();

        // Log the search filters
        SearchLog::create(['filters' => json_encode($filters)]);

        $search = new Search();
        $properties = $search->filter($filters);

        // Check if the result is empty
        if ($properties->isEmpty()) {
            return response()->json(['message' => 'No matches found'], 404);
        }

        return response()->json($properties);
    }
}
