<?php
namespace App\Http\Controllers;

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
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'body' => $request->body,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $message,
        ]);
    }

    // Get messages for a specific conversation
    public function getMessages($receiverId)
    {
        $messages = Message::where(function ($query) use ($receiverId) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($receiverId) {
            $query->where('sender_id', $receiverId)
                  ->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $messages,
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
