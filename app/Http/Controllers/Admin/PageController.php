<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function dashboard()
    {
        $totalCount = Announcement::count();
        $publicCount = Announcement::where('visibility', 'public')->count();
        $exclusiveCount = Announcement::where('visibility', 'exclusive')->count();
        $usersCount = User::where('role', 'user')->count();
        $totalReadsCount = DB::table('announcement_reads')->count();

        $recentAnnouncements = Announcement::with(['creator', 'recipients', 'reads'])
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::where('role', 'user')
            ->latest()
            ->take(5)
            ->get();

        $currentPage = 'Dashboard';

        return view('admin.dashboard', compact(
            'totalCount',
            'publicCount',
            'exclusiveCount',
            'usersCount',
            'totalReadsCount',
            'recentAnnouncements',
            'recentUsers',
            'currentPage'
        ));
    }
}

