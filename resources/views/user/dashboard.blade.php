@extends('layouts.user')
@section('page-title', 'Dashboard')

@section('alpine-data')
    unreadList: {{ json_encode($unreadItems->map(fn($i) => [
        'id' => $i->id,
        'title' => $i->title,
        'description' => $i->description,
        'type' => $i->type,
        'icon' => $i->icon,
        'visibility' => $i->visibility,
        'time_ago' => $i->created_at->diffForHumans()
    ])) }},
    markItemRead(id) {
        fetch('/user/notifications/' + id + '/read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            this.unreadList = this.unreadList.filter(item => item.id !== id);
            this.unreadCount = data.unreadCount;
            this.triggerToast('Announcement marked as read');
        });
    }
@endsection

@section('content')
    <!-- Welcome Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-zinc-900 tracking-tight">
                Hello, {{ explode(' ', Auth::user()->name)[0] }} 👋
            </h1>
            <p class="text-xs font-semibold text-zinc-500 mt-1">Here is your dispatch overview and latest broadcast announcements</p>
        </div>
        <a href="{{ route('user.notifications.index') }}"
           class="px-5 py-2.5 neu-btn-dark rounded-2xl text-xs font-bold flex items-center gap-2 cursor-pointer self-start sm:self-auto">
            <i data-lucide="bell" class="w-4 h-4"></i>
            <span>View All Announcements</span>
        </a>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="neu-card rounded-3xl p-5">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-2xl bg-zinc-900 text-white flex items-center justify-center">
                    <i data-lucide="radio" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-xl font-black text-zinc-900">{{ $totalVisible }}</span>
                    <p class="text-[11px] font-bold text-zinc-500 uppercase tracking-wider">Total Dispatches</p>
                </div>
            </div>
        </div>

        <div class="neu-card rounded-3xl p-5">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-2xl neu-sm text-zinc-800 flex items-center justify-center">
                    <i data-lucide="bell-ring" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-xl font-black text-zinc-900" x-text="unreadCount">{{ $unreadCount }}</span>
                    <p class="text-[11px] font-bold text-zinc-500 uppercase tracking-wider">Pending Unread</p>
                </div>
            </div>
        </div>

        <div class="neu-card rounded-3xl p-5">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-2xl neu-sm text-zinc-800 flex items-center justify-center">
                    <i data-lucide="check-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-xl font-black text-zinc-900">{{ $readCount }}</span>
                    <p class="text-[11px] font-bold text-zinc-500 uppercase tracking-wider">Acknowledged</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Unread Priority Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <i data-lucide="zap" class="w-4 h-4 text-zinc-900"></i>
                <h3 class="text-sm font-bold text-zinc-900 uppercase tracking-wider">Requires Attention</h3>
            </div>
            <a href="{{ route('user.notifications.index', ['filter' => 'unread']) }}" class="text-xs font-bold text-zinc-600 hover:text-zinc-900">
                Filter Unread →
            </a>
        </div>

        <!-- Dynamic Unread Cards Grid -->
        <template x-if="unreadList.length > 0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <template x-for="item in unreadList" :key="item.id">
                    <div class="neu-card rounded-3xl p-5 border-l-4 border-zinc-900 flex flex-col justify-between"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95">
                        <div>
                            <div class="flex items-start gap-3.5 mb-3">
                                <div class="w-9 h-9 rounded-2xl bg-zinc-900 text-white flex items-center justify-center shrink-0 text-sm">
                                    <i data-lucide="bell" class="w-4 h-4"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap mb-1">
                                        <span class="w-2 h-2 rounded-full bg-zinc-900 animate-pulse"></span>
                                        <h4 class="text-xs font-bold text-zinc-900 truncate" x-text="item.title"></h4>
                                        <template x-if="item.visibility === 'exclusive'">
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-zinc-900 text-white">🔒 For You</span>
                                        </template>
                                    </div>
                                    <p class="text-xs text-zinc-500 line-clamp-2 leading-relaxed" x-text="item.description"></p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 mt-2 border-t border-zinc-200/60 flex items-center justify-between">
                            <span class="text-[10px] font-semibold text-zinc-400" x-text="item.time_ago"></span>
                            <button @click="markItemRead(item.id)"
                                    class="px-3 py-1.5 neu-btn-light rounded-xl text-xs font-bold text-zinc-800 hover:text-zinc-900 flex items-center gap-1.5 cursor-pointer">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                <span>Mark as read</span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </template>

        <!-- Empty Unread State -->
        <template x-if="unreadList.length === 0">
            <div class="neu-flat rounded-3xl p-10 text-center">
                <div class="w-12 h-12 rounded-2xl neu-sm flex items-center justify-center mx-auto mb-3 text-zinc-700">
                    <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                </div>
                <h4 class="text-sm font-bold text-zinc-800">You're All Caught Up</h4>
                <p class="text-xs text-zinc-400 mt-1">There are no unread announcements waiting for your acknowledgement.</p>
            </div>
        </template>
    </div>

    <!-- Recent Announcements Feed -->
    <div class="neu-flat rounded-3xl p-6 sm:p-7">
        <div class="flex items-center justify-between mb-5 pb-4 border-b border-zinc-200/80">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-zinc-900 text-white flex items-center justify-center">
                    <i data-lucide="history" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-zinc-900">Recent Stream</h3>
                    <p class="text-[10px] text-zinc-500">History of dispatches published for your profile</p>
                </div>
            </div>
            <a href="{{ route('user.notifications.index') }}" class="text-xs font-bold text-zinc-700 hover:text-zinc-900">View All →</a>
        </div>

        <div class="space-y-3">
            @forelse ($recentAnnouncements as $item)
                <div class="p-3.5 rounded-2xl neu-sm flex items-center justify-between gap-4 {{ $item->is_read ? 'opacity-70' : '' }}">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-8 h-8 rounded-xl {{ $item->is_read ? 'neu-inset text-zinc-500' : 'bg-zinc-900 text-white' }} flex items-center justify-center shrink-0 text-xs">
                            <i data-lucide="{{ $item->icon }}" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <h4 class="text-xs font-bold text-zinc-900 truncate">{{ $item->title }}</h4>
                                @if ($item->visibility === 'exclusive')
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-zinc-900 text-white">🔒 For You</span>
                                @endif
                            </div>
                            <p class="text-[11px] text-zinc-500 truncate mt-0.5">{{ $item->description }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <span class="text-[10px] font-semibold text-zinc-400 hidden sm:inline">{{ $item->created_at->diffForHumans() }}</span>
                        @if ($item->is_read)
                            <span class="px-2 py-1 rounded-xl neu-inset text-[10px] font-bold text-zinc-500 flex items-center gap-1">
                                <i data-lucide="check-check" class="w-3 h-3"></i> Read
                            </span>
                        @else
                            <button @click="markItemRead({{ $item->id }})"
                                    class="px-2.5 py-1 rounded-xl neu-btn-light text-[10px] font-bold text-zinc-800 hover:text-zinc-900 flex items-center gap-1 cursor-pointer">
                                <i data-lucide="check" class="w-3 h-3"></i> Mark read
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-xs text-zinc-400 text-center py-6">No recent dispatches found.</p>
            @endforelse
        </div>
    </div>
@endsection
