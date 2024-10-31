<?php
// app/Http/Controllers/FavoriteController.php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Property;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;


class FavoriteController extends Controller
{
    /**
     * A method to add a property to the user's favorites list
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    #[OA\Post(
        path: '/api/favorites',
        description: 'Create a new favorite',
        tags: ['Create Favourite'],
        security : [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'property_id', type: 'integer', example: 1),

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
        description: 'Create a new favorite',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'property is added to favorites successfully!'),

                new OA\Property(
                    property: 'favorite',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'user_id', type: 'integer', example:  1),
                        new OA\Property(property: 'property_id', type: 'integer', example: 3),

                    ]
                )
            ]
        )
    )]
    public function add(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
        ]);

        $favorite = Favorite::create([
            'user_id' => auth()->id(), // Assuming you have authentication set up
            'property_id' => $request->property_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Property added to favorites',
            'favorite' => $favorite,
        ], 201);
    }

    /**
     * A method to remove a property from the user's favorites list
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    #[OA\Delete(
        path: '/api/favorites/{id}',
        description: 'Delete a favorite',
        tags: ['Delete Favourite'],
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
        description: 'Delete a favorite',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'property is removed from favorites successfully!'),


            ]
        )
    )]


    public function remove(Request $request, string $id)
    {
        $favorite = Favorite::where('user_id', auth()->id())
            ->where('property_id', $id)
            ->firstOrFail();

        $favorite->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Property is removed from favorites',
        ], 200);
    }

    /**
     * A method to list the user's favorite properties
     * @return \Illuminate\Http\JsonResponse
     */
    #[OA\Get(
        path: '/api/favorites',
        description: 'get the list of favorites',
        tags: ['All Favourites'],
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
        description: 'get the list of favorites',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'All favorites are fetched successfully!'),

                new OA\Property(
                    property: 'favorite',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'user_id', type: 'integer', example:  1),
                        new OA\Property(property: 'property_id', type: 'integer', example: 3),

                    ]
                )
            ]
        )
    )]
    public function list()
    {
        $favorites = Favorite::where('user_id', auth()->id())->with('property')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Favorites are retrieved successfully',
            'favorites' => $favorites,
        ], 200);
    }

}
