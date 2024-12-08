<?php

namespace App\Http\Controllers;

use App\Models\VisitRequest;
use App\Models\Property;
use Illuminate\Http\Request;

class VisitRequestController extends Controller
{
    /**
     * List all visit requests for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $visitRequests = VisitRequest::where('user_id', auth()->id())
            ->with('property') 
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Visit requests fetched successfully',
            'visit_requests' => $visitRequests
        ]);
    }

    /**
     * Store a new visit request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'visitor_name' => 'required|string|max:255',
            'visitor_email' => 'required|email',
            'visit_date' => 'required|date|after:today',
        ]);

        $visitRequest = VisitRequest::create([
            'user_id' => auth()->id(),
            'property_id' => $request->property_id,
            'visitor_name' => $request->visitor_name,
            'visitor_email' => $request->visitor_email,
            'visit_date' => $request->visit_date,
            'requested_at' => now(), // Automatically set the current timestamp
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Visit request created successfully',
            'visit_request' => $visitRequest
        ], 201);
    }

    /**
     * Delete a specific visit request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $visitRequest = VisitRequest::findOrFail($id);

        if ($visitRequest->user_id !== auth()->id()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $visitRequest->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Visit request deleted successfully'
        ]);
    }
}
