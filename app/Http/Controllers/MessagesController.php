<?php

namespace App\Http\Controllers;

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
        $selectedUserId = $request->get('user');
        
        // Get accepted interests (mutual connections)
        $connections = collect();
        try {
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
            
            if ($connectionIds->isNotEmpty()) {
                $connections = User::whereIn('id', $connectionIds)->get();
                $connections->each(function($connection) use ($user) {
                    if ($connection->dob) {
                        $connection->age = \Carbon\Carbon::parse($connection->dob)->age;
                    }
                    
                    // Get last message and unread count
                    $lastMessage = Message::where(function($query) use ($user, $connection) {
                        $query->where('sender_id', $user->id)
                              ->where('receiver_id', $connection->id);
                    })->orWhere(function($query) use ($user, $connection) {
                        $query->where('sender_id', $connection->id)
                              ->where('receiver_id', $user->id);
                    })->orderBy('created_at', 'desc')->first();
                    
                    $connection->last_message = $lastMessage;
                    $connection->unread_count = Message::where('sender_id', $connection->id)
                        ->where('receiver_id', $user->id)
                        ->where('is_read', false)
                        ->count();
                });
            }
        } catch (\Exception $e) {
            // Table might not exist
        }
        
        $selectedUser = null;
        if ($selectedUserId && $connections->contains('id', $selectedUserId)) {
            $selectedUser = $connections->firstWhere('id', $selectedUserId);
        }
        
        return view('pages.messages', [
            'user' => $user,
            'connections' => $connections,
            'selectedUser' => $selectedUser,
        ]);
    }

    public function getChat(User $user)
    {
        $currentUser = Auth::user();
        $otherUser = $user;
        
        // Verify they have accepted interest
        $hasConnection = DB::table('user_interests')
            ->where(function($query) use ($currentUser, $otherUser) {
                $query->where('sender_id', $currentUser->id)
                      ->where('receiver_id', $otherUser->id)
                      ->where('status', 'accepted');
            })->orWhere(function($query) use ($currentUser, $otherUser) {
                $query->where('sender_id', $otherUser->id)
                      ->where('receiver_id', $currentUser->id)
                      ->where('status', 'accepted');
            })->exists();
        
        if (!$hasConnection) {
            return response()->json(['error' => 'No connection found'], 403);
        }
        
        // Get messages
        $messages = Message::where(function($query) use ($currentUser, $otherUser) {
            $query->where('sender_id', $currentUser->id)
                  ->where('receiver_id', $otherUser->id);
        })->orWhere(function($query) use ($currentUser, $otherUser) {
            $query->where('sender_id', $otherUser->id)
                  ->where('receiver_id', $currentUser->id);
        })->with(['sender', 'receiver'])
          ->orderBy('created_at', 'asc')
          ->get();
        
        // Mark messages as read
        Message::where('sender_id', $otherUser->id)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        return response()->json([
            'messages' => $messages,
            'otherUser' => [
                'id' => $otherUser->id,
                'full_name' => $otherUser->full_name,
                'profile_image' => $otherUser->profile_image ? asset('storage/' . $otherUser->profile_image) : null,
                'age' => $otherUser->dob ? \Carbon\Carbon::parse($otherUser->dob)->age : null,
                'occupation' => $otherUser->occupation,
                'city' => $otherUser->city,
                'height' => $otherUser->height,
                'highest_education' => $otherUser->highest_education,
                'mother_tongue' => $otherUser->mother_tongue,
                'verification_status' => $otherUser->verification_status,
            ],
        ]);
    }

    public function sendMessage(Request $request, User $user)
    {
        $currentUser = Auth::user();
        $otherUser = $user;
        
        // Verify they have accepted interest
        $hasConnection = DB::table('user_interests')
            ->where(function($query) use ($currentUser, $otherUser) {
                $query->where('sender_id', $currentUser->id)
                      ->where('receiver_id', $otherUser->id)
                      ->where('status', 'accepted');
            })->orWhere(function($query) use ($currentUser, $otherUser) {
                $query->where('sender_id', $otherUser->id)
                      ->where('receiver_id', $currentUser->id)
                      ->where('status', 'accepted');
            })->exists();
        
        if (!$hasConnection) {
            return response()->json(['error' => 'No connection found'], 403);
        }
        
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);
        
        $message = Message::create([
            'sender_id' => $currentUser->id,
            'receiver_id' => $otherUser->id,
            'message' => $request->message,
            'is_read' => false,
        ]);
        
        $message->load(['sender', 'receiver']);
        
        // Create notification for the receiver (only if they haven't read messages recently)
        $recentUnreadCount = Message::where('sender_id', $currentUser->id)
            ->where('receiver_id', $otherUser->id)
            ->where('is_read', false)
            ->count();
        
        // Only create notification if this is the first unread message (to avoid spam)
        if ($recentUnreadCount === 1) {
            $this->createNotification(
                $otherUser,
                'message',
                $currentUser->full_name . ' sent you a message',
                $currentUser,
                'chat',
                'green-500',
                ['message_id' => $message->id]
            );
        }
        
        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function getNewMessages(Request $request, User $user)
    {
        $currentUser = Auth::user();
        $otherUser = $user;
        $lastMessageId = $request->get('last_message_id');
        
        // If no lastMessageId provided, return empty (initial load should use getChat)
        if (!$lastMessageId) {
            return response()->json([
                'newMessages' => [],
            ]);
        }
        
        $query = Message::where(function($query) use ($currentUser, $otherUser) {
            $query->where('sender_id', $currentUser->id)
                  ->where('receiver_id', $otherUser->id);
        })->orWhere(function($query) use ($currentUser, $otherUser) {
            $query->where('sender_id', $otherUser->id)
                  ->where('receiver_id', $currentUser->id);
        });
        
        // Only get messages with ID greater than lastMessageId
        $query->where('id', '>', $lastMessageId);
        
        $messages = $query->with(['sender', 'receiver'])
                         ->orderBy('created_at', 'asc')
                         ->get();
        
        // Mark new messages as read (only for messages received by current user)
        if ($messages->isNotEmpty()) {
            Message::where('sender_id', $otherUser->id)
                ->where('receiver_id', $currentUser->id)
                ->where('is_read', false)
                ->whereIn('id', $messages->pluck('id'))
                ->update(['is_read' => true]);
        }
        
        return response()->json([
            'newMessages' => $messages,
        ]);
    }
}
