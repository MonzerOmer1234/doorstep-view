<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    //
    public function show($propertyId)
{
    // Assume Property is your model
    $property = Property::find($propertyId);
    // Query to find nearby amenities within the distance range
    $nearbyAmenities = Amenity::selectRaw("
    name,
    (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance
    ", [$property->latitude, $property->longitude, $property->latitude])
    ->having('distance', '<=', 5)
    ->orderBy('distance', 'asc')->get();


    return view('property.show', compact('property' , 'nearbyAmenities'));
}

}
