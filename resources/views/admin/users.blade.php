@extends('layouts.admin')
@section('page-title', 'User Directory')

@section('alpine-data')
    selectedIds: [],
    selectAll: false,
    openSingleDeleteModal: false,
    openBulkDeleteModal: false,
    targetUser: { id: null, name: '', email: '' },
    currentTotalUsers: {{ $totalUsers }},
    currentMemberCount: {{ $memberCount }},
    isProcessing: false,

    toggleSelectAll(checked, allIds) {
        if (checked) {
            this.selectedIds = [...allIds];
        } else {
            this.selectedIds = [];
        }
    },

    confirmSingleDelete(id, name, email) {
        this.targetUser = { id, name, email };
        this.openSingleDeleteModal = true;
    },

    executeSingleDelete() {
        if (!this.targetUser.id || this.isProcessing) return;
        this.isProcessing = true;

        fetch('/admin/users/' + this.targetUser.id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            this.isProcessing = false;
            this.openSingleDeleteModal = false;

            if (data.success) {
                // Remove row with animation
                let row = document.getElementById('user-row-' + this.targetUser.id);
                if (row) {
                    row.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => row.remove(), 200);
                }

                this.currentTotalUsers = data.totalUsers;
                this.currentMemberCount = data.memberCount;
                this.selectedIds = this.selectedIds.filter(id => id != this.targetUser.id);
                this.triggerToast(data.message);
            } else if (data.error) {
                alert(data.error);
            }
        })
        .catch(() => {
            this.isProcessing = false;
            alert('An error occurred while deleting the user.');
        });
    },

    executeBulkDelete() {
        if (this.selectedIds.length === 0 || this.isProcessing) return;
        this.isProcessing = true;

        fetch('{{ route('admin.users.bulkDestroy') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids: this.selectedIds })
        })
        .then(r => r.json())
        .then(data => {
            this.isProcessing = false;
            this.openBulkDeleteModal = false;

            if (data.success) {
                // Remove rows smoothly
                data.deleted_ids.forEach(id => {
                    let row = document.getElementById('user-row-' + id);
                    if (row) {
                        row.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => row.remove(), 200);
                    }
                });

                this.currentTotalUsers = data.totalUsers;
                this.currentMemberCount = data.memberCount;
                this.selectedIds = [];
                this.selectAll = false;
                this.triggerToast(data.message);
            } else if (data.error) {
                alert(data.error);
            }
        })
        .catch(() => {
            this.isProcessing = false;
            alert('An error occurred during bulk deletion.');
        });
    }
@endsection

@section('content')
    @php
        $deletableIds = $users->where('role', '!=', 'admin')->pluck('id')->toArray();
    @endphp

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-zinc-900 tracking-tight">User Directory</h1>
            <p class="text-xs font-semibold text-zinc-500 mt-1">Manage registered accounts, inspect readership, and perform batch operations</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3.5 py-1.5 rounded-full neu-sm text-xs font-bold text-zinc-800">
                <span x-text="currentTotalUsers">{{ $totalUsers }}</span> Total Accounts
            </span>
        </div>
    </div>

    <!-- Metrics Stat Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="neu-card rounded-3xl p-5">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-2xl bg-zinc-900 text-white flex items-center justify-center">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-xl font-black text-zinc-900" x-text="currentTotalUsers">{{ $totalUsers }}</span>
                    <p class="text-[11px] font-bold text-zinc-500 uppercase tracking-wider">Total Accounts</p>
                </div>
            </div>
        </div>

        <div class="neu-card rounded-3xl p-5">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-2xl neu-sm text-zinc-800 flex items-center justify-center">
                    <i data-lucide="shield" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-xl font-black text-zinc-900">{{ $adminCount }}</span>
                    <p class="text-[11px] font-bold text-zinc-500 uppercase tracking-wider">Administrators</p>
                </div>
            </div>
        </div>

        <div class="neu-card rounded-3xl p-5">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-2xl neu-sm text-zinc-800 flex items-center justify-center">
                    <i data-lucide="user-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-xl font-black text-zinc-900" x-text="currentMemberCount">{{ $memberCount }}</span>
                    <p class="text-[11px] font-bold text-zinc-500 uppercase tracking-wider">Members / Recipients</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Bar & Batch Control -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="relative w-full max-w-sm">
            <i data-lucide="search" class="absolute left-3.5 top-2.5 text-zinc-400 w-4 h-4"></i>
            <input type="text" name="search" value="{{ $search ?? '' }}"
                   placeholder="Search by name or email..."
                   class="w-full pl-10 pr-9 py-2 neu-inset rounded-2xl text-xs text-zinc-800 placeholder-zinc-400 focus:outline-none font-medium">
            @if (!empty($search))
                <a href="{{ route('admin.users.index') }}" class="absolute right-3 top-2.5 text-zinc-400 hover:text-zinc-700">
                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                </a>
            @endif
        </form>

        <!-- Floating Multi-Select Bulk Actions Strip -->
        <div x-show="selectedIds.length > 0"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="flex items-center gap-3 px-4 py-2 rounded-2xl neu-card bg-zinc-900 text-white shadow-xl"
             x-cloak>
            <span class="text-xs font-bold flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                <span x-text="selectedIds.length"></span> selected
            </span>

            <div class="h-4 w-px bg-zinc-700"></div>

            <button type="button"
                    @click="openBulkDeleteModal = true"
                    class="px-3 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition-colors cursor-pointer flex items-center gap-1.5">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                <span>Delete Selected</span>
            </button>

            <button type="button"
                    @click="selectedIds = []; selectAll = false;"
                    class="text-[11px] font-semibold text-zinc-400 hover:text-white transition-colors cursor-pointer">
                Clear
            </button>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="neu-flat rounded-3xl p-6 sm:p-7 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200/80 text-[10px] font-extrabold text-zinc-400 uppercase tracking-wider">
                        <!-- Select All Checkbox -->
                        <th class="pb-3.5 pl-3 w-10">
                            <input type="checkbox"
                                   x-model="selectAll"
                                   @change="toggleSelectAll($el.checked, {{ json_encode($deletableIds) }})"
                                   class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-800 w-4 h-4 cursor-pointer">
                        </th>
                        <th class="pb-3.5">User Profile</th>
                        <th class="pb-3.5">Role</th>
                        <th class="pb-3.5">Readership</th>
                        <th class="pb-3.5">Registered</th>
                        <th class="pb-3.5 pr-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200/60 text-xs font-semibold">
                    @forelse ($users as $u)
                        <tr id="user-row-{{ $u->id }}"
                            class="hover:bg-white/40 transition-all duration-200 group"
                            :class="selectedIds.includes({{ $u->id }}) ? 'bg-zinc-100/70' : ''">
                            
                            <!-- Row Checkbox -->
                            <td class="py-4 pl-3">
                                @if (!$u->isAdmin())
                                    <input type="checkbox"
                                           value="{{ $u->id }}"
                                           x-model="selectedIds"
                                           class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-800 w-4 h-4 cursor-pointer">
                                @else
                                    <span class="w-4 h-4 flex items-center justify-center text-zinc-400" title="Protected Admin">
                                        <i data-lucide="shield" class="w-3.5 h-3.5 text-zinc-400"></i>
                                    </span>
                                @endif
                            </td>

                            <!-- User Profile -->
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-2xl {{ $u->isAdmin() ? 'bg-zinc-900 text-white' : 'neu-sm text-zinc-800' }} flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-zinc-900 truncate">{{ $u->name }}</p>
                                        <p class="text-[11px] text-zinc-400 truncate">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Role -->
                            <td class="py-4">
                                @if ($u->isAdmin())
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-zinc-900 text-white">
                                        Superadmin
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold neu-sm text-zinc-700">
                                        Member
                                    </span>
                                @endif
                            </td>

                            <!-- Readership -->
                            <td class="py-4 text-zinc-500">
                                <span class="px-2.5 py-1 rounded-xl neu-inset text-[10px] font-bold text-zinc-700">
                                    {{ $u->read_announcements_count }} dispatches read
                                </span>
                            </td>

                            <!-- Registered Date -->
                            <td class="py-4 text-zinc-400 text-[11px]">
                                {{ $u->created_at->format('M d, Y') }}
                                <span class="text-[9px] block text-zinc-400">({{ $u->created_at->diffForHumans() }})</span>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 pr-3 text-right">
                                @if ($u->isAdmin())
                                    <span class="px-2.5 py-1 rounded-xl neu-inset text-[10px] font-bold text-zinc-500 inline-flex items-center gap-1">
                                        <i data-lucide="shield" class="w-3 h-3 text-zinc-500"></i>
                                        <span>{{ $u->id === Auth::id() ? 'Current Admin' : 'Admin Protected' }}</span>
                                    </span>
                                @else
                                    <button type="button"
                                            @click="confirmSingleDelete({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ addslashes($u->email) }}')"
                                            title="Delete User"
                                            class="p-2 neu-sm rounded-xl text-zinc-400 hover:text-rose-600 hover:bg-rose-50/50 transition-colors cursor-pointer inline-flex items-center gap-1.5">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        <span class="text-[10px] font-bold hidden sm:inline">Delete</span>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-zinc-400">
                                <div class="w-12 h-12 rounded-2xl neu-sm flex items-center justify-center mx-auto mb-2 text-zinc-400">
                                    <i data-lucide="users" class="w-6 h-6"></i>
                                </div>
                                <p class="text-xs font-bold text-zinc-600">No users found</p>
                                <p class="text-[11px] text-zinc-400 mt-0.5">No accounts match your query "{{ $search }}".</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('modals')
    <!-- 1. SINGLE DELETE USER CONFIRMATION MODAL (AJAX) -->
    <div x-show="openSingleDeleteModal"
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
             @click.away="if (!isProcessing) openSingleDeleteModal = false">
            
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4 border border-rose-200">
                <i data-lucide="user-x" class="w-6 h-6"></i>
            </div>
            
            <h3 class="text-base font-extrabold text-zinc-900">Delete User Account?</h3>
            
            <div class="my-3 p-3 rounded-2xl neu-inset text-left">
                <p class="text-xs font-bold text-zinc-900 truncate" x-text="targetUser.name"></p>
                <p class="text-[10px] text-zinc-500 truncate mt-0.5" x-text="targetUser.email"></p>
            </div>

            <p class="text-xs text-zinc-500 mt-2 leading-relaxed">
                This account and its targeted announcement associations will be immediately purged without refreshing the page.
            </p>

            <div class="mt-6 flex gap-3">
                <button type="button"
                        :disabled="isProcessing"
                        @click="openSingleDeleteModal = false"
                        class="flex-1 py-2.5 neu-btn-light rounded-xl text-xs font-bold text-zinc-700 cursor-pointer">
                    Cancel
                </button>
                <button type="button"
                        :disabled="isProcessing"
                        @click="executeSingleDelete()"
                        class="flex-1 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md cursor-pointer flex items-center justify-center gap-1.5">
                    <span x-show="!isProcessing">Delete User</span>
                    <span x-show="isProcessing" class="flex items-center gap-1.5" x-cloak>
                        <i data-lucide="loader" class="w-3.5 h-3.5 animate-spin"></i> Deleting...
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- 2. BULK DELETE CONFIRMATION MODAL (AJAX) -->
    <div x-show="openBulkDeleteModal"
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
             @click.away="if (!isProcessing) openBulkDeleteModal = false">
            
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4 border border-rose-200">
                <i data-lucide="trash-2" class="w-6 h-6"></i>
            </div>
            
            <h3 class="text-base font-extrabold text-zinc-900">
                Delete <span x-text="selectedIds.length"></span> Selected Accounts?
            </h3>

            <p class="text-xs text-zinc-500 mt-2 leading-relaxed">
                You are about to batch delete <span class="font-bold text-zinc-800" x-text="selectedIds.length"></span> user accounts. This action is irreversible and will remove all their read history and targeted permissions.
            </p>

            <div class="mt-6 flex gap-3">
                <button type="button"
                        :disabled="isProcessing"
                        @click="openBulkDeleteModal = false"
                        class="flex-1 py-2.5 neu-btn-light rounded-xl text-xs font-bold text-zinc-700 cursor-pointer">
                    Cancel
                </button>
                <button type="button"
                        :disabled="isProcessing"
                        @click="executeBulkDelete()"
                        class="flex-1 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md cursor-pointer flex items-center justify-center gap-1.5">
                    <span x-show="!isProcessing">Delete <span x-text="selectedIds.length"></span> Users</span>
                    <span x-show="isProcessing" class="flex items-center gap-1.5" x-cloak>
                        <i data-lucide="loader" class="w-3.5 h-3.5 animate-spin"></i> Purging...
                    </span>
                </button>
            </div>
        </div>
    </div>
@endsection
