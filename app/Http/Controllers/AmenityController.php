<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\Property;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class AmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    #[OA\Get(
        path: '/api/amenities',
        description: 'getting all amenities',
        tags: ['All Amenities']
    )]
    #[OA\Response(
        response: 200,
        description: 'Fetching all amenities',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'amenities are fetched successfully!'),

                new OA\Property(
                    property: 'amenities',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'name', type: 'integer', example:  'wifi'),
                        new OA\Property(property: 'icon', type: 'string', example: 'icon'),
                        new OA\Property(property: 'category', type: 'string', example: 'category 1')
                    ]
                )
            ]
        )
    )]
    public function index()
    {
        //
        $amenities = Amenity::all();
        return response()->json([
            'status' => 'success',
            'message' => 'The amenities are fetched successfully',
            'amenities' => $amenities,
        ] , 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    #[OA\Post(
        path: '/api/amenities',
        description: 'craete an amenity',
        tags: ['Create an amenity']
    )]
    #[OA\Response(
        response: 200,
        description: 'create an amenity',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'the amenity is created successfully!'),

                new OA\Property(
                    property: 'agents',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'icon', type: 'integer', example: "icon"),
                        new OA\Property(property: 'name', type: 'string', example: 'wifi'),
                        new OA\Property(property: 'category', type: 'string', example: 'cateory 2')
                    ]
                )
            ]
        )
    )]
    public function store(Request $request)
    {
        //
        $fields = $request->validate([
            'name' => 'required',
            'icon' => 'required'
        ]);
        $amenity = Amenity::create($fields);
        return response()->json([
            'status' => 'success',
            'message' => 'The amenity is created successfully',
            'amenity' => $amenity
        ] , 200);
    }



    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return Response
     */
    #[OA\Put(
        path: '/api/agents/{agent}',
        description: 'Update an agent',
        tags: ['Update agent']
    )]
    #[OA\Response(
        response: 200,
        description: 'Updating an agent',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'The amenity is updated successfully!'),

                new OA\Property(
                    property: 'amenity',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'icon', type: 'integer', example: 'icon'),
                        new OA\Property(property: 'name', type: 'string', example: 'wifi'),
                        new OA\Property(property: 'category', type: 'string', example: 'cat 2')
                    ]
                )
            ]
        )
    )]

    public function update(Request $request, string $id)
    {
        //
        $amenity = Amenity::findOrFail($id);
        $fields = $request->validate([
            'name' => 'required',
            'icon' => 'required'
        ]);
        $amenity->update($fields);
        return response()->json([
            'status' => 'success',
            'message' => 'The amenity is updated successfully',
            'amenity' => $amenity
        ] , 200);

    }

    /**
     * Remove the specified resource from storage.
     * @param string $id
     * @return Response
     */
    #[OA\Delete(
        path: '/api/agents/{id}',
        description: 'Deleting an agent',
        tags: ['Delete an agent']
    )]
    #[OA\Response(
        response: 200,
        description: 'Deleting an agent',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'amenity is deleted successfully!'),


            ]
        )
    )]
    public function destroy(string $id)
    {
        //
        $amenity = Amenity::findOrFail($id);
        $amenity->delete();
        return response()->json([
            'status' => 'succcess',
            'message' => 'The amenity is deleted successfully'
        ] , 200);
    }



    // Method to get nearby amenities for a specific property
    #[OA\Get(
        path: '/api/properties/{id}/amenities',
        description: 'get nearby amenities',
        tags: ['Nearby amenities']
    )]
    #[OA\Response(
        response: 200,
        description: 'get nearby amenities',
        content: new OA\JsonContent(
            type: 'object',
            properties: [


                new OA\Property(
                    property: 'property',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'title', type: 'integer', example: "villa"),
                        new OA\Property(property: 'description', type: 'string', example: 'beside somewhere'),
                        new OA\Property(property: 'price', type: 'string', example: '$1000')
                    ]
                    ),
                new OA\Property(
                    property: 'amenities',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'icon', type: 'integer', example: "icon"),
                        new OA\Property(property: 'name', type: 'string', example: 'wifi'),
                        new OA\Property(property: 'category', type: 'string', example: 'cat 2')
                    ]
                )
            ]
        )
    )]
    public function getNearbyAmenities($propertyId, Request $request)
    {
        // Find the property by ID
        $property = Property::findOrFail($propertyId);

        // Set the distance threshold (in kilometers or miles)
        $distance = $request->input('distance', 5); // Default to 5 km

        // Query to find nearby amenities within the distance range
        $amenities = Amenity::selectRaw("
            id, name, address, category, latitude, longitude,
            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance
        ", [$property->latitude, $property->longitude, $property->latitude])
            ->having('distance', '<=', $distance)
            ->orderBy('distance', 'asc')
            ->get();

        return response()->json([
            'property' => $property,
            'amenities' => $amenities
        ]);
    }
}


