<?php

namespace App\Http\Controllers;

use App\Models\Recommendation;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function fetchRecommendations($apartmentId)
    {
        $recommendation = Recommendation::where('apartment_id', $apartmentId)->first();

        if ($recommendation) {
            return response()->json([
                'recommended_apartments' => json_decode($recommendation->recommended_apartments)
            ]);
        }

        // If no recommendations found, generate and return new recommendations
        $newRecommendation = Recommendation::generateRecommendations($apartmentId);
        return response()->json([
            'recommended_apartments' => json_decode($newRecommendation->recommended_apartments)
        ]);
    }

    public function updateRecommendations(Request $request, $apartmentId)
    {
        // Here you can handle user activity that would affect recommendations,
        // e.g., a user viewed or liked an apartment.

        // For now, let's regenerate recommendations as a simple example.
        Recommendation::where('apartment_id', $apartmentId)->delete();
        Recommendation::generateRecommendations($apartmentId);

        return response()->json(['message' => 'Recommendations updated successfully']);
    }
}
