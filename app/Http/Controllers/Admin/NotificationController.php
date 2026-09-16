<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $search = $request->query('search');

        $query = Announcement::with(['recipients', 'reads', 'creator'])
            ->orderByDesc('created_at');

        if ($filter === 'public') {
            $query->where('visibility', 'public');
        } elseif ($filter === 'exclusive') {
            $query->where('visibility', 'exclusive');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $announcements = $query->get();
        $totalCount = Announcement::count();
        $publicCount = Announcement::where('visibility', 'public')->count();
        $exclusiveCount = Announcement::where('visibility', 'exclusive')->count();
        $users = User::where('role', 'user')->orderBy('name')->get();

        $currentPage = 'Announcements';
        return view('admin.notifications', compact(
            'announcements', 'filter', 'search', 'totalCount', 'publicCount', 'exclusiveCount', 'users', 'currentPage'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:info,error,warning,success',
            'visibility' => 'required|in:public,exclusive',
            'user_ids' => 'required_if:visibility,exclusive|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $iconMap = [
            'info' => 'bell',
            'error' => 'alert-circle',
            'warning' => 'alert-triangle',
            'success' => 'check-circle-2',
        ];

        $announcement = Announcement::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'visibility' => $validated['visibility'],
            'icon' => $iconMap[$validated['type']] ?? 'bell',
            'created_by' => $request->user()->id,
        ]);

        // Attach recipients for exclusive announcements
        if ($validated['visibility'] === 'exclusive' && !empty($validated['user_ids'])) {
            $announcement->recipients()->attach($validated['user_ids']);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Announcement posted successfully!']);
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Announcement posted successfully!');
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:info,error,warning,success',
            'visibility' => 'required|in:public,exclusive',
            'user_ids' => 'required_if:visibility,exclusive|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $iconMap = [
            'info' => 'bell',
            'error' => 'alert-circle',
            'warning' => 'alert-triangle',
            'success' => 'check-circle-2',
        ];

        $announcement->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'visibility' => $validated['visibility'],
            'icon' => $iconMap[$validated['type']] ?? 'bell',
        ]);

        if ($validated['visibility'] === 'exclusive') {
            $announcement->recipients()->sync($validated['user_ids'] ?? []);
        } else {
            $announcement->recipients()->detach();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Announcement updated successfully!']);
        }

        return redirect()->route('admin.notifications.index', ['filter' => $request->query('filter', 'all')])
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        Announcement::findOrFail($id)->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Announcement deleted.']);
        }

        return redirect()->route('admin.notifications.index', ['filter' => $request->query('filter', 'all')])
            ->with('success', 'Announcement deleted.');
    }

    public function readers(Request $request, $id)
    {
        $announcement = Announcement::with('reads')->findOrFail($id);
        
        $readers = $announcement->reads()->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'read_at' => optional($user->pivot->read_at)->diffForHumans() ?? 'Recently',
            ];
        });

        return response()->json([
            'id' => $announcement->id,
            'title' => $announcement->title,
            'total_readers' => $readers->count(),
            'readers' => $readers,
        ]);
    }
}