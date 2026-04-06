<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationsController extends Controller
{
    /**
     * Display the notifications page
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all');
        
        $query = Notification::where('user_id', $user->id)
            ->with('relatedUser')
            ->orderBy('created_at', 'desc');
        
        // Apply filter
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
        
        $notifications = $query->paginate(20);
        
        // Get unread count
        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
        
        return view('pages.notifications', [
            'notifications' => $notifications,
            'filter' => $filter,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Get notifications via AJAX (for real-time updates)
     */
    public function getNotifications(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all');
        $lastNotificationId = $request->get('last_notification_id', 0);
        
        $query = Notification::where('user_id', $user->id)
            ->with('relatedUser')
            ->where('id', '>', $lastNotificationId)
            ->orderBy('created_at', 'desc');
        
        // Apply filter
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
        
        $notifications = $query->get();
        
        // Get unread count
        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
        
        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        
        $notification->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Get unread count (for sidebar)
     */
    public function getUnreadCount()
    {
        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();
        
        // Get unread message count separately
        $unreadMessageCount = DB::table('messages')
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();
        
        return response()->json([
            'notifications' => $unreadCount,
            'messages' => $unreadMessageCount,
        ]);
    }

    /**
     * Delete a notification
     */
    public function delete($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        
        $notification->delete();
        
        return response()->json(['success' => true]);
    }
}
