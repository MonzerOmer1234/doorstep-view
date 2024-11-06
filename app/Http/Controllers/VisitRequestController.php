<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\VisitRequest;
use Illuminate\Http\Request;

class VisitRequestController extends Controller
{
    /**
     * Show the form to request a visit to a specified property.
     *
     * @param  int  $propertyId
     * @return \Illuminate\View\View
     */
    public function create($propertyId)
    {
        $property = Property::findOrFail($propertyId);

        return response()->json([
            'status' => 'success',
            'property' => $property,
        ]);
    }

    /**
     * Store a new visit request for a specified property.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $propertyId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $propertyId)
    {
        $request->validate([
            'visitor_name' => 'required|string|max:255',
            'visitor_email' => 'required|email',
            'visit_date' => 'required|date|after:today',
        ]);

        $property = Property::findOrFail($propertyId);

        $visitRequest = VisitRequest::create([
            'property_id' => $property->id,
            'visitor_name' => $request->visitor_name,
            'visitor_email' => $request->visitor_email,
            'visit_date' => $request->visit_date,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'The visit request is stored successfully',
            'visit_request' => $visitRequest,
        ]);
    }
}
