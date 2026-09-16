<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $totalVisible = Announcement::visibleTo($user)->count();
        $unreadCount = Announcement::visibleTo($user)
            ->whereDoesntHave('reads', fn($q) => $q->where('user_id', $user->id))
            ->count();
        $readCount = max(0, $totalVisible - $unreadCount);

        $unreadItems = Announcement::visibleTo($user)
            ->whereDoesntHave('reads', fn($q) => $q->where('user_id', $user->id))
            ->latest()
            ->take(4)
            ->get();

        $recentAnnouncements = Announcement::visibleTo($user)
            ->with(['creator', 'reads'])
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($a) use ($user) {
                $a->is_read = $a->reads->contains('id', $user->id);
                return $a;
            });

        $currentPage = 'Dashboard';

        return view('user.dashboard', compact(
            'totalVisible',
            'unreadCount',
            'readCount',
            'unreadItems',
            'recentAnnouncements',
            'currentPage'
        ));
    }
}

