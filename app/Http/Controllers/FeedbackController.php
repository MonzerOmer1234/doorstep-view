<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    /**
     * submitting feedback
     * @param Request $request
     * @return Response
     */
    public function submitFeedback(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'apartment_id' => 'required|exists:apartments,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create new feedback entry
        $feedback = Feedback::create([
            'user_id' => $request->user_id,
            'apartment_id' => $request->apartment_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Feedback submitted successfully!',
                'feedback' => $feedback
            ], 201);
    }

     /**
     * Method to retrieve feedback for an apartment
     * @param string $apartmentId
     * @return Response
     */
     public function getFeedbackForApartment($apartmentId)
     {
         // Retrieve feedback for the specified apartment
         $feedbacks = Feedback::where('apartment_id', $apartmentId)->with('user')->get();

         if ($feedbacks->isEmpty()) {
             return response()->json([
                'status' => 'fail',
                'message' => 'No feedback found for this apartment.'],
                404);
         }

         return response()->json([
            'status' => 'success',
            'message' => 'feedbacks for this apartment is fetched successfully',
            'feedbacks' => $feedbacks,
            ] , 200);
     }
}
