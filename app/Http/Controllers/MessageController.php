<?php
namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use openapi\Attributes as OA;

class MessageController extends Controller
{
    // Send a message
    /**
     * @param Request $request
     * @return Response
     */
    #[OA\Post(
        path: '/api/messages/send',
        description: 'Sending Messages in the system',
        tags: ['Send Messages'],
        security : [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'receiver_id', type: 'integer', example: 1),
                    new OA\Property(property: 'body', type: 'string', example: "message"),

                ]
            )
        )
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
        description: 'Sending Messages in the system',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'sender_id', type: 'integer', example: 1),
                new OA\Property(property: 'receiver_id', type: 'integer', example: 5),
                new OA\Property(property: 'body', type: 'string', example: 'Hello John Doe'),


            ]
        )
    )]
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',

            'body' => 'required|string',
            'media_files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,wmv,avi,mkv,flv,webm|max:10240',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'body' => $request->body,
        ]);
        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $file) {
                $path = $file->store('media', 'public'); // Save file in storage/public/media

                Media::create([
                    'message_id' => $message->id,
                    'media_type' => $file->getClientMimeType(), // 'image/jpeg', 'video/mp4', etc.
                    'media_path' => $path,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $message->load('media'),
        ]);
    }

    // Get messages for a specific conversation
    /**
     * @param string $receiverId
     * @param Request $request
     * @return Response
     */
    #[OA\Get(
        path: '/api/messages/{receiverId}',
        description: 'Receiving Messages',
        tags: ['Receive Message'],
        security : [["bearerAuth" => []]],
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
        description: 'Receiving Messages in the system',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'data', type: 'string', example: 'hello 1 , hello 2'),



            ]
        )
    )]

    public function getMessages(string $receiverId , Request $request)
    {
        $messages = Message::where(function ($query) use ($receiverId) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($receiverId) {
            $query->where('sender_id', $receiverId)
                  ->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();
        // to be reviewed

    $messages_with_media = Message::with('media')->where('sender_id', $request->user()->id)
        ->orWhere('receiver_id', $request->user()->id)
        ->get();




        return response()->json([
            'success' => true,
            'data' => $messages,
            'message_with_media' => $messages_with_media
        ]);
    }

    // Mark a message as read
    /**
     * @param string id
     * @return Response
     */
    #[OA\Patch(
        path: '/api/messages/{id}/read',
        description: 'Mark messages as read',
        tags: ['Mark Messages as read'],
        security : [["bearerAuth" => []]],
        parameters: [new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )],
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
        description: 'Updating messages as read messages',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'string', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'Mark Message as read')



            ]
        )
    )]
    public function markAsRead($id)
    {
        $message = Message::findOrFail($id);
        $message->is_read = true;
        $message->save();

        return response()->json([
            'success' => true,
            'message' => 'Message marked as read',
        ]);
    }
}
