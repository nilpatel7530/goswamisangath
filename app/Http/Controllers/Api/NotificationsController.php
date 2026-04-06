<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 20);

        $query = Notification::where('user_id', $user->id)
            ->with('relatedUser')
            ->orderBy('created_at', 'desc');

        if ($filter !== 'all') {
            $typeMap = [
                'matches' => 'match',
                'interests' => ['interest', 'interest_accepted'],
                'messages' => 'message',
                'views' => 'profile_view',
            ];

            if (isset($typeMap[$filter])) {
                if (is_array($typeMap[$filter])) {
                    $query->whereIn('type', $typeMap[$filter]);
                } else {
                    $query->where('type', $typeMap[$filter]);
                }
            }
        }

        $notifications = $query->paginate($perPage);

        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        $formatted = $notifications->map(function($notification) {
            return [
                'id' => $notification->id,
                'type' => $notification->type,
                'message' => $notification->message,
                'icon' => $notification->icon,
                'icon_color' => $notification->icon_color,
                'is_read' => $notification->is_read,
                'created_at' => $notification->created_at,
                'related_user' => $notification->relatedUser ? [
                    'id' => $notification->relatedUser->id,
                    'full_name' => $notification->relatedUser->full_name,
                    'profile_image' => $notification->relatedUser->profile_image ? asset('storage/' . $notification->relatedUser->profile_image) : null,
                ] : null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'notifications' => $formatted,
                'unread_count' => $unreadCount,
                'current_page' => $notifications->currentPage(),
                'total' => $notifications->total(),
                'per_page' => $notifications->perPage(),
                'last_page' => $notifications->lastPage(),
            ],
        ]);
    }

    public function unreadCount()
    {
        $user = Auth::user();

        $unreadNotificationCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        $unreadMessageCount = DB::table('messages')
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'notifications' => $unreadNotificationCount,
                'messages' => $unreadMessageCount,
            ],
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $notification->update(['is_read' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Notification marked as read',
        ]);
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'All notifications marked as read',
        ]);
    }

    public function updateSettings(Request $request)
    {
        // This would typically update user notification preferences
        // For now, return success
        return response()->json([
            'status' => 'success',
            'message' => 'Notification settings updated',
        ]);
    }
}

