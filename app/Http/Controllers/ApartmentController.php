<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\Request;

class ApartmentController extends Controller
/**
 * specifies all apartments
 * @return Response
 */
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


    /**
     * specifies the attachment of amenity to apartment
     * @param string $apartmentId
     * @param $amenityId
     * @return Response
     */
    public function attachAmenity(string $apartmentId , string $amenityId){
        $apartment = Apartment::findOrFail($apartmentId);
        $apartment->addAmenity($amenityId);
        return response()->json([
            'status' => 'success',
            'message' => 'The amenity is attached successfully',

        ] , 200);

    }

    /**
     * specifies the detachment of amenity from apartment
     * @param string $apartmentId
     * @param $amenityId
     * @return Response
     */
    public function detachAmenity(string $apartmentId , string $amenityId){
        $apartment = Apartment::findOrFail($apartmentId);
        $apartment->detachAmenity($amenityId);
        return response()->json([
            'status' => 'success',
            'message' => 'The amenity is detached successfully',

        ] , 200);

    }
    public function getNearbyApartments($userLat, $userLng, $radius = 5)
    {
        $apartments = \Illuminate\Support\Facades\DB::table('apartments')
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

    return $apartments;
}
    public function nearbyApartments(Request $request)
{
    $userLat = $request->input('latitude');
    $userLng = $request->input('longitude');

    if (!$userLat || !$userLng) {
        return response()->json(['error' => 'User location is required'], 400);
    }

    // Get apartments within a 5 km range
    $apartments = $this->getNearbyApartments($userLat, $userLng);

    return response()->json($apartments);
}


}
