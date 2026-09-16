<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>notify.xyz // Admin — @yield('page-title', 'Dashboard')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
    <script>
        // Check theme on initial load to avoid flash of white
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #eef2f7;
            color: #1e293b;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        /* Neumorphic Foundations */
        .neu-flat {
            background: #eef2f7;
            box-shadow: 8px 8px 18px #cbd3df, -8px -8px 18px #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.85);
        }
        .neu-card {
            background: #eef2f7;
            box-shadow: 6px 6px 14px #cbd3df, -6px -6px 14px #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.8);
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .neu-card:hover {
            box-shadow: 8px 8px 20px #cbd3df, -8px -8px 20px #ffffff;
        }
        .neu-sm {
            background: #eef2f7;
            box-shadow: 3px 3px 8px #cbd3df, -3px -3px 8px #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.85);
        }
        .neu-inset {
            background: #e9eef5;
            box-shadow: inset 3px 3px 6px #cbd3df, inset -3px -3px 6px #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        .neu-btn-dark {
            background: #09090b;
            color: #ffffff;
            box-shadow: 5px 5px 12px #cbd3df, -5px -5px 12px #ffffff;
            transition: all 0.15s ease;
        }
        .neu-btn-dark:hover {
            background: #18181b;
            box-shadow: 3px 3px 8px #cbd3df, -3px -3px 8px #ffffff;
            transform: translateY(-1px);
        }
        .neu-btn-dark:active {
            box-shadow: inset 2px 2px 4px #000000;
            transform: translateY(1px);
        }
        .neu-btn-light {
            background: #eef2f7;
            color: #18181b;
            box-shadow: 4px 4px 10px #cbd3df, -4px -4px 10px #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.8);
            transition: all 0.15s ease;
        }
        .neu-btn-light:hover {
            background: #e6ecf4;
            box-shadow: 2px 2px 6px #cbd3df, -2px -2px 6px #ffffff;
        }
        .neu-btn-light:active {
            box-shadow: inset 2px 2px 5px #cbd3df, inset -2px -2px 5px #ffffff;
        }
        .neu-pill-active {
            background: #09090b;
            color: #ffffff;
            box-shadow: inset 2px 2px 5px rgba(0,0,0,0.5);
        }
        .neu-pill-inactive {
            background: #eef2f7;
            color: #52525b;
            box-shadow: 3px 3px 7px #cbd3df, -3px -3px 7px #ffffff;
            border: 1px solid rgba(255,255,255,0.7);
        }
        .neu-pill-inactive:hover {
            color: #09090b;
            background: #e9eef5;
        }

        /* Custom scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #eef2f7;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd3df;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Dark Mode Theme Overrides */
        html.dark body {
            background-color: #0b0f17;
            color: #f1f5f9;
        }

        html.dark aside {
            background-color: #0f141e !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        html.dark header {
            background-color: rgba(15, 20, 30, 0.88) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        html.dark .neu-flat {
            background: #141a24;
            box-shadow: 7px 7px 16px #080b11, -7px -7px 16px #202937;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        html.dark .neu-card {
            background: #141a24;
            box-shadow: 6px 6px 14px #080b11, -6px -6px 14px #202937;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        html.dark .neu-card:hover {
            box-shadow: 8px 8px 20px #080b11, -8px -8px 20px #202937;
        }
        html.dark .neu-sm {
            background: #141a24;
            box-shadow: 3px 3px 8px #080b11, -3px -3px 8px #202937;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        html.dark .neu-inset {
            background: #0f131a;
            box-shadow: inset 3px 3px 6px #080b11, inset -3px -3px 6px #202937;
            border: 1px solid rgba(255, 255, 255, 0.05);
            color: #f1f5f9;
        }
        html.dark .neu-btn-dark {
            background: #f8fafc;
            color: #09090b;
            box-shadow: 4px 4px 10px #080b11, -4px -4px 10px #202937;
        }
        html.dark .neu-btn-dark:hover {
            background: #ffffff;
            box-shadow: 2px 2px 6px #080b11, -2px -2px 6px #202937;
        }
        html.dark .neu-btn-dark:active {
            box-shadow: inset 2px 2px 4px rgba(0,0,0,0.4);
        }
        html.dark .neu-btn-light {
            background: #141a24;
            color: #e2e8f0;
            box-shadow: 4px 4px 10px #080b11, -4px -4px 10px #202937;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        html.dark .neu-btn-light:hover {
            background: #1a2230;
            box-shadow: 2px 2px 6px #080b11, -2px -2px 6px #202937;
        }
        html.dark .neu-btn-light:active {
            box-shadow: inset 2px 2px 5px #080b11, inset -2px -2px 5px #202937;
        }
        html.dark .neu-pill-active {
            background: #f8fafc;
            color: #09090b;
            box-shadow: inset 2px 2px 5px rgba(0,0,0,0.3);
        }
        html.dark .neu-pill-inactive {
            background: #141a24;
            color: #94a3b8;
            box-shadow: 3px 3px 7px #080b11, -3px -3px 7px #202937;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        html.dark .neu-pill-inactive:hover {
            color: #ffffff;
            background: #1a2230;
        }

        html.dark .text-zinc-900 { color: #f8fafc !important; }
        html.dark .text-zinc-800 { color: #f1f5f9 !important; }
        html.dark .text-zinc-700 { color: #e2e8f0 !important; }
        html.dark .text-zinc-600 { color: #cbd5e1 !important; }
        html.dark .text-zinc-500 { color: #94a3b8 !important; }
        html.dark .text-zinc-400 { color: #64748b !important; }

        html.dark .bg-zinc-900 {
            background-color: #1e2638 !important;
            color: #f8fafc !important;
        }
        html.dark .bg-zinc-800 {
            background-color: #273144 !important;
        }
        html.dark .bg-zinc-100 {
            background-color: #1a2230 !important;
        }
        html.dark [class*="bg-zinc-100"] {
            background-color: rgba(26, 34, 48, 0.4) !important;
        }

        html.dark [class*="border-zinc-200"] {
            border-color: rgba(255, 255, 255, 0.08) !important;
        }
        html.dark [class*="border-zinc-100"] {
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        html.dark input, html.dark textarea, html.dark select {
            background-color: #0f131a !important;
            color: #f1f5f9 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        html.dark input::placeholder, html.dark textarea::placeholder {
            color: #64748b !important;
        }

        html.dark tr.hover\:bg-white\/40:hover {
            background-color: rgba(255, 255, 255, 0.04) !important;
        }

        html.dark ::-webkit-scrollbar-track {
            background: #0b0f17;
        }
        html.dark ::-webkit-scrollbar-thumb {
            background: #273144;
        }
        html.dark ::-webkit-scrollbar-thumb:hover {
            background: #3b475e;
        }

        /* Subtle Monochrome Grid Background */
        .bg-grid-monochrome {
            background-image: radial-gradient(rgba(15, 23, 42, 0.12) 1.2px, transparent 1.2px);
            background-size: 24px 24px;
        }
        html.dark .bg-grid-monochrome {
            background-image: radial-gradient(rgba(241, 245, 249, 0.08) 1.2px, transparent 1.2px);
        }
    </style>
</head>
<body class="min-h-screen flex selection:bg-zinc-900 selection:text-white relative"
      @keydown.window.escape="sidebarOpen = false"
      x-data="{
          darkMode: document.documentElement.classList.contains('dark'),
          toggleTheme() {
              this.darkMode = !this.darkMode;
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
                  localStorage.setItem('theme', 'dark');
              } else {
                  document.documentElement.classList.remove('dark');
                  localStorage.setItem('theme', 'light');
              }
              this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
          },
          sidebarOpen: false,
          desktopSidebarOpen: localStorage.getItem('admin_sidebar_open') !== 'false',
          toggleSidebar() {
              if (window.innerWidth >= 1024) {
                  this.desktopSidebarOpen = !this.desktopSidebarOpen;
                  localStorage.setItem('admin_sidebar_open', this.desktopSidebarOpen);
              } else {
                  this.sidebarOpen = !this.sidebarOpen;
              }
              this.$nextTick(() => { lucide.createIcons(); });
          },
          bellOpen: false,
          toast: { show: false, message: '' },
          triggerToast(msg) {
              this.toast.message = msg;
              this.toast.show = true;
              setTimeout(() => { this.toast.show = false; }, 3500);
          },
          @yield('alpine-data')
      }">

    <!-- Monochrome Background Design -->
    <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden">
        <!-- Subtle Monochrome Dot Grid -->
        <div class="absolute inset-0 bg-grid-monochrome opacity-75 dark:opacity-40 [mask-image:radial-gradient(ellipse_at_center,black_45%,transparent_80%)]"></div>

        <!-- Ambient Monochrome Signal Rings -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[850px] h-[850px] opacity-[0.035] dark:opacity-[0.055] pointer-events-none">
            <svg viewBox="0 0 200 200" class="w-full h-full stroke-current text-zinc-900 dark:text-zinc-100" fill="none">
                <circle cx="100" cy="100" r="28" stroke-width="0.75" stroke-dasharray="2 2" />
                <circle cx="100" cy="100" r="54" stroke-width="0.75" />
                <circle cx="100" cy="100" r="80" stroke-width="0.75" stroke-dasharray="3 3" />
                <circle cx="100" cy="100" r="106" stroke-width="0.75" />
            </svg>
        </div>
    </div>

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-zinc-900/50 backdrop-blur-xs z-40 lg:hidden"
         x-cloak></div>

    <!-- Sidebar Navigation (Discord-style Icon Dock when collapsed, Full Sidebar when expanded) -->
    <aside :class="{
               'translate-x-0': sidebarOpen,
               '-translate-x-full lg:translate-x-0': !sidebarOpen,
               'lg:w-64': desktopSidebarOpen,
               'lg:w-20': !desktopSidebarOpen
           }"
           class="fixed lg:sticky lg:top-0 inset-y-0 left-0 bg-[#eef2f7] border-r border-zinc-200/80 p-4 sm:p-5 flex flex-col justify-between shrink-0 h-screen lg:self-start z-40 transition-all duration-200 ease-in-out overflow-y-auto">
        
        <!-- Top Section: Brand & Nav Links -->
        <div class="flex flex-col">
            <!-- Brand Header -->
            <div class="flex items-center justify-between mb-8 pb-3 border-b border-zinc-200/60"
                 :class="!desktopSidebarOpen ? 'lg:flex-col lg:gap-2.5 lg:items-center' : ''">
                
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 group"
                   :title="!desktopSidebarOpen ? 'notify.xyz Admin Console' : ''">
                    <div class="w-10 h-10 rounded-2xl bg-zinc-900 flex items-center justify-center text-white shadow-md shadow-zinc-400/40 shrink-0 transition-transform group-hover:scale-105 duration-200">
                        <i data-lucide="radio" class="w-5 h-5"></i>
                    </div>
                    <div x-show="desktopSidebarOpen" class="transition-opacity duration-150" x-cloak>
                        <h1 class="text-base font-extrabold text-zinc-900 tracking-tight leading-none">notify<span class="text-zinc-500 font-medium">.xyz</span></h1>
                        <span class="text-[9px] font-extrabold text-zinc-600 uppercase tracking-widest block mt-0.5">Admin Console</span>
                    </div>
                </a>

                <!-- Mobile Close Button (X) -->
                <button @click="sidebarOpen = false"
                        class="lg:hidden p-1.5 rounded-xl neu-sm text-zinc-500 hover:text-zinc-900 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <!-- Desktop Collapse/Expand Button (Upper Side, Very Small) -->
                <button type="button"
                        @click="toggleSidebar()"
                        :title="desktopSidebarOpen ? 'Collapse sidebar' : 'Expand sidebar'"
                        class="hidden lg:flex items-center justify-center w-6 h-6 rounded-lg neu-sm text-zinc-400 hover:text-zinc-800 hover:bg-white/80 transition-all cursor-pointer shrink-0">
                    <svg x-show="desktopSidebarOpen" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <svg x-show="!desktopSidebarOpen" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- Section Label (hidden when collapsed) -->
            <p x-show="desktopSidebarOpen" class="text-[10px] font-extrabold text-zinc-400 uppercase tracking-wider mb-3 px-3" x-cloak>Management</p>

            <!-- Navigation Links -->
            <nav class="space-y-2.5">
                @php $currentPage = $currentPage ?? ''; @endphp

                <!-- 1. Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                   @click="if (window.innerWidth < 1024) sidebarOpen = false"
                   :title="!desktopSidebarOpen ? 'Dashboard' : ''"
                   class="flex items-center rounded-2xl font-semibold text-xs tracking-wide transition-all {{ $currentPage === 'Dashboard' ? 'neu-btn-dark' : 'text-zinc-600 hover:text-zinc-900 hover:bg-white/60 neu-sm' }}"
                   :class="desktopSidebarOpen ? 'gap-3 w-full px-4 py-3' : 'w-11 h-11 mx-auto justify-center'">
                    <i data-lucide="layout-grid" class="w-4 h-4 shrink-0"></i>
                    <span x-show="desktopSidebarOpen" class="truncate" x-cloak>Dashboard</span>
                </a>

                <!-- 2. Announcements -->
                <a href="{{ route('admin.notifications.index') }}"
                   @click="if (window.innerWidth < 1024) sidebarOpen = false"
                   :title="!desktopSidebarOpen ? 'Announcements' : ''"
                   class="flex items-center rounded-2xl font-semibold text-xs tracking-wide transition-all {{ $currentPage === 'Announcements' ? 'neu-btn-dark' : 'text-zinc-600 hover:text-zinc-900 hover:bg-white/60 neu-sm' }}"
                   :class="desktopSidebarOpen ? 'gap-3 w-full px-4 py-3' : 'w-11 h-11 mx-auto justify-center'">
                    <i data-lucide="megaphone" class="w-4 h-4 shrink-0"></i>
                    <span x-show="desktopSidebarOpen" class="truncate" x-cloak>Announcements</span>
                </a>

                <!-- 3. User Directory -->
                <a href="{{ route('admin.users.index') }}"
                   @click="if (window.innerWidth < 1024) sidebarOpen = false"
                   :title="!desktopSidebarOpen ? 'User Directory' : ''"
                   class="flex items-center rounded-2xl font-semibold text-xs tracking-wide transition-all {{ $currentPage === 'Users' ? 'neu-btn-dark' : 'text-zinc-600 hover:text-zinc-900 hover:bg-white/60 neu-sm' }}"
                   :class="desktopSidebarOpen ? 'gap-3 w-full px-4 py-3' : 'w-11 h-11 mx-auto justify-center'">
                    <i data-lucide="users" class="w-4 h-4 shrink-0"></i>
                    <span x-show="desktopSidebarOpen" class="truncate" x-cloak>User Directory</span>
                </a>
            </nav>
        </div>

        <!-- Bottom Section: Profile, Collapse Toggle, Logout -->
        <div class="pt-6 border-t border-zinc-200/80 space-y-3">
            <!-- Profile Info -->
            <div class="rounded-2xl neu-sm"
                 :class="desktopSidebarOpen ? 'p-3 flex items-center gap-3' : 'p-1.5 flex justify-center'">
                <div class="w-9 h-9 rounded-xl bg-zinc-900 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs"
                     :title="!desktopSidebarOpen ? '{{ Auth::user()->name }} (Superadmin)' : ''">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div x-show="desktopSidebarOpen" class="flex-1 min-w-0" x-cloak>
                    <p class="text-xs font-bold text-zinc-900 truncate">{{ Auth::user()->name }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-zinc-800"></span>
                        <p class="text-[10px] font-semibold text-zinc-500 uppercase tracking-wider">Superadmin</p>
                    </div>
                </div>
            </div>


            <!-- Sign Out Button -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        :title="!desktopSidebarOpen ? 'Sign Out' : ''"
                        class="rounded-xl neu-btn-light text-xs font-bold text-zinc-700 hover:text-rose-600 flex items-center justify-center cursor-pointer transition-colors"
                        :class="desktopSidebarOpen ? 'w-full py-2.5 gap-2' : 'w-11 h-11 mx-auto'">
                    <i data-lucide="log-out" class="w-3.5 h-3.5 shrink-0"></i>
                    <span x-show="desktopSidebarOpen" class="truncate" x-cloak>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Viewport -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen">
        
        <!-- Top Navigation Bar -->
        <header class="sticky top-0 bg-[#eef2f7]/90 backdrop-blur-md border-b border-zinc-200/80 px-4 sm:px-6 py-3.5 flex items-center justify-between z-30">
            <div class="flex items-center gap-3">
                <!-- Mobile Drawer Trigger Button (Hidden on Desktop) -->
                <button @click="sidebarOpen = true"
                        title="Open Menu"
                        class="lg:hidden p-2.5 rounded-2xl neu-sm text-zinc-700 hover:text-zinc-900 cursor-pointer transition-transform active:scale-95 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <div>
                    <h2 class="text-sm sm:text-base font-extrabold text-zinc-900 tracking-tight">@yield('page-title', 'Dashboard')</h2>
                    <p class="text-[10px] sm:text-[11px] font-medium text-zinc-500 hidden sm:block">notify.xyz · Admin Console</p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 sm:gap-3">
                <!-- Status Badge -->
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full neu-sm text-[11px] font-semibold text-zinc-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>System Active</span>
                </div>

                <!-- Theme Toggle Button (Dark / Light Mode) -->
                <button type="button"
                        @click="toggleTheme()"
                        :title="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
                        class="p-2.5 rounded-2xl neu-sm text-zinc-700 hover:text-zinc-900 cursor-pointer transition-transform active:scale-95 flex items-center justify-center">
                    <!-- Sun Icon (Shown in Dark Mode) -->
                    <svg x-show="darkMode" class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                        <circle cx="12" cy="12" r="4" stroke-width="2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32l1.41 1.41M2 12h2m16 0h2M6.34 17.66l-1.41 1.41m14.14-14.14l-1.41 1.41"/>
                    </svg>
                    <!-- Moon Icon (Shown in Light Mode) -->
                    <svg x-show="!darkMode" class="w-4 h-4 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                <!-- Notification Bell Popover -->
                <div class="relative" x-data="{
                    dropdownOpen: false,
                    loading: false,
                    feedItems: [],
                    totalCount: {{ \App\Models\Announcement::count() }},
                    loadRecent() {
                        this.loading = true;
                        fetch('{{ route('admin.notifications.index') }}?format=json')
                            .then(r => r.json())
                            .then(data => {
                                this.feedItems = data.slice(0, 5);
                                this.loading = false;
                            })
                            .catch(() => { this.loading = false; });
                    }
                }">
                    <button @click="dropdownOpen = !dropdownOpen; if(dropdownOpen) { loadRecent(); }"
                            class="relative p-2.5 rounded-2xl neu-sm text-zinc-700 hover:text-zinc-900 cursor-pointer transition-all">
                        <i data-lucide="bell" class="w-4 h-4"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-zinc-900 rounded-full"></span>
                    </button>

                    <!-- Dropdown Content -->
                    <div x-show="dropdownOpen"
                         @click.away="dropdownOpen = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                         class="absolute right-0 mt-3 w-80 sm:w-96 rounded-3xl neu-flat p-5 z-50 text-zinc-800"
                         x-cloak>
                        <div class="flex items-center justify-between pb-3 border-b border-zinc-200/80 mb-3">
                            <div class="flex items-center gap-2">
                                <i data-lucide="radio" class="w-4 h-4 text-zinc-900"></i>
                                <h4 class="text-xs font-bold text-zinc-900 uppercase tracking-wider">Recent Dispatches</h4>
                            </div>
                            <a href="{{ route('admin.notifications.index') }}" class="text-[11px] font-bold text-zinc-500 hover:text-zinc-900 transition-colors">Manage All →</a>
                        </div>
                        
                        <div class="space-y-2.5 max-h-64 overflow-y-auto pr-1">
                            @forelse (\App\Models\Announcement::latest()->take(4)->get() as $recent)
                                <div class="p-3 rounded-xl neu-sm flex items-start gap-3">
                                    <div class="w-7 h-7 rounded-lg bg-zinc-900 text-white flex items-center justify-center shrink-0 text-xs">
                                        <i data-lucide="{{ $recent->icon }}" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-zinc-900 truncate">{{ $recent->title }}</p>
                                        <p class="text-[10px] text-zinc-500 line-clamp-1 mt-0.5">{{ $recent->description }}</p>
                                        <p class="text-[9px] text-zinc-400 font-semibold mt-1">{{ $recent->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-zinc-400 text-center py-4">No dispatches recorded yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Admin Avatar Chip -->
                <div class="flex items-center gap-2 pl-2 border-l border-zinc-200">
                    <div class="w-8 h-8 rounded-xl bg-zinc-900 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="flex-1 p-6 sm:p-8 overflow-y-auto">
            @if (session('success'))
                <div class="mb-6 p-4 rounded-2xl neu-flat border-l-4 border-zinc-900 flex items-center justify-between gap-3 text-xs font-bold text-zinc-800"
                     x-data="{ show: true }" x-show="show" x-transition>
                    <div class="flex items-center gap-2.5">
                        <i data-lucide="check-circle" class="w-4 h-4 text-zinc-900"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-zinc-400 hover:text-zinc-800">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Modals Slot -->
    @yield('modals')

    <!-- Interactive Toast Banner -->
    <div x-show="toast.show"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="fixed bottom-6 right-6 z-50 px-5 py-3.5 rounded-2xl neu-btn-dark flex items-center gap-3 text-xs font-bold shadow-2xl"
         x-cloak>
        <i data-lucide="check" class="w-4 h-4 text-emerald-400"></i>
        <span x-text="toast.message"></span>
    </div>

    <script>
        lucide.createIcons();
        document.addEventListener('alpine:initialized', () => {
            lucide.createIcons();
        });
        document.addEventListener('click', () => {
            setTimeout(() => lucide.createIcons(), 50);
        });
    </script>
</body>
</html>
