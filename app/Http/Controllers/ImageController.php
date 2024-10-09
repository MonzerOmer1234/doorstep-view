<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    // Upload and store image
    public function store(Request $request)
    {
        // Validate the request to ensure an image is uploaded
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // max:2048 = 2MB
        ]);

        // Store the uploaded image in the 'public' directory 
        $path = $request->file('image')->store('images', 'public');

        // Return the path or the image URL
        return response()->json([
            'message' => 'Image uploaded successfully!',
            'image_path' => Storage::url($path), // Generate a public URL for the image
        ]);
    }
}
