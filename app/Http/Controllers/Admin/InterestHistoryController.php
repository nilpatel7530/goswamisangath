<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InterestHistoryController extends Controller
{
    /**
     * Display a listing of the interest history.
     */
    public function index(Request $request)
    {
        $query = DB::table('user_interests')
            ->select('user_interests.*', 'senders.full_name as sender_name', 'receivers.full_name as receiver_name')
            ->join('users as senders', 'user_interests.sender_id', '=', 'senders.id')
            ->join('users as receivers', 'user_interests.receiver_id', '=', 'receivers.id');

        // Filter by sender
        if ($request->filled('sender')) {
            $query->where('senders.full_name', 'like', '%' . $request->sender . '%');
        }

        // Filter by receiver
        if ($request->filled('receiver')) {
            $query->where('receivers.full_name', 'like', '%' . $request->receiver . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('user_interests.status', $request->status);
        }

        $interests = $query->orderBy('user_interests.created_at', 'desc')
            ->paginate(20)
            ->appends($request->all());

        return view('admin.interests.index', compact('interests'));
    }

    /**
     * Show interest history for a specific user.
     */
    public function userHistory(User $user)
    {
        $sentInterests = DB::table('user_interests')
            ->where('sender_id', $user->id)
            ->join('users', 'user_interests.receiver_id', '=', 'users.id')
            ->select('user_interests.*', 'users.full_name as receiver_name')
            ->orderBy('created_at', 'desc')
            ->get();

        $receivedInterests = DB::table('user_interests')
            ->where('receiver_id', $user->id)
            ->join('users', 'user_interests.sender_id', '=', 'users.id')
            ->select('user_interests.*', 'users.full_name as sender_name')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.interests.user_history', compact('user', 'sentInterests', 'receivedInterests'));
    }
}
