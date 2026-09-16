@extends('layouts.admin')
@section('page-title', 'Announcements')

@section('alpine-data')
    openCreateModal: false,
    openEditModal: false,
    openDeleteModal: false,
    openReadersModal: false,
    deleteActionUrl: '',
    currentEdit: { id: null, title: '', description: '', type: 'info', visibility: 'public', recipients: [] },
    readersData: { title: '', readers: [], loading: false },
    searchQuery: '{{ request('search') }}',
    visibility: 'public',
    selectAll: false,
    editSelectAll: false
@endsection

@section('content')
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-zinc-900 tracking-tight">Announcements</h1>
            <p class="text-xs font-semibold text-zinc-500 mt-1">Publish, edit, and track readership of system dispatches</p>
        </div>
        <button @click="openCreateModal = true; visibility = 'public';"
                class="px-5 py-2.5 neu-btn-dark rounded-2xl text-xs font-bold flex items-center gap-2 cursor-pointer self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>New Announcement</span>
        </button>
    </div>

    <!-- Stats Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="neu-card rounded-3xl p-5">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-2xl bg-zinc-900 text-white flex items-center justify-center">
                    <i data-lucide="radio" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-xl font-black text-zinc-900">{{ $totalCount }}</span>
                    <p class="text-[11px] font-bold text-zinc-500 uppercase tracking-wider">Total Dispatches</p>
                </div>
            </div>
        </div>

        <div class="neu-card rounded-3xl p-5">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-2xl neu-sm text-zinc-800 flex items-center justify-center">
                    <i data-lucide="globe" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-xl font-black text-zinc-900">{{ $publicCount }}</span>
                    <p class="text-[11px] font-bold text-zinc-500 uppercase tracking-wider">Public Broadcasts</p>
                </div>
            </div>
        </div>

        <div class="neu-card rounded-3xl p-5">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-2xl neu-sm text-zinc-800 flex items-center justify-center">
                    <i data-lucide="lock" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-xl font-black text-zinc-900">{{ $exclusiveCount }}</span>
                    <p class="text-[11px] font-bold text-zinc-500 uppercase tracking-wider">Targeted Posts</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-7">
        <!-- Filter Tabs -->
        <div class="flex flex-wrap gap-2">
            @foreach (['all' => 'All Dispatches', 'public' => '🌐 Public', 'exclusive' => '🔒 Targeted'] as $key => $label)
                <a href="{{ route('admin.notifications.index', ['filter' => $key, 'search' => request('search')]) }}"
                   class="px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ $filter === $key ? 'neu-pill-active' : 'neu-pill-inactive' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('admin.notifications.index') }}" class="w-full md:w-auto relative md:min-w-[260px]">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <i data-lucide="search" class="absolute left-3.5 top-2.5 text-zinc-400 w-4 h-4"></i>
            <input type="text" name="search" value="{{ $search ?? '' }}"
                   placeholder="Search dispatches..."
                   class="w-full pl-10 pr-9 py-2 neu-inset rounded-2xl text-xs text-zinc-800 placeholder-zinc-400 focus:outline-none font-medium">
            @if (!empty($search))
                <a href="{{ route('admin.notifications.index', ['filter' => $filter]) }}"
                   class="absolute right-3 top-2.5 text-zinc-400 hover:text-zinc-700">
                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Announcement Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @forelse ($announcements as $item)
            <div class="neu-card rounded-3xl overflow-hidden flex flex-col justify-between group">
                <!-- Top Badge Strip -->
                <div class="px-5 py-3 border-b border-zinc-200/60 flex items-center justify-between bg-zinc-100/40">
                    <div class="flex items-center gap-2">
                        @if ($item->visibility === 'public')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-zinc-200 text-zinc-800">
                                🌐 Public
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-zinc-900 text-white">
                                🔒 {{ $item->recipients->count() }} Targeted
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
                        @elseif ($item->type === 'success')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                ✓ Update
                            </span>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-1">
                        <!-- View Readers -->
                        <button @click="
                                    readersData.title = '{{ addslashes($item->title) }}';
                                    readersData.loading = true;
                                    openReadersModal = true;
                                    fetch('{{ route('admin.notifications.readers', $item->id) }}')
                                        .then(r => r.json())
                                        .then(data => {
                                            readersData.readers = data.readers;
                                            readersData.loading = false;
                                        });
                                "
                                title="View Readers"
                                class="p-1.5 neu-sm rounded-xl text-zinc-500 hover:text-zinc-900 cursor-pointer transition-colors">
                            <i data-lucide="users" class="w-3.5 h-3.5"></i>
                        </button>

                        <!-- Edit Button -->
                        <button @click="
                                    currentEdit = {
                                        id: {{ $item->id }},
                                        title: '{{ addslashes($item->title) }}',
                                        description: '{{ addslashes($item->description) }}',
                                        type: '{{ $item->type }}',
                                        visibility: '{{ $item->visibility }}',
                                        recipients: {{ json_encode($item->recipients->pluck('id')) }}
                                    };
                                    openEditModal = true;
                                "
                                title="Edit"
                                class="p-1.5 neu-sm rounded-xl text-zinc-500 hover:text-zinc-900 cursor-pointer transition-colors">
                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                        </button>

                        <!-- Delete Button (triggers modal) -->
                        <button @click="
                                    deleteActionUrl = '{{ route('admin.notifications.destroy', $item->id) }}?filter={{ $filter }}';
                                    openDeleteModal = true;
                                "
                                title="Delete"
                                class="p-1.5 neu-sm rounded-xl text-zinc-500 hover:text-rose-600 cursor-pointer transition-colors">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                </div>

                <!-- Main Card Body -->
                <div class="p-5 flex-1">
                    <div class="flex items-start gap-3.5 mb-3">
                        <div class="w-9 h-9 rounded-2xl bg-zinc-900 text-white flex items-center justify-center shrink-0 text-sm">
                            <i data-lucide="{{ $item->icon }}" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-zinc-900 leading-snug">{{ $item->title }}</h3>
                            <p class="text-xs text-zinc-500 leading-relaxed mt-1 line-clamp-3">{{ $item->description }}</p>
                        </div>
                    </div>

                    <!-- Targeted preview pill if exclusive -->
                    @if ($item->visibility === 'exclusive' && $item->recipients->count() > 0)
                        <div class="mt-4 pt-3 border-t border-zinc-200/60">
                            <p class="text-[9px] font-extrabold uppercase tracking-wider text-zinc-400 mb-1.5">Targeted Recipients</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach ($item->recipients->take(4) as $recip)
                                    <span class="px-2 py-0.5 rounded-lg neu-sm text-[10px] font-bold text-zinc-700">
                                        {{ $recip->name }}
                                    </span>
                                @endforeach
                                @if ($item->recipients->count() > 4)
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold text-zinc-400">
                                        +{{ $item->recipients->count() - 4 }} more
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Card Footer: Author, Date, Read count -->
                <div class="px-5 py-3 border-t border-zinc-200/60 flex items-center justify-between text-[11px] text-zinc-400 font-semibold bg-zinc-100/20">
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded-lg bg-zinc-800 text-white flex items-center justify-center text-[9px] font-bold">
                            {{ strtoupper(substr($item->creator->name, 0, 1)) }}
                        </div>
                        <span class="text-zinc-600">{{ $item->creator->name }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded-md neu-sm text-[10px] font-bold text-zinc-700">
                            {{ $item->reads->count() }} read{{ $item->reads->count() !== 1 ? 's' : '' }}
                        </span>
                        <span>{{ $item->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full neu-flat rounded-3xl p-16 text-center">
                <div class="w-14 h-14 rounded-2xl neu-sm flex items-center justify-center mx-auto mb-3 text-zinc-400">
                    <i data-lucide="radio" class="w-7 h-7"></i>
                </div>
                <h3 class="text-sm font-bold text-zinc-800">No dispatches found</h3>
                <p class="text-xs text-zinc-400 mt-1">
                    @if (!empty($search))
                        No announcements match your search term "{{ $search }}".
                    @else
                        No announcements have been published in this category yet.
                    @endif
                </p>
                <button @click="openCreateModal = true" class="mt-4 px-4 py-2 neu-btn-dark rounded-xl text-xs font-bold cursor-pointer">
                    + Create Announcement
                </button>
            </div>
        @endforelse
    </div>
@endsection

@section('modals')
    <!-- 1. CREATE ANNOUNCEMENT MODAL -->
    <div x-show="openCreateModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-zinc-900/50 backdrop-blur-xs flex items-center justify-center p-4 z-50"
         x-cloak>
        <div class="neu-flat rounded-3xl max-w-lg w-full p-7 shadow-2xl max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             @click.away="openCreateModal = false">
            
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-zinc-200/80">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-zinc-900 text-white flex items-center justify-center">
                        <i data-lucide="radio" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-zinc-900">New Announcement</h3>
                        <p class="text-[11px] text-zinc-500">Dispatch message to system members</p>
                    </div>
                </div>
                <button @click="openCreateModal = false" class="p-1.5 rounded-xl neu-sm text-zinc-400 hover:text-zinc-800">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form action="{{ route('admin.notifications.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 mb-1.5">Title</label>
                    <input type="text" name="title" required placeholder="e.g. Scheduled System Upgrades"
                           class="w-full px-4 py-2.5 neu-inset rounded-2xl text-xs text-zinc-800 placeholder-zinc-400 focus:outline-none font-semibold">
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 mb-1.5">Description</label>
                    <textarea name="description" rows="3" required placeholder="Write the announcement dispatch contents..."
                              class="w-full px-4 py-2.5 neu-inset rounded-2xl text-xs text-zinc-800 placeholder-zinc-400 focus:outline-none font-medium resize-none"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 mb-1.5">Notice Type</label>
                        <select name="type" class="w-full px-4 py-2.5 neu-inset rounded-2xl text-xs text-zinc-800 font-semibold focus:outline-none">
                            <option value="info">ℹ️ Info Notice</option>
                            <option value="warning">⚡ Warning Notice</option>
                            <option value="error">⚠️ Critical Alert</option>
                            <option value="success">✓ Success Update</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 mb-1.5">Audience</label>
                        <select name="visibility" x-model="visibility" class="w-full px-4 py-2.5 neu-inset rounded-2xl text-xs text-zinc-800 font-semibold focus:outline-none">
                            <option value="public">🌐 Public (All Users)</option>
                            <option value="exclusive">🔒 Targeted (Select Users)</option>
                        </select>
                    </div>
                </div>

                <!-- Recipient Selector (exclusive mode) -->
                <div x-show="visibility === 'exclusive'" x-transition class="neu-card rounded-2xl p-4">
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-zinc-200">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-zinc-700">Choose Recipients</label>
                        <label class="flex items-center gap-1.5 text-xs text-zinc-600 font-semibold cursor-pointer select-none">
                            <input type="checkbox" x-model="selectAll"
                                   @change="document.querySelectorAll('.create-user-cb').forEach(cb => cb.checked = selectAll)"
                                   class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-800 w-3.5 h-3.5">
                            Select All
                        </label>
                    </div>
                    <div class="space-y-1.5 max-h-40 overflow-y-auto pr-1">
                        @forelse ($users as $user)
                            <label class="flex items-center gap-3 p-2 rounded-xl neu-sm hover:bg-white/60 cursor-pointer transition-colors">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="create-user-cb rounded border-zinc-300 text-zinc-900 focus:ring-zinc-800 w-4 h-4">
                                <div class="w-6 h-6 rounded-lg bg-zinc-900 text-white flex items-center justify-center font-bold text-[9px]">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-zinc-800 truncate">{{ $user->name }}</p>
                                    <p class="text-[10px] text-zinc-400 truncate">{{ $user->email }}</p>
                                </div>
                            </label>
                        @empty
                            <p class="text-xs text-zinc-400 py-3 text-center">No registered users available.</p>
                        @endforelse
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-zinc-200/80">
                    <button type="button" @click="openCreateModal = false" class="px-4 py-2.5 neu-btn-light rounded-xl text-xs font-bold cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 neu-btn-dark rounded-xl text-xs font-bold cursor-pointer">
                        Publish Dispatch
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. EDIT ANNOUNCEMENT MODAL -->
    <div x-show="openEditModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-zinc-900/50 backdrop-blur-xs flex items-center justify-center p-4 z-50"
         x-cloak>
        <div class="neu-flat rounded-3xl max-w-lg w-full p-7 shadow-2xl max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             @click.away="openEditModal = false">
            
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-zinc-200/80">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-zinc-900 text-white flex items-center justify-center">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-zinc-900">Edit Announcement</h3>
                        <p class="text-[11px] text-zinc-500">Update broadcast details and recipients</p>
                    </div>
                </div>
                <button @click="openEditModal = false" class="p-1.5 rounded-xl neu-sm text-zinc-400 hover:text-zinc-800">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form :action="'/admin/notifications/' + currentEdit.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 mb-1.5">Title</label>
                    <input type="text" name="title" x-model="currentEdit.title" required
                           class="w-full px-4 py-2.5 neu-inset rounded-2xl text-xs text-zinc-800 focus:outline-none font-semibold">
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 mb-1.5">Description</label>
                    <textarea name="description" rows="3" x-model="currentEdit.description" required
                              class="w-full px-4 py-2.5 neu-inset rounded-2xl text-xs text-zinc-800 focus:outline-none font-medium resize-none"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 mb-1.5">Notice Type</label>
                        <select name="type" x-model="currentEdit.type" class="w-full px-4 py-2.5 neu-inset rounded-2xl text-xs text-zinc-800 font-semibold focus:outline-none">
                            <option value="info">ℹ️ Info Notice</option>
                            <option value="warning">⚡ Warning Notice</option>
                            <option value="error">⚠️ Critical Alert</option>
                            <option value="success">✓ Success Update</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 mb-1.5">Audience</label>
                        <select name="visibility" x-model="currentEdit.visibility" class="w-full px-4 py-2.5 neu-inset rounded-2xl text-xs text-zinc-800 font-semibold focus:outline-none">
                            <option value="public">🌐 Public (All Users)</option>
                            <option value="exclusive">🔒 Targeted (Select Users)</option>
                        </select>
                    </div>
                </div>

                <!-- Recipient Selector (exclusive mode in edit) -->
                <div x-show="currentEdit.visibility === 'exclusive'" x-transition class="neu-card rounded-2xl p-4">
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-zinc-200">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-zinc-700">Choose Recipients</label>
                        <label class="flex items-center gap-1.5 text-xs text-zinc-600 font-semibold cursor-pointer select-none">
                            <input type="checkbox" x-model="editSelectAll"
                                   @change="document.querySelectorAll('.edit-user-cb').forEach(cb => cb.checked = editSelectAll)"
                                   class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-800 w-3.5 h-3.5">
                            Select All
                        </label>
                    </div>
                    <div class="space-y-1.5 max-h-40 overflow-y-auto pr-1">
                        @foreach ($users as $user)
                            <label class="flex items-center gap-3 p-2 rounded-xl neu-sm hover:bg-white/60 cursor-pointer transition-colors">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                       :checked="currentEdit.recipients && currentEdit.recipients.includes({{ $user->id }})"
                                       class="edit-user-cb rounded border-zinc-300 text-zinc-900 focus:ring-zinc-800 w-4 h-4">
                                <div class="w-6 h-6 rounded-lg bg-zinc-900 text-white flex items-center justify-center font-bold text-[9px]">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-zinc-800 truncate">{{ $user->name }}</p>
                                    <p class="text-[10px] text-zinc-400 truncate">{{ $user->email }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-zinc-200/80">
                    <button type="button" @click="openEditModal = false" class="px-4 py-2.5 neu-btn-light rounded-xl text-xs font-bold cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 neu-btn-dark rounded-xl text-xs font-bold cursor-pointer">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 3. READERS MODAL -->
    <div x-show="openReadersModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-zinc-900/50 backdrop-blur-xs flex items-center justify-center p-4 z-50"
         x-cloak>
        <div class="neu-flat rounded-3xl max-w-md w-full p-7 shadow-2xl max-h-[85vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             @click.away="openReadersModal = false">
            
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-zinc-200/80">
                <div>
                    <h3 class="text-base font-black text-zinc-900">Readership Audit</h3>
                    <p class="text-[11px] text-zinc-500 line-clamp-1 mt-0.5" x-text="readersData.title"></p>
                </div>
                <button @click="openReadersModal = false" class="p-1.5 rounded-xl neu-sm text-zinc-400 hover:text-zinc-800">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="py-2">
                <template x-if="readersData.loading">
                    <div class="py-8 text-center text-xs text-zinc-400 flex items-center justify-center gap-2">
                        <i data-lucide="loader" class="w-4 h-4 animate-spin"></i> Loading reader audit...
                    </div>
                </template>

                <template x-if="!readersData.loading && readersData.readers.length === 0">
                    <div class="py-8 text-center">
                        <div class="w-10 h-10 rounded-2xl neu-sm flex items-center justify-center mx-auto mb-2 text-zinc-400">
                            <i data-lucide="clock" class="w-5 h-5"></i>
                        </div>
                        <p class="text-xs font-bold text-zinc-700">No readers yet</p>
                        <p class="text-[10px] text-zinc-400 mt-0.5">Recipients have not opened this announcement yet.</p>
                    </div>
                </template>

                <template x-if="!readersData.loading && readersData.readers.length > 0">
                    <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                        <template x-for="r in readersData.readers" :key="r.id">
                            <div class="p-3 rounded-2xl neu-sm flex items-center justify-between">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-7 h-7 rounded-xl bg-zinc-900 text-white flex items-center justify-center font-bold text-[10px] shrink-0"
                                         x-text="r.name.substring(0,1).toUpperCase()"></div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-zinc-800 truncate" x-text="r.name"></p>
                                        <p class="text-[10px] text-zinc-400 truncate" x-text="r.email"></p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-zinc-500" x-text="r.read_at"></span>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <div class="pt-4 border-t border-zinc-200/80 text-right">
                <button @click="openReadersModal = false" class="px-4 py-2 neu-btn-dark rounded-xl text-xs font-bold cursor-pointer">
                    Close Audit
                </button>
            </div>
        </div>
    </div>

    <!-- 4. DELETE CONFIRMATION MODAL (replaces browser confirm) -->
    <div x-show="openDeleteModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-zinc-900/50 backdrop-blur-xs flex items-center justify-center p-4 z-50"
         x-cloak>
        <div class="neu-flat rounded-3xl max-w-sm w-full p-6 shadow-2xl text-center"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             @click.away="openDeleteModal = false">
            
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4 border border-rose-200">
                <i data-lucide="trash-2" class="w-6 h-6"></i>
            </div>
            
            <h3 class="text-base font-extrabold text-zinc-900">Delete Announcement?</h3>
            <p class="text-xs text-zinc-500 mt-1.5 leading-relaxed">
                This action cannot be undone. This dispatch and its read audit records will be permanently removed.
            </p>

            <form :action="deleteActionUrl" method="POST" class="mt-6 flex gap-3">
                @csrf
                @method('DELETE')
                <button type="button" @click="openDeleteModal = false" class="flex-1 py-2.5 neu-btn-light rounded-xl text-xs font-bold text-zinc-700 cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="flex-1 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md cursor-pointer">
                    Delete
                </button>
            </form>
        </div>
    </div>
@endsection
