<?php

namespace App\Traits;

use App\Models\Notification;
use App\Models\User;

trait CreatesNotifications
{
    /**
     * Create a notification for a user
     */
    protected function createNotification(User $user, string $type, string $message, ?User $relatedUser = null, ?string $icon = null, ?string $iconColor = null, ?array $metadata = null)
    {
        try {
            // Determine icon and color based on type if not provided
            if (!$icon) {
                $iconMap = [
                    'interest' => 'favorite',
                    'interest_accepted' => 'favorite',
                    'message' => 'chat',
                    'match' => 'person_add',
                    'profile_view' => 'visibility',
                ];
                $icon = $iconMap[$type] ?? 'notifications';
            }
            
            if (!$iconColor) {
                $colorMap = [
                    'interest' => 'primary',
                    'interest_accepted' => 'primary',
                    'message' => 'green-500',
                    'match' => 'blue-500',
                    'profile_view' => 'gray-500',
                ];
                $iconColor = $colorMap[$type] ?? 'primary';
            }
            
            Notification::create([
                'user_id' => $user->id,
                'related_user_id' => $relatedUser?->id,
                'type' => $type,
                'message' => $message,
                'icon' => $icon,
                'icon_color' => $iconColor,
                'is_read' => false,
                'metadata' => $metadata,
            ]);
        } catch (\Exception $e) {
            // Silently fail if notifications table doesn't exist yet
            \Log::warning('Failed to create notification: ' . $e->getMessage());
        }
    }
}

