@extends('layouts.user')
@section('page-title', 'Announcements')

@section('alpine-data')
    activeFilter: '{{ $filter }}',
    readItems: {},
    modalDetail: { open: false, item: null },
    markSingle(id) {
        fetch('/user/notifications/' + id + '/read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            this.readItems[id] = true;
            this.unreadCount = data.unreadCount;
            this.triggerToast('Announcement marked as read');
        });
    },
    markAll() {
        fetch('{{ route('user.notifications.markRead') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            document.querySelectorAll('[data-announcement-id]').forEach(el => {
                let id = el.getAttribute('data-announcement-id');
                this.readItems[id] = true;
            });
            this.unreadCount = 0;
            this.triggerToast('All announcements marked as read');
        });
    },
    showDetail(item) {
        this.modalDetail.item = item;
        this.modalDetail.open = true;
        if (!item.is_read && !this.readItems[item.id]) {
            this.markSingle(item.id);
        }
    }
@endsection

@section('content')
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-zinc-900 tracking-tight">Announcements</h1>
            <p class="text-xs font-semibold text-zinc-500 mt-1">
                You have <span class="font-extrabold text-zinc-900" x-text="unreadCount">{{ $unreadCount }}</span> pending unread announcement{{ $unreadCount !== 1 ? 's' : '' }}
            </p>
        </div>

        <button x-show="unreadCount > 0"
                @click="markAll()"
                class="px-4 py-2.5 neu-btn-light rounded-2xl text-xs font-bold text-zinc-800 hover:text-zinc-900 flex items-center gap-2 cursor-pointer self-start sm:self-auto transition-all">
            <i data-lucide="check-check" class="w-4 h-4"></i>
            <span>Mark all read</span>
        </button>
    </div>

    <!-- Search & Filter Controls -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-7">
        <!-- Filter Tabs -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('user.notifications.index', ['filter' => 'all', 'search' => request('search')]) }}"
               class="px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ $filter === 'all' ? 'neu-pill-active' : 'neu-pill-inactive' }}">
                All ({{ $allVisible }})
            </a>
            <a href="{{ route('user.notifications.index', ['filter' => 'unread', 'search' => request('search')]) }}"
               class="px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ $filter === 'unread' ? 'neu-pill-active' : 'neu-pill-inactive' }}">
                Unread (<span x-text="unreadCount">{{ $unreadCount }}</span>)
            </a>
            <a href="{{ route('user.notifications.index', ['filter' => 'read', 'search' => request('search')]) }}"
               class="px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ $filter === 'read' ? 'neu-pill-active' : 'neu-pill-inactive' }}">
                Read ({{ $readCount }})
            </a>
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('user.notifications.index') }}" class="w-full md:w-auto relative md:min-w-[260px]">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <i data-lucide="search" class="absolute left-3.5 top-2.5 text-zinc-400 w-4 h-4"></i>
            <input type="text" name="search" value="{{ $search ?? '' }}"
                   placeholder="Search dispatches..."
                   class="w-full pl-10 pr-9 py-2 neu-inset rounded-2xl text-xs text-zinc-800 placeholder-zinc-400 focus:outline-none font-medium">
            @if (!empty($search))
                <a href="{{ route('user.notifications.index', ['filter' => $filter]) }}"
                   class="absolute right-3 top-2.5 text-zinc-400 hover:text-zinc-700">
                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Announcement Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @forelse ($announcements as $item)
            @php
                $itemArray = [
                    'id' => $item->id,
                    'title' => $item->title,
                    'description' => $item->description,
                    'type' => $item->type,
                    'icon' => $item->icon,
                    'visibility' => $item->visibility,
                    'is_read' => $item->is_read,
                    'creator' => $item->creator->name,
                    'time_ago' => $item->created_at->diffForHumans(),
                    'date_formatted' => $item->created_at->format('M d, Y h:i A')
                ];
            @endphp
            <div data-announcement-id="{{ $item->id }}"
                 :class="(readItems[{{ $item->id }}] || {{ $item->is_read ? 'true' : 'false' }}) ? 'opacity-70 border-transparent' : 'border-l-4 border-zinc-900'"
                 class="neu-card rounded-3xl p-5 flex flex-col justify-between transition-all duration-200">
                
                <div>
                    <!-- Top Category & Type Badges -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @if ($item->visibility === 'exclusive')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-zinc-900 text-white">
                                    🔒 For You
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-zinc-200 text-zinc-800">
                                    🌐 Public
                                </span>
                            @endif

                            @if ($item->type === 'error')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 border border-rose-200">
                                    ⚠️ Alert
                                </span>
                            @elseif ($item->type === 'warning')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200">
                                    ⚡ Warning
                                </span>
                            @endif
                        </div>

                        <!-- Unread Live Pulse Dot -->
                        <span x-show="!readItems[{{ $item->id }}] && !{{ $item->is_read ? 'true' : 'false' }}"
                              class="w-2 h-2 rounded-full bg-zinc-900 animate-pulse"></span>
                    </div>

                    <!-- Title & Preview -->
                    <div class="flex items-start gap-3.5 mb-3 cursor-pointer" @click="showDetail({{ json_encode($itemArray) }})">
                        <div class="w-9 h-9 rounded-2xl bg-zinc-900 text-white flex items-center justify-center shrink-0 text-sm">
                            <i data-lucide="{{ $item->icon }}" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-zinc-900 hover:text-zinc-700 transition-colors leading-snug">
                                {{ $item->title }}
                            </h3>
                            <p class="text-xs text-zinc-500 leading-relaxed mt-1 line-clamp-3">
                                {{ $item->description }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Footer / Action Row -->
                <div class="pt-3 mt-3 border-t border-zinc-200/60 flex items-center justify-between">
                    <span class="text-[10px] font-semibold text-zinc-400 flex items-center gap-1.5">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                        {{ $item->created_at->diffForHumans() }}
                    </span>

                    <div class="flex items-center gap-2">
                        <!-- Read Status Action -->
                        <template x-if="readItems[{{ $item->id }}] || {{ $item->is_read ? 'true' : 'false' }}">
                            <span class="px-2.5 py-1 rounded-xl neu-inset text-[10px] font-bold text-zinc-500 flex items-center gap-1">
                                <i data-lucide="check-check" class="w-3 h-3"></i> Read
                            </span>
                        </template>

                        <template x-if="!readItems[{{ $item->id }}] && !{{ $item->is_read ? 'true' : 'false' }}">
                            <button @click="markSingle({{ $item->id }})"
                                    class="px-3 py-1.5 neu-btn-light rounded-xl text-xs font-bold text-zinc-800 hover:text-zinc-900 flex items-center gap-1 cursor-pointer">
                                <i data-lucide="check" class="w-3 h-3"></i> Mark read
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full neu-flat rounded-3xl p-16 text-center">
                <div class="w-14 h-14 rounded-2xl neu-sm flex items-center justify-center mx-auto mb-3 text-zinc-400">
                    <i data-lucide="bell-off" class="w-7 h-7"></i>
                </div>
                <h3 class="text-sm font-bold text-zinc-800">No announcements in this view</h3>
                <p class="text-xs text-zinc-400 mt-1">
                    @if (!empty($search))
                        No dispatches match your search query "{{ $search }}".
                    @else
                        You have acknowledged all dispatches in this filter.
                    @endif
                </p>
            </div>
        @endforelse
    </div>
@endsection

@section('modals')
    <!-- DETAIL MODAL -->
    <div x-show="modalDetail.open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-zinc-900/50 backdrop-blur-xs flex items-center justify-center p-4 z-50"
         x-cloak>
        <div class="neu-flat rounded-3xl max-w-lg w-full p-7 shadow-2xl max-h-[85vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             @click.away="modalDetail.open = false">
            
            <template x-if="modalDetail.item">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-zinc-200/80">
                        <div class="flex items-center gap-2">
                            <span x-show="modalDetail.item.visibility === 'exclusive'" class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-zinc-900 text-white">🔒 For You</span>
                            <span x-show="modalDetail.item.visibility === 'public'" class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-zinc-200 text-zinc-800">🌐 Public</span>
                            <span class="text-[10px] font-bold text-zinc-400" x-text="modalDetail.item.date_formatted"></span>
                        </div>
                        <button @click="modalDetail.open = false" class="p-1.5 rounded-xl neu-sm text-zinc-400 hover:text-zinc-800">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <div class="flex items-start gap-3.5 mb-4">
                        <div class="w-10 h-10 rounded-2xl bg-zinc-900 text-white flex items-center justify-center shrink-0">
                            <i data-lucide="radio" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-zinc-900 leading-snug" x-text="modalDetail.item.title"></h2>
                            <p class="text-[11px] font-semibold text-zinc-400 mt-0.5">Dispatched by <span class="text-zinc-700" x-text="modalDetail.item.creator"></span></p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl neu-inset mb-6">
                        <p class="text-xs text-zinc-700 leading-relaxed whitespace-pre-line font-medium" x-text="modalDetail.item.description"></p>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-zinc-200/80">
                        <button @click="modalDetail.open = false" class="px-5 py-2.5 neu-btn-dark rounded-xl text-xs font-bold cursor-pointer">
                            Close Notice
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
@endsection
