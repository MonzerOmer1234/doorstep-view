<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;
use App\Notifications\PropertyAddedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use openapi\Attributes as OA;

class PropertyController extends Controller

{
    /**
     * specifies all the properties in the database
     * @return \Illuminate\Http\JsonResponse

     */
    #[OA\Get(
        path: '/api/properties',
        description: 'Get all properties',
        tags: ['All properties']
    )]
    #[OA\Response(
        response: 200,
        description: 'Get all properties',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'properties are fetched successfully!'),

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
    public function index()
    {
        $properties = Property::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Properties retrieved successfully',
            'properties' => $properties
        ], 200);
    }
    /**
     * creates a new property in the database
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    #[OA\Post(
        path: '/api/properties',
        description: 'Store a property',
        tags: ['Store a property']
    )]
    #[OA\Response(
        response: 200,
        description: 'Create a property',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'property is created successfully!'),

                new OA\Property(
                    property: 'property',
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'agent_id' => 'required|exists:agents,id', // Ensure agent exists
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'location' => 'required|string|max:255',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'area' => 'nullable|integer',
            'property_type' => 'required|string',
            'status' => 'in:available,sold,reserved', // Ensure status is valid
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $property = Property::create($request->all());
        $users = User::all();
        foreach ($users as $user) {
            $user->notify(new PropertyAddedNotification($property));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Property created successfully and users are notified',
            'property' => $property
        ], 201);
    }
    /**
     * retrieves a specific property from the database
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */

     #[OA\Get(
        path: '/api/properties/{id}',
        description: 'Get the details of a property',
        tags: ['The details of a property']
    )]
    #[OA\Response(
        response: 200,
        description: 'The details of a property',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'property is fethced successfully!'),

                new OA\Property(
                    property: 'data',
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
     public function show(string $id)
     {
         // Retrieve the property
         $property = Property::findOrFail($id);

         // Increment the view count
         $property->increment('view_count');

        // Return a JSON response with the property data
        return response()->json([
            'success' => true,
            'data' => $property,
        ] , 200);
     }
    /**
     * updates a specific property in the database
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    #[OA\Patch(
        path: '/api/properties/{id}',
        description: 'Update a property',
        tags: ['Update a property']
    )]
    #[OA\Response(
        response: 200,
        description: 'Update a property',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'property is updated successfully!'),

                new OA\Property(
                    property: 'property',
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

    public function update(Request $request, string$id)
    {
        $property = Property::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'location' => 'nullable|string|max:255',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'area' => 'nullable|integer',
            'property_type' => 'nullable|string',
            'status' => 'in:available,sold,reserved',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $property->update($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Property updated successfully',
            'property' => $property
        ], 200);
    }
    /**
     * deletes a specific property from the database
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    #[OA\Delete(
        path: '/api/properties/{id}',
        description: 'Delete property',
        tags: ['Deletion of a property']
    )]
    #[OA\Response(
        response: 200,
        description: 'Deletion of a property',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'property is deleted successfully!'),


            ]
        )
    )]

    public function destroy(string $id)
    {
        $property = Property::findOrFail($id);
        $property->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Property is deleted successfully',
        ], 200);
    }
     // Method to feature properties
     /**
      * @param Property $property
      * @return Response
      */
      #[OA\Patch(
        path: '/api/properties/feature',
        description: 'Featuring Properties',
        tags: ['Featuring Properties']
    )]
    #[OA\Response(
        response: 200,
        description: 'Featuring properties',
        content: new OA\JsonContent(
            type: 'object',
            properties: [

                new OA\Property(property: 'message', type: 'string', example: 'property is featured successfully!'),


            ]
        )
    )]
     public function feature(Property $property)
     {
         $property->update(['featured' => true]);

         return response()->json([
            'message' => 'property is featured successfully'
         ] , 200);
     }
      /**
     * specifies the attachment of amenity to property
     * @param string $propertyId
     * @param $amenityId
     * @return Response
     */
    #[OA\Put(
        path: '/api/properties/attach-amenity/{propertyId}/{amenityId}',
        description: 'attach amenity',
        tags: ['Attach amenities']
    )]
    #[OA\Response(
        response: 200,
        description: 'attach amenities',
        content: new OA\JsonContent(
            type: 'object',
            properties: [

                new OA\Property(property: 'message', type: 'string', example: 'The amenity is attached successfully!'),


            ]
        )
    )]


    public function attachAmenity(string $propertyId , string $amenityId){
        $property = Property::findOrFail($propertyId);
        $property->addAmenity($amenityId);
        return response()->json([
            'status' => 'success',
            'message' => 'The amenity is attached successfully',

        ] , 200);

    }
        /**
     * specifies the detachment of amenity from property
     * @param string $propertyId
     * @param $amenityId
     * @return Response
     */
    #[OA\Delete(
        path: '/api/properties/detach-amenity/{propertyId}/{amenityId}',
        description: 'attach amenity',
        tags: ['Detach amenities']
    )]
    #[OA\Response(
        response: 200,
        description: 'Detach amenities',
        content: new OA\JsonContent(
            type: 'object',
            properties: [

                new OA\Property(property: 'message', type: 'string', example: 'The amenity is detached successfully!'),
                new OA\Property(property: 'status', type: 'string', example: 'success'),


            ]
        )
    )]
    public function detachAmenity(string $propertyId , string $amenityId){
        $property = Property::findOrFail($propertyId);
        $property->detachAmenity($amenityId);
        return response()->json([
            'status' => 'success',
            'message' => 'The amenity is detached successfully',

        ] , 200);

    }
  /**
   * @param $userLat
   * @param $userLng
   * @param $radius
   * @return Response
   */
    public function getNearByProperties($userLat, $userLng, $radius = 5)
    {
        $properties = \Illuminate\Support\Facades\DB::table('properties')
            ->select('*', \Illuminate\Support\Facades\DB::raw("
                ( 6371 * acos( cos( radians($userLat) )
                * cos( radians( latitude ) )
                * cos( radians( longitude ) - radians($userLng) )
            + sin( radians($userLat) )
            * sin( radians( latitude ) ) ) )
            AS distance"))
        ->having('distance', '<=', $radius)
        ->orderBy('distance')
        ->limit(6) // Return six apartments
        ->get();

    return response()->json([
        'properties' => $properties
    ] , 200);
}

/**
 * @param Request $request
 * @return Response
 */
#[OA\Get(
    path: '/api/nearby-properties',
    description: 'These are nearby properties',
    tags: ['Nearby Properties']
)]
#[OA\Response(
    response: 200,
    description: 'These are nearby properties',
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
public function nearByProperties(Request $request)
{
    $userLat = $request->input('latitude');
    $userLng = $request->input('longitude');

    if (!$userLat || !$userLng) {
        return response()->json(['error' => 'User location is required'], 400);
    }

    // Get apartments within a 5 km range
    $properties = $this->getNearByProperties($userLat, $userLng);

    return response()->json([
        'properties' => $properties
    ] , 200);
}



}
