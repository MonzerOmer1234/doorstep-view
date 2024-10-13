<?php
namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Send a message
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
    public function getMessages($receiverId , Request $request)
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
