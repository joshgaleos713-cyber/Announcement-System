<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = User::withCount('readAnnouncements')
            ->orderBy('role')
            ->orderByDesc('created_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->get();
        $totalUsers = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $memberCount = User::where('role', 'user')->count();

        $currentPage = 'Users';

        return view('admin.users', compact(
            'users', 'search', 'totalUsers', 'adminCount', 'memberCount', 'currentPage'
        ));
    }

    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Strict protection: No administrator can ever be deleted
        if ($user->isAdmin() || $user->role === 'admin') {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'Administrator accounts are protected and cannot be deleted.'], 403);
            }
            return back()->with('error', 'Administrator accounts are protected and cannot be deleted.');
        }

        $userName = $user->name;
        $user->delete();

        $remainingTotal = User::count();
        $remainingMembers = User::where('role', 'user')->count();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'id' => (int)$id,
                'totalUsers' => $remainingTotal,
                'memberCount' => $remainingMembers,
                'message' => "User '{$userName}' has been deleted."
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$userName}' has been successfully deleted.");
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id',
        ]);

        // Strict protection: Exclude any user with admin role
        $adminIds = User::where('role', 'admin')->pluck('id')->toArray();
        $targetIds = array_values(array_diff($validated['ids'], $adminIds));

        if (empty($targetIds)) {
            return response()->json(['error' => 'No eligible member accounts selected. Administrator accounts are protected and cannot be deleted.'], 422);
        }

        $count = User::whereIn('id', $targetIds)->count();
        User::whereIn('id', $targetIds)->delete();


        $remainingTotal = User::count();
        $remainingMembers = User::where('role', 'user')->count();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'deleted_count' => $count,
                'deleted_ids' => $targetIds,
                'totalUsers' => $remainingTotal,
                'memberCount' => $remainingMembers,
                'message' => "{$count} user account" . ($count !== 1 ? 's' : '') . " deleted."
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', "{$count} user accounts deleted.");
    }
}

