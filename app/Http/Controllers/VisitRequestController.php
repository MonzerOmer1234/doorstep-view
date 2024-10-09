<?php
namespace App\Http\Controllers;

use App\Models\VisitRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitRequestController extends Controller
{
    // Create a visit request
    public function create(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'requested_at' => 'required|date',
        ]);

        $visitRequest = VisitRequest::create([
            'property_id' => $request->property_id,
            'user_id' => Auth::id(),
            'requested_at' => $request->requested_at,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Visit request created successfully',
            'data' => $visitRequest,
        ], 201); // 201 Created status
    }

    // Get visit requests for a user
    public function index()
    {
        $visitRequests = VisitRequest::where('user_id', Auth::id())->get();

        return response()->json([
            'success' => true,
            'data' => $visitRequests,
        ]);
    }

    // Update the status of a visit request
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:approved,rejected',
        ]);

        $visitRequest = VisitRequest::findOrFail($id);
        $visitRequest->status = $request->status;
        $visitRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Visit request status updated successfully',
            'data' => $visitRequest,
        ]);
    }
}
