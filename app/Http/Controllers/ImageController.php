<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use openapi\Attributes as OA;

class ImageController extends Controller
{
    // Upload and store image
    /**
     * @param Request $request
     * @return Response
     */
    #[OA\Post(
        path: '/api/upload-image',
        description: 'Storing images of the system',
        tags: ['Store Images']
    )]
    #[OA\Response(
        response: 200,
        description: 'Storing Images of the system',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'image_path', type: 'string', example: 'path 1'),
                new OA\Property(property: 'message', type: 'string', example: 'agents are fetched successfully!'),


            ]
        )
    )]
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
            'message' => 'Image is uploaded successfully!',
            'image_path' => Storage::url($path), // Generate a public URL for the image
        ]);
    }
}
