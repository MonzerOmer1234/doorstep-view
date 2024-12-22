<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    //
    public function show($propertyId)
{
    $property = Property::find($propertyId); // Assume Property is your model
    return view('property.show', compact('property'));
}

}
