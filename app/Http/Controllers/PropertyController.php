<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;
use App\Notifications\PropertyAddedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;

class PropertyController extends Controller

{
    /**
     * specifies all the properties in the database
     * @return \Illuminate\Http\JsonResponse

     */
    #[OA\Get(
        path: '/api/properties',
        description: 'Get all properties',
        tags: ['All properties'],
        security : [["bearerAuth" => []]],
    )]
    #[OA\Get(
        path: '/api/agents/properties',
        description: 'Get all properties',
        tags: ['All properties'],
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
        path: '/api/agents/properties',
        description: 'Store a property',
        tags: ['Store a property'],
        security : [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'user_id', type: 'integer', example: 1),
                    new OA\Property(property: 'property_type', type: 'string',  example: 'villa'),
                    new OA\Property(property: 'description', type: 'string',  example: 'good villa'),
                    new OA\Property(property: 'title', type: 'string',  example: 'villa 1'),
                    new OA\Property(property: 'price', type: 'string',  example: 20.5),
                    new OA\Property(property: 'location', type: 'string',  example: "Kassala"),
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
            'user_id' => 'required|exists:users,id', // Ensure agent exists
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
        tags: ['The details of a property'],
        security : [["bearerAuth" => []]],
        parameters: [new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )],
    )]
     #[OA\Get(
        path: '/api/agents/properties/{id}',
        description: 'Get the details of a property',
        tags: ['The details of a property'],
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
        description: 'The details of a property',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'property is fetched successfully!'),

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
        path: '/api/agents/properties/{id}',
        description: 'Update a property',
        tags: ['Update a property'],
        security : [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'user_id', type: 'integer', example: 1),
                    new OA\Property(property: 'property_type', type: 'string',  example: 'villa'),
                    new OA\Property(property: 'description', type: 'string',  example: 'good villa'),
                    new OA\Property(property: 'title', type: 'string',  example: 'villa 1'),
                    new OA\Property(property: 'price', type: 'string',  example: 20.5),
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

    public function update(Request $request, Property $property)
    {


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
     * @param $property
     * @return \Illuminate\Http\JsonResponse
     */
    #[OA\Delete(
        path: '/api/agents/properties/{id}',
        description: 'Delete property',
        tags: ['Deletion of a property'],
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

      /**
     * specifies the attachment of amenity to property
     * @param string $propertyId
     * @param $amenityId
     * @return Response
     */
    #[OA\Put(
        path: '/api/agents/properties/attach-amenity/{propertyId}/{amenityId}',
        description: 'attach amenity',
        tags: ['Attach amenities'],
        security : [["bearerAuth" => []]],
        parameters: [new OA\Parameter(
            name: "propertyId",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        ),
        new OA\Parameter(
            name: "amenityId",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
            )
        ]
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
        path: '/api/agents/properties/detach-amenity/{propertyId}/{amenityId}',
        description: 'attach amenity',
        tags: ['Detach amenities'],
        security : [["bearerAuth" => []]],
        parameters: [new OA\Parameter(
            name: "propertyId",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        ),
        new OA\Parameter(
            name: "amenityId",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
            )
        ]
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
 * Get properties within a specified radius from given coordinates
 * @param float $userLat User's latitude
 * @param float $userLng User's longitude
 * @param int $radius Search radius in kilometers
 * @return \Illuminate\Database\Eloquent\Collection
 */

    public function getNearByProperties($userLat, $userLng, $radius = 5)
    {
        try{

            $properties = DB::table('properties')
            ->select('*', DB::raw("
                ( 6371 * acos( cos( radians($userLat) )
                * cos( radians( latitude ) )
                * cos( radians( longitude ) - radians($userLng) )
                + sin( radians($userLat) )
                * sin( radians( latitude ) ) ) )
                AS distance"))
                ->whereNotNull('latitude')  // Ensure coordinates exist
                ->whereNotNull('longitude')
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->get();

            return $properties;
        }
        catch (\Exception $e) {
            Log::error("Error in getNearByProperties: " . $e->getMessage());
            throw $e;
        }
}

/**
 * @param Request $request
 * @return Response
*/
#[OA\Get(
    path: '/api/properties/nearby/{latitude}/{longitude}/{radius?}',
    description: 'Get nearby properties based on coordinates',
    tags: ['Nearby Properties'],
    security: [["bearerAuth" => []]],
    parameters: [
        new OA\Parameter(
            name: "latitude",
            in: "query",  // Changed from requestBody to query parameter
            required: true,
            description: "User's latitude",
            schema: new OA\Schema(type: "number", format: "float")
        ),
        new OA\Parameter(
            name: "longitude",
            in: "query",  // Changed from requestBody to query parameter
            required: true,
            description: "User's longitude",
            schema: new OA\Schema(type: "number", format: "float")
        )
    ]
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
    description: 'Successful response',
    content: new OA\JsonContent(
        type: 'object',
        properties: [
            new OA\Property(property: 'message', type: 'string'),

            new OA\Property(
                property: 'data',
                type: 'object',
                properties: [
                    new OA\Property(
                        property: 'properties',
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'location', type: 'string'),
                            new OA\Property(property: 'property_type', type: 'string'),
                            new OA\Property(property: 'latitude', type: 'number'),
                            new OA\Property(property: 'longitude', type: 'number'),
                            new OA\Property(property: 'distance', type: 'number')
                        ]

                    )
                ]
                )

        ]
    )
)]

public function nearbyProperties(Request $request)
{
    $validator = Validator::make($request->all(), [
        'latitude' => 'required|numeric|between:-90,90',
        'longitude' => 'required|numeric|between:-180,180',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }
    $userLat = $request->input('latitude');
    $userLng = $request->input('longitude');
    $properties = $this->getNearByProperties($userLat, $userLng);

    if ($properties->isEmpty()) {
        return response()->json([
            'message' => 'No properties found in this area',
            'properties' => []
        ], 200);
    }

    return response()->json([
        'message' => 'Properties found successfully',
        'properties' => $properties
    ], 200);
}

/**
 * Count total feedbacks for a specific property
 * @param string $propertyId
 * @return \Illuminate\Http\JsonResponse
 */
#[OA\Get(
    path: '/api/agents/count-property/{propertyId}/feedbacks',
    description: 'Count total feedbacks for a property',
    tags: ['Property Feedback Count'],
    security: [["bearerAuth" => []]],
    parameters: [
        new OA\Parameter(
            name: "propertyId",
            in: "path",
            required: true,
            description: "ID of the property",
            schema: new OA\Schema(type: "integer")
        )
    ]
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
    description: 'Successfully retrieved feedback count',
    content: new OA\JsonContent(
        type: 'object',
        properties: [
            new OA\Property(property: 'property_id', type: 'integer', example: 1),
            new OA\Property(property: 'total_feedback', type: 'integer', example: 5)
        ]
    )
)]

public function countPropertyFeedbacks($propertyId)
{
    $property = Property::findOrFail($propertyId);
    $feedbackCount = $property->getTotalFeedback();

    return response()->json([
        'property_id' => $property->id,
        'total_feedback' => $feedbackCount
    ]);
}

/**
 * Count total visitRequests for a specific property
 * @param string $propertyId
 * @return \Illuminate\Http\JsonResponse
 */

#[OA\Get(
    path: '/api/agents/count-property/{propertyId}/visitRequests',
    description: 'Count total visit requests for a property',
    tags: ['Property Vist Request Count'],
    security: [["bearerAuth" => []]],
    parameters: [
        new OA\Parameter(
            name: "propertyId",
            in: "path",
            required: true,
            description: "ID of the property",
            schema: new OA\Schema(type: "integer")
        )
    ]
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
    description: 'Successfully retrieved feedback count',
    content: new OA\JsonContent(
        type: 'object',
        properties: [
            new OA\Property(property: 'property_id', type: 'integer', example: 1),
            new OA\Property(property: 'total_feedback', type: 'integer', example: 5)
        ]
    )
)]
public function countPropertyVisitRequests($propertyId)
{
    $property = Property::findOrFail($propertyId);
    $totalVisitRequests = $property->getTotalVisitRequests();

    return response()->json([
        'property_id' => $property->id,
        'total_visit_requests' => $totalVisitRequests
    ]);
}



}
