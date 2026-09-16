@extends('layouts.admin')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Top Welcome Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-zinc-900 tracking-tight">Dispatch Console</h1>
            <p class="text-xs font-semibold text-zinc-500 mt-1">Operational metrics and system-wide broadcast activity</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.notifications.index') }}"
               class="px-5 py-2.5 neu-btn-dark rounded-2xl text-xs font-bold flex items-center gap-2 cursor-pointer">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>New Announcement</span>
            </a>
        </div>
    </div>

    <!-- Metrics Stat Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Stat 1: Total -->
        <div class="neu-card rounded-3xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-2xl bg-zinc-900 text-white flex items-center justify-center">
                    <i data-lucide="radio" class="w-5 h-5"></i>
                </div>
                <span class="text-2xl font-black text-zinc-900">{{ $totalCount }}</span>
            </div>
            <p class="text-xs font-bold text-zinc-800">Total Dispatches</p>
            <p class="text-[11px] text-zinc-400 mt-0.5">All-time announcements</p>
        </div>

        <!-- Stat 2: Public -->
        <div class="neu-card rounded-3xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-2xl neu-sm text-zinc-800 flex items-center justify-center">
                    <i data-lucide="globe" class="w-5 h-5"></i>
                </div>
                <span class="text-2xl font-black text-zinc-900">{{ $publicCount }}</span>
            </div>
            <p class="text-xs font-bold text-zinc-800">Public Broadcasts</p>
            <p class="text-[11px] text-zinc-400 mt-0.5">Visible to all members</p>
        </div>

        <!-- Stat 3: Exclusive -->
        <div class="neu-card rounded-3xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-2xl neu-sm text-zinc-800 flex items-center justify-center">
                    <i data-lucide="lock" class="w-5 h-5"></i>
                </div>
                <span class="text-2xl font-black text-zinc-900">{{ $exclusiveCount }}</span>
            </div>
            <p class="text-xs font-bold text-zinc-800">Exclusive / Targeted</p>
            <p class="text-[11px] text-zinc-400 mt-0.5">Assigned to select users</p>
        </div>

        <!-- Stat 4: Registered Users -->
        <div class="neu-card rounded-3xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-2xl neu-sm text-zinc-800 flex items-center justify-center">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <span class="text-2xl font-black text-zinc-900">{{ $usersCount }}</span>
            </div>
            <p class="text-xs font-bold text-zinc-800">Recipients Registered</p>
            <p class="text-[11px] text-zinc-400 mt-0.5">{{ $totalReadsCount }} total reads recorded</p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Recent Dispatches List (2 cols) -->
        <div class="lg:col-span-2 neu-flat rounded-3xl p-6 sm:p-7">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-zinc-200/80">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-zinc-900 text-white flex items-center justify-center">
                        <i data-lucide="activity" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900">Recent Broadcasts</h3>
                        <p class="text-[10px] text-zinc-500">Latest announcements dispatched across the system</p>
                    </div>
                </div>
                <a href="{{ route('admin.notifications.index') }}" class="text-xs font-bold text-zinc-700 hover:text-zinc-900 transition-colors">View All →</a>
            </div>

            <div class="space-y-3.5">
                @forelse ($recentAnnouncements as $item)
                    <div class="neu-card rounded-2xl p-4 flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3.5 min-w-0">
                            <div class="w-9 h-9 rounded-xl bg-zinc-900 text-white flex items-center justify-center shrink-0 text-sm">
                                <i data-lucide="{{ $item->icon }}" class="w-4 h-4"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <h4 class="text-xs font-bold text-zinc-900 truncate">{{ $item->title }}</h4>
                                    @if ($item->visibility === 'public')
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-zinc-200 text-zinc-800">Public</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-zinc-900 text-white">Targeted ({{ $item->recipients->count() }})</span>
                                    @endif
                                    @if ($item->type === 'error')
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-rose-100 text-rose-800 border border-rose-200">Alert</span>
                                    @endif
                                </div>
                                <p class="text-xs text-zinc-500 line-clamp-1 leading-relaxed">{{ $item->description }}</p>
                                <div class="flex items-center gap-3 mt-2 text-[10px] font-semibold text-zinc-400">
                                    <span>{{ $item->created_at->diffForHumans() }}</span>
                                    <span>·</span>
                                    <span>By {{ $item->creator->name }}</span>
                                    <span>·</span>
                                    <span class="text-zinc-700 font-bold">{{ $item->reads->count() }} read{{ $item->reads->count() !== 1 ? 's' : '' }}</span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('admin.notifications.index') }}" class="p-2 neu-sm rounded-xl text-zinc-500 hover:text-zinc-900 shrink-0 self-center">
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-12 h-12 rounded-2xl neu-sm flex items-center justify-center mx-auto mb-3 text-zinc-400">
                            <i data-lucide="radio" class="w-6 h-6"></i>
                        </div>
                        <p class="text-xs font-bold text-zinc-600">No broadcasts recorded yet</p>
                        <p class="text-[11px] text-zinc-400 mt-0.5">Click "New Announcement" above to publish your first update.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Sidebar Widget Column (1 col) -->
        <div class="space-y-6">
            
            <!-- Quick Actions -->
            <div class="neu-flat rounded-3xl p-6">
                <h3 class="text-xs font-bold text-zinc-900 uppercase tracking-wider mb-4">Quick Operations</h3>
                <div class="space-y-2.5">
                    <a href="{{ route('admin.notifications.index') }}"
                       class="flex items-center gap-3 p-3.5 rounded-2xl neu-sm hover:neu-card text-zinc-800 transition-all group">
                        <div class="w-8 h-8 rounded-xl bg-zinc-900 text-white flex items-center justify-center group-hover:scale-105 transition-transform">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-zinc-900">Create Broadcast</p>
                            <p class="text-[10px] text-zinc-400">Publish public or targeted notice</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.notifications.index', ['filter' => 'exclusive']) }}"
                       class="flex items-center gap-3 p-3.5 rounded-2xl neu-sm hover:neu-card text-zinc-800 transition-all group">
                        <div class="w-8 h-8 rounded-xl neu-inset flex items-center justify-center text-zinc-900 group-hover:scale-105 transition-transform">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-zinc-900">Targeted Audits</p>
                            <p class="text-[10px] text-zinc-400">View exclusive announcement delivery</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center gap-3 p-3.5 rounded-2xl neu-sm hover:neu-card text-zinc-800 transition-all group">
                        <div class="w-8 h-8 rounded-xl neu-inset flex items-center justify-center text-zinc-900 group-hover:scale-105 transition-transform">
                            <i data-lucide="users" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-zinc-900">User Directory</p>
                            <p class="text-[10px] text-zinc-400">View, audit, and remove accounts</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Active Registered Users Preview -->
            <div class="neu-flat rounded-3xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-bold text-zinc-900 uppercase tracking-wider">Recipients</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-[10px] font-bold text-zinc-500 hover:text-zinc-900">Manage All ({{ $usersCount }}) →</a>
                </div>

                <div class="space-y-2.5">
                    @forelse ($recentUsers as $u)
                        <div class="flex items-center justify-between p-2.5 rounded-xl neu-sm">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-zinc-900 text-white flex items-center justify-center font-bold text-[10px] shrink-0">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-zinc-800 truncate">{{ $u->name }}</p>
                                    <p class="text-[10px] text-zinc-400 truncate">{{ $u->email }}</p>
                                </div>
                            </div>
                            <span class="text-[9px] font-bold text-zinc-400">{{ $u->created_at->diffForHumans(null, true) }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-zinc-400 py-3 text-center">No users registered yet.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
@endsection
