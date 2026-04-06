<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\CreatesNotifications;

class MessagesController extends Controller
{
    use CreatesNotifications;

    public function index(Request $request)
    {
        $user = Auth::user();

        $acceptedInterests = DB::table('user_interests')
            ->where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->where('status', 'accepted')
            ->get();

        $connectionIds = $acceptedInterests->map(function($interest) use ($user) {
            return $interest->sender_id == $user->id ? $interest->receiver_id : $interest->sender_id;
        })->unique();

        $connections = collect();
        if ($connectionIds->isNotEmpty()) {
            $connections = User::whereIn('id', $connectionIds)->get();
            $connections->each(function($connection) use ($user) {
                $lastMessage = Message::where(function($query) use ($user, $connection) {
                    $query->where('sender_id', $user->id)
                          ->where('receiver_id', $connection->id);
                })->orWhere(function($query) use ($user, $connection) {
                    $query->where('sender_id', $connection->id)
                          ->where('receiver_id', $user->id);
                })->orderBy('created_at', 'desc')->first();

                $connection->last_message = $lastMessage ? [
                    'id' => $lastMessage->id,
                    'message' => $lastMessage->message,
                    'sender_id' => $lastMessage->sender_id,
                    'created_at' => $lastMessage->created_at,
                    'is_read' => $lastMessage->is_read,
                ] : null;

                $connection->unread_count = Message::where('sender_id', $connection->id)
                    ->where('receiver_id', $user->id)
                    ->where('is_read', false)
                    ->count();
            });
        }

        $formatted = $connections->map(function($connection) {
            return [
                'user' => [
                    'id' => $connection->id,
                    'full_name' => $connection->full_name,
                    'profile_image' => $connection->profile_image ? asset('storage/' . $connection->profile_image) : null,
                ],
                'last_message' => $connection->last_message,
                'unread_count' => $connection->unread_count,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $formatted,
        ]);
    }

    public function getChat(User $user)
    {
        $currentUser = Auth::user();

        $hasConnection = DB::table('user_interests')
            ->where(function($query) use ($currentUser, $user) {
                $query->where('sender_id', $currentUser->id)
                      ->where('receiver_id', $user->id)
                      ->where('status', 'accepted');
            })->orWhere(function($query) use ($currentUser, $user) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', $currentUser->id)
                      ->where('status', 'accepted');
            })->exists();

        if (!$hasConnection) {
            return response()->json([
                'status' => 'error',
                'message' => 'No connection found. You need to have accepted interest to message.',
            ], 403);
        }

        $lastMessageId = request()->get('last_message_id');
        $query = Message::where(function($q) use ($currentUser, $user) {
            $q->where('sender_id', $currentUser->id)
              ->where('receiver_id', $user->id);
        })->orWhere(function($q) use ($currentUser, $user) {
            $q->where('sender_id', $user->id)
              ->where('receiver_id', $currentUser->id);
        });

        if ($lastMessageId) {
            $query->where('id', '>', $lastMessageId);
        }

        $messages = $query->orderBy('created_at', 'asc')
            ->with(['sender', 'receiver'])
            ->get();

        // Mark messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->update(['is_read' => true]);

        $formattedMessages = $messages->map(function($message) use ($currentUser) {
            return [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'message' => $message->message,
                'is_read' => $message->is_read,
                'created_at' => $message->created_at,
                'is_sent' => $message->sender_id === $currentUser->id,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'messages' => $formattedMessages,
                'other_user' => [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'profile_image' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
                    'age' => $user->dob ? \Carbon\Carbon::parse($user->dob)->age : null,
                    'occupation' => $user->occupation,
                    'city' => $user->city,
                    'height' => $user->height,
                    'highest_education' => $user->highest_education,
                    'mother_tongue' => $user->mother_tongue,
                ],
            ],
        ]);
    }

    public function sendMessage(Request $request, User $user)
    {
        $currentUser = Auth::user();

        $hasConnection = DB::table('user_interests')
            ->where(function($query) use ($currentUser, $user) {
                $query->where('sender_id', $currentUser->id)
                      ->where('receiver_id', $user->id)
                      ->where('status', 'accepted');
            })->orWhere(function($query) use ($currentUser, $user) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', $currentUser->id)
                      ->where('status', 'accepted');
            })->exists();

        if (!$hasConnection) {
            return response()->json([
                'status' => 'error',
                'message' => 'No connection found',
            ], 403);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $message = Message::create([
            'sender_id' => $currentUser->id,
            'receiver_id' => $user->id,
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        $message->load(['sender', 'receiver']);

        // Create notification
        $this->createNotification(
            $user, // User object (recipient)
            'message', // type
            $currentUser->full_name . ' sent you a message', // message
            $currentUser, // related user (sender)
            'chat_bubble', // icon
            'blue' // icon color
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'message' => $message->message,
                'is_read' => $message->is_read,
                'created_at' => $message->created_at,
            ],
        ]);
    }

    public function getNewMessages(User $user)
    {
        $currentUser = Auth::user();
        $lastMessageId = request()->get('last_message_id');

        if (!$lastMessageId) {
            return response()->json([
                'status' => 'success',
                'data' => ['newMessages' => []],
            ]);
        }

        $messages = Message::where(function($q) use ($currentUser, $user) {
            $q->where('sender_id', $user->id)
              ->where('receiver_id', $currentUser->id);
        })
        ->where('id', '>', $lastMessageId)
        ->orderBy('created_at', 'asc')
        ->get();

        // Mark as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->where('id', '>', $lastMessageId)
            ->update(['is_read' => true]);

        $formatted = $messages->map(function($message) {
            return [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'message' => $message->message,
                'is_read' => $message->is_read,
                'created_at' => $message->created_at,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => ['newMessages' => $formatted],
        ]);
    }

    public function markAsRead(Message $message)
    {
        $user = Auth::user();

        if ($message->receiver_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        $message->update(['is_read' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Message marked as read',
        ]);
    }
}

