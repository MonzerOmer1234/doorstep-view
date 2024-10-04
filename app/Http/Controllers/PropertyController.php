<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
/**
 * specifies all the properties in the database
 * @return \Illuminate\Http\JsonResponse

 */
{
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
        return response()->json([
            'status' => 'success',
            'message' => 'Property created successfully',
            'property' => $property
        ], 201);
    }
    /**
     * retrieves a specific property from the database
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function show(string $id)
    {
        $property = Property::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Property retrieved successfully',
            'property' => $property
        ], 200);
    }
    /**
     * updates a specific property in the database
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */

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

    public function destroy(string $id)
    {
        $property = Property::findOrFail($id);
        $property->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Property deleted successfully',
        ], 200);
    }
}