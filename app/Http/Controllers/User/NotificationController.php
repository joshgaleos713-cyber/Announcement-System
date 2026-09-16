<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $filter = $request->query('filter', 'all');
        $search = $request->query('search');

        $query = Announcement::visibleTo($user)
            ->with(['creator', 'reads'])
            ->orderByDesc('created_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $allVisible = Announcement::visibleTo($user)->count();
        $unreadCount = Announcement::visibleTo($user)
            ->whereDoesntHave('reads', fn($q) => $q->where('user_id', $user->id))
            ->count();
        $readCount = max(0, $allVisible - $unreadCount);

        if ($filter === 'unread') {
            $query->whereDoesntHave('reads', fn($q) => $q->where('user_id', $user->id));
        } elseif ($filter === 'read') {
            $query->whereHas('reads', fn($q) => $q->where('user_id', $user->id));
        }

        $announcements = $query->get()->map(function ($a) use ($user) {
            $a->is_read = $a->reads->contains('id', $user->id);
            return $a;
        });

        $currentPage = 'Announcements';
        return view('user.notifications', compact(
            'announcements', 'filter', 'search', 'unreadCount', 'readCount', 'allVisible', 'currentPage'
        ));
    }

    public function markRead(Request $request)
    {
        $user = $request->user();

        $unreadIds = Announcement::visibleTo($user)
            ->whereDoesntHave('reads', fn($q) => $q->where('user_id', $user->id))
            ->pluck('id');

        foreach ($unreadIds as $id) {
            DB::table('announcement_reads')->insertOrIgnore([
                'announcement_id' => $id,
                'user_id' => $user->id,
                'read_at' => now(),
            ]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'unreadCount' => 0,
                'message' => 'All announcements marked as read',
            ]);
        }

        return redirect()->route('user.notifications.index', ['filter' => $request->query('filter', 'all')])
            ->with('success', 'All announcements marked as read');
    }

    public function markSingleRead(Request $request, $id)
    {
        $user = $request->user();
        $announcement = Announcement::findOrFail($id);

        if ($announcement->isVisibleTo($user)) {
            DB::table('announcement_reads')->insertOrIgnore([
                'announcement_id' => $id,
                'user_id' => $user->id,
                'read_at' => now(),
            ]);
        }

        $remainingUnread = Announcement::visibleTo($user)
            ->whereDoesntHave('reads', fn($q) => $q->where('user_id', $user->id))
            ->count();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'id' => $id,
                'unreadCount' => $remainingUnread,
                'message' => 'Announcement marked as read',
            ]);
        }

        return redirect()->route('user.notifications.index', ['filter' => $request->query('filter', 'all')]);
    }

    public function unreadFeed(Request $request)
    {
        $user = $request->user();

        $unreadCount = Announcement::visibleTo($user)
            ->whereDoesntHave('reads', fn($q) => $q->where('user_id', $user->id))
            ->count();

        $recentUnread = Announcement::visibleTo($user)
            ->whereDoesntHave('reads', fn($q) => $q->where('user_id', $user->id))
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'type' => $item->type,
                    'icon' => $item->icon,
                    'visibility' => $item->visibility,
                    'time_ago' => $item->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'unreadCount' => $unreadCount,
            'recent' => $recentUnread,
        ]);
    }
}