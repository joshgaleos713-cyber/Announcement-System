<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>notify.xyz — Unified Announcement Engine</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>

    <!-- Theme Initialization -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Lucide Icons & Alpine.js -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #eef2f7;
            color: #18181b;
            transition: background-color 0.25s ease, color 0.25s ease;
        }

        /* Subtle Monochrome Grid Background */
        .bg-grid-monochrome {
            background-image: radial-gradient(rgba(15, 23, 42, 0.12) 1.2px, transparent 1.2px);
            background-size: 24px 24px;
        }
        html.dark .bg-grid-monochrome {
            background-image: radial-gradient(rgba(241, 245, 249, 0.08) 1.2px, transparent 1.2px);
        }

        /* Tactile Foundations (Monochrome) */
        .neu-flat {
            background: #eef2f7;
            box-shadow: 10px 10px 24px #cbd3df, -10px -10px 24px #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.85);
        }
        .neu-card {
            background: #eef2f7;
            box-shadow: 8px 8px 20px #cbd3df, -8px -8px 20px #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.85);
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .neu-card:hover {
            transform: translateY(-2px);
            box-shadow: 10px 10px 26px #cbd3df, -10px -10px 26px #ffffff;
        }
        .neu-sm {
            background: #eef2f7;
            box-shadow: 4px 4px 10px #cbd3df, -4px -4px 10px #ffffff;
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
            box-shadow: 5px 5px 14px #cbd3df, -5px -5px 14px #ffffff;
            transition: all 0.15s ease;
        }
        .neu-btn-dark:hover {
            background: #18181b;
            transform: translateY(-1px);
            box-shadow: 3px 3px 8px #cbd3df, -3px -3px 8px #ffffff;
        }
        .neu-btn-dark:active {
            transform: translateY(1px);
            box-shadow: inset 2px 2px 4px #000000;
        }

        .neu-btn-light {
            background: #eef2f7;
            color: #18181b;
            box-shadow: 3px 3px 8px #cbd3df, -3px -3px 8px #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.8);
            transition: all 0.15s ease;
        }
        .neu-btn-light:hover {
            background: #e4eaf3;
        }

        /* Dark Mode Monochrome */
        html.dark body {
            background-color: #090d14;
            color: #f4f4f5;
        }
        html.dark .neu-flat {
            background: #111622;
            box-shadow: 10px 10px 24px #05070a, -8px -8px 24px #1a2334;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        html.dark .neu-card {
            background: #111622;
            box-shadow: 8px 8px 20px #05070a, -8px -8px 20px #1a2334;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        html.dark .neu-card:hover {
            box-shadow: 10px 10px 26px #05070a, -10px -10px 26px #1a2334;
        }
        html.dark .neu-sm {
            background: #111622;
            box-shadow: 4px 4px 10px #05070a, -4px -4px 10px #1a2334;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        html.dark .neu-inset {
            background: #0b0e17;
            box-shadow: inset 3px 3px 6px #05070a, inset -3px -3px 6px #1a2334;
            border: 1px solid rgba(255, 255, 255, 0.04);
            color: #f4f4f5;
        }
        html.dark .neu-btn-dark {
            background: #f4f4f5;
            color: #09090b;
            box-shadow: 5px 5px 12px #05070a, -5px -5px 12px #1a2334;
        }
        html.dark .neu-btn-dark:hover {
            background: #ffffff;
            box-shadow: 3px 3px 8px #05070a, -3px -3px 8px #1a2334;
        }
        html.dark .neu-btn-light {
            background: #111622;
            color: #e4e4e7;
            box-shadow: 3px 3px 8px #05070a, -3px -3px 8px #1a2334;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        html.dark .neu-btn-light:hover {
            background: #161c2b;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 selection:bg-zinc-900 selection:text-white dark:selection:bg-white dark:selection:text-zinc-900 relative"
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
          }
      }">

    <!-- Monochrome Background Design -->
    <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden">
        <!-- Subtle Monochrome Dot Grid -->
        <div class="absolute inset-0 bg-grid-monochrome opacity-75 dark:opacity-40 [mask-image:radial-gradient(ellipse_at_center,black_45%,transparent_80%)]"></div>

        <!-- Ambient Monochrome Signal Rings (Subtle Broadcast Geometry) -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[850px] h-[850px] opacity-[0.035] dark:opacity-[0.055]">
            <svg viewBox="0 0 200 200" class="w-full h-full stroke-current text-zinc-900 dark:text-zinc-100" fill="none">
                <circle cx="100" cy="100" r="28" stroke-width="0.75" stroke-dasharray="2 2" />
                <circle cx="100" cy="100" r="54" stroke-width="0.75" />
                <circle cx="100" cy="100" r="80" stroke-width="0.75" stroke-dasharray="3 3" />
                <circle cx="100" cy="100" r="106" stroke-width="0.75" />
            </svg>
        </div>

        <!-- Corner Frame Accents (Minimalist Tech Grid) -->
        <div class="hidden sm:block absolute top-6 left-6 text-zinc-300 dark:text-zinc-800 text-[10px] font-mono tracking-widest uppercase">
            + NTFY // CORE
        </div>
        <div class="hidden sm:block absolute bottom-6 left-6 text-zinc-300 dark:text-zinc-800 text-[10px] font-mono tracking-widest uppercase">
            + SYS // OPERATIONAL
        </div>
        <div class="hidden sm:block absolute bottom-6 right-6 text-zinc-300 dark:text-zinc-800 text-[10px] font-mono tracking-widest uppercase">
            + PORTAL // UNIFIED
        </div>
    </div>

    <!-- Top Navigation Bar -->
    <header class="fixed top-0 left-0 right-0 p-5 sm:px-8 flex items-center justify-between z-40">
        <a href="{{ url('/') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-2xl neu-sm flex items-center justify-center shrink-0">
                <div class="w-7 h-7 rounded-xl bg-zinc-900 dark:bg-white flex items-center justify-center text-white dark:text-zinc-900">
                    <i data-lucide="radio" class="w-4 h-4"></i>
                </div>
            </div>
            <div>
                <span class="text-base font-black tracking-tight text-zinc-900 dark:text-white">
                    notify<span class="text-zinc-500 font-medium">.xyz</span>
                </span>
                <span class="block text-[10px] font-semibold uppercase tracking-widest text-zinc-500 dark:text-zinc-400">Announcement Engine</span>
            </div>
        </a>

        <!-- Top Right Actions -->
        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" class="hidden sm:inline-flex px-4 py-2 neu-btn-light rounded-xl text-xs font-bold items-center gap-1.5 cursor-pointer">
                <i data-lucide="log-in" class="w-3.5 h-3.5"></i> Sign In
            </a>
            <button type="button"
                    @click="toggleTheme()"
                    :title="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
                    class="p-2.5 rounded-2xl neu-sm text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white cursor-pointer transition-transform active:scale-95 flex items-center justify-center">
                <i x-show="darkMode" data-lucide="sun" class="w-4 h-4 text-zinc-200" x-cloak></i>
                <i x-show="!darkMode" data-lucide="moon" class="w-4 h-4 text-zinc-700"></i>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="w-full max-w-2xl my-16 px-4 text-center z-10">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full neu-sm text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-6">
            <span class="w-1.5 h-1.5 rounded-full bg-zinc-900 dark:bg-white animate-pulse"></span>
            Unified Announcement & Notification Engine
        </div>

        <h1 class="text-3xl sm:text-5xl font-black text-zinc-900 dark:text-white tracking-tight mb-3">
            notify<span class="text-zinc-500 font-medium">.xyz</span>
        </h1>
        <p class="text-sm sm:text-base font-medium text-zinc-600 dark:text-zinc-400 max-w-lg mx-auto mb-10">
            Real-time broadcast dispatches, categorized subscriber streams, and verified reader analytics.
        </p>

        <!-- Portals Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 max-w-xl mx-auto text-left mb-10">
            <!-- User Portal Card -->
            <a href="{{ route('user.dashboard') }}" class="neu-card rounded-3xl p-6 flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl neu-sm flex items-center justify-center mb-4">
                        <div class="w-8 h-8 rounded-xl bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 flex items-center justify-center">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white tracking-tight mb-1">User Portal</h2>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed font-medium">
                        Access your incoming announcements, personal alert stream, and read receipts.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-zinc-200/60 dark:border-zinc-800 flex items-center justify-between">
                    <span class="text-xs font-bold text-zinc-800 dark:text-zinc-200 group-hover:underline">Open Portal</span>
                    <i data-lucide="arrow-right" class="w-4 h-4 text-zinc-500 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

            <!-- Admin Portal Card -->
            <a href="{{ route('admin.dashboard') }}" class="neu-card rounded-3xl p-6 flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl neu-sm flex items-center justify-center mb-4">
                        <div class="w-8 h-8 rounded-xl bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 flex items-center justify-center">
                            <i data-lucide="shield" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white tracking-tight mb-1">Admin Panel</h2>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed font-medium">
                        Compose and dispatch instant broadcasts, manage users, and monitor analytics.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-zinc-200/60 dark:border-zinc-800 flex items-center justify-between">
                    <span class="text-xs font-bold text-zinc-800 dark:text-zinc-200 group-hover:underline">Open Admin</span>
                    <i data-lucide="arrow-right" class="w-4 h-4 text-zinc-500 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>
        </div>

        <div class="flex items-center justify-center gap-4 text-xs font-medium text-zinc-500 dark:text-zinc-400">
            <a href="{{ route('login') }}" class="hover:text-zinc-900 dark:hover:text-white underline transition-colors">Sign In</a>
            <span>•</span>
            <a href="{{ route('register') }}" class="hover:text-zinc-900 dark:hover:text-white underline transition-colors">Create Account</a>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) lucide.createIcons();
        });
    </script>
</body>
</html>
