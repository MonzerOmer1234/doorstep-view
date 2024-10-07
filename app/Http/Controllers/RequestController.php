<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use App\Models\Request; // Assuming the model is named Request
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    // Fetch all requests (for admin or user’s own requests)
    public function index()
    {
        $user = Auth::user();

        // If user is an admin, return all requests, otherwise return only the user’s requests
        if ($user->isAdmin()) {
            $requests = Request::all();
        } else {
            $requests = Request::where('user_id', $user->id)->get();
        }

        return response()->json([
            'status'=> 'success',
            'message' => 'Requests fetched successfully',
            'data' => $requests
        ] , 200) ;
    }

    // Store a new request
    public function store(HttpRequest $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'message' => 'required|string',
        ]);

        $newRequest = Request::create([
            'user_id' => Auth::id(),
            'property_id' => $request->property_id,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return response()->json([
            'status'=> 'success',
            'message' => 'Request created successfully',
            'data' => $newRequest
        ], 201); // 201 Created
    }

    // Show a specific request
    public function show($id)
    {
        $request = Request::findOrFail($id);

        // Allow access only to the owner or admin
        if (Auth::id() !== $request->user_id && !Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403); // Forbidden
        }

        return response()->json([
            'status'=> 'success',
            'message' => 'Request fetched successfully',
            'data' => $request
        ], 200);
    }

    // Update a request (e.g., modify status)
    public function update(HttpRequest $httpRequest, $id)
    {
        $request = Request::findOrFail($id);

        // Allow update only by the owner or admin
        if (Auth::id() !== $request->user_id && !Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Validate the incoming request
        $httpRequest->validate([
            'status' => 'in:pending,approved,rejected',
            'message' => 'nullable|string',
        ]);

        // Update request fields
        $request->update($httpRequest->only(['status', 'message']));

        return response()->json([
            'status'=> 'success',
            'message' => 'Request updated successfully',
            'data' => $request
        ], 200);
    }

    // Delete a request
    public function destroy($id)
    {
        $request = Request::findOrFail($id);

        // Allow delete only by the owner or admin
        if (Auth::id() !== $request->user_id && !Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->delete();

        return response()->json(['message' => 'Request deleted successfully']);
    }
}
