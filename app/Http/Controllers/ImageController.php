<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenApi\Attributes as OA;

class ImageController extends Controller
{
    // Upload and store image
    /**
     * @param Request $request
     * @return Response
     */
    #[OA\Post(
        path: '/api/agents/upload-image',
        description: 'Upload an image file',
        tags: ['Image Upload'],
        security : [["bearerAuth" => []]],

        requestBody: new OA\RequestBody(
            required: true,

            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'image', type: 'string', format: 'binary', description: 'Image file to upload'),
                    ]
                )
            )
        ),
    )]
    #[OA\Parameter(
        name: 'Authorization',
        in: 'header',
        description: 'Bearer {token}',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Response(
        response: 200,
        description: 'Storing Images of the system',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'image_path', type: 'string', example: 'path 1'),
                new OA\Property(property: 'message', type: 'string', example: 'Image is stored successfully!'),


            ]
        )
    )]
    public function store(Request $request)
    {
        // Validate the request to ensure an image is uploaded
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:500', // max:500 = 500KB
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
