<?php
// app/Http/Controllers/FavoriteController.php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Property;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * A method to add a property to the user's favorites list
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
        ]);

        $favorite = Favorite::create([
            'user_id' => auth()->id(), // Assuming you have authentication set up
            'property_id' => $request->property_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Property added to favorites',
            'favorite' => $favorite,
        ], 201);
    }

    /**
     * A method to remove a property from the user's favorites list
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function remove(Request $request, string $id)
    {
        $favorite = Favorite::where('user_id', auth()->id())
            ->where('property_id', $id)
            ->firstOrFail();

        $favorite->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Property removed from favorites',
        ], 204);
    }

    /**
     * A method to list the user's favorite properties
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $favorites = Favorite::where('user_id', auth()->id())->with('property')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Favorites are retrieved successfully',
            'favorites' => $favorites,
        ], 200);
    }
    
}
