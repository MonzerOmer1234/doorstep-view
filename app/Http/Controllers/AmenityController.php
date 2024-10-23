<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\Property;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return Response
     */
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


