<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\Request;

class ApartmentController extends Controller
{
    public function index()
    {
        return Apartment::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            // Add other validation rules as needed
        ]);

        return Apartment::create($request->all());
    }

    public function show($id)
    {
        return Apartment::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $apartment = Apartment::findOrFail($id);
        $apartment->update($request->all());
        return $apartment;
    }

    public function destroy($id)
    {
        Apartment::destroy($id);
        return response()->noContent();
    }

    // Method to feature apartments
    public function feature(Apartment $apartment)
    {
        $apartment->update(['featured' => true]);

        return redirect()->route('apartments.index')->with('success', 'Apartment featured successfully.');
    }

    public function attachAmenity(string $apartmentId , string $amenityId){
        $apartment = Apartment::findOrFail($apartmentId);
        $apartment->addAmenity($amenityId);
        return response()->json([
            'status' => 'success',
            'message' => 'The amenity is attached successfully',

        ] , 200);

    }
    public function detachAmenity(string $apartmentId , string $amenityId){
        $apartment = Apartment::findOrFail($apartmentId);
        $apartment->detachAmenity($amenityId);
        return response()->json([
            'status' => 'success',
            'message' => 'The amenity is detached successfully',

        ] , 200);

    }
}
