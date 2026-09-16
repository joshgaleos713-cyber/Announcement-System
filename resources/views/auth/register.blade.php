<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>notify.xyz — Create Account</title>
    
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
        .neu-sm {
            background: #eef2f7;
            box-shadow: 4px 4px 10px #cbd3df, -4px -4px 10px #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.85);
        }
        .neu-inset {
            background: #e9eef5;
            box-shadow: inset 3px 3px 6px #cbd3df, inset -3px -3px 6px #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.4);
            transition: all 0.2s ease;
        }
        .neu-inset:focus-within {
            box-shadow: inset 2px 2px 4px #cbd3df, inset -2px -2px 4px #ffffff, 0 0 0 2px #18181b;
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
        .neu-btn-light:active {
            box-shadow: inset 2px 2px 4px #cbd3df, inset -2px -2px 4px #ffffff;
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
        html.dark .neu-inset:focus-within {
            box-shadow: inset 2px 2px 4px #05070a, inset -2px -2px 4px #1a2334, 0 0 0 2px #f4f4f5;
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
          step: {{ ($errors->has('password') || $errors->has('password_confirmation')) ? 2 : 1 }},
          name: '{{ old('name', '') }}',
          email: '{{ old('email', '') }}',
          password: '',
          passwordConfirmation: '',
          showPass: false,
          loading: false,
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

          goToStep2() {
              if (this.name.trim() && this.email.trim()) {
                  this.step = 2;
                  this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
              } else {
                  alert('Please enter both your name and email address.');
              }
          }
      }">

    <!-- Monochrome Background Design -->
    <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden">
        <!-- Subtle Monochrome Dot Grid -->
        <div class="absolute inset-0 bg-grid-monochrome opacity-75 dark:opacity-40 [mask-image:radial-gradient(ellipse_at_center,black_45%,transparent_80%)]"></div>

        <!-- Ambient Monochrome Signal Rings (Subtle Broadcast Geometry) -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[760px] h-[760px] opacity-[0.035] dark:opacity-[0.055]">
            <svg viewBox="0 0 200 200" class="w-full h-full stroke-current text-zinc-900 dark:text-zinc-100" fill="none">
                <circle cx="100" cy="100" r="28" stroke-width="0.75" stroke-dasharray="2 2" />
                <circle cx="100" cy="100" r="54" stroke-width="0.75" />
                <circle cx="100" cy="100" r="80" stroke-width="0.75" stroke-dasharray="3 3" />
                <circle cx="100" cy="100" r="106" stroke-width="0.75" />
            </svg>
        </div>

        <!-- Corner Frame Accents (Minimalist Tech Grid) -->
        <div class="hidden sm:block absolute top-6 left-6 text-zinc-300 dark:text-zinc-800 text-[10px] font-mono tracking-widest uppercase">
            + NTFY // 02
        </div>
        <div class="hidden sm:block absolute bottom-6 left-6 text-zinc-300 dark:text-zinc-800 text-[10px] font-mono tracking-widest uppercase">
            + REG // ACCOUNT
        </div>
        <div class="hidden sm:block absolute bottom-6 right-6 text-zinc-300 dark:text-zinc-800 text-[10px] font-mono tracking-widest uppercase">
            + ENCRYPT // 256
        </div>
    </div>

    <!-- Top Right Theme Toggle -->
    <div class="fixed top-5 right-5 z-50">
        <button type="button"
                @click="toggleTheme()"
                :title="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
                class="p-2.5 rounded-2xl neu-sm text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white cursor-pointer transition-transform active:scale-95 flex items-center justify-center">
            <i x-show="darkMode" data-lucide="sun" class="w-4 h-4 text-zinc-200" x-cloak></i>
            <i x-show="!darkMode" data-lucide="moon" class="w-4 h-4 text-zinc-700"></i>
        </button>
    </div>

    <!-- Main Content Container -->
    <div class="w-full max-w-md my-8 relative z-10">
        <!-- Logo & Header -->
        <div class="text-center mb-7">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl neu-sm mb-3.5 group cursor-default">
                <div class="w-9 h-9 rounded-xl bg-zinc-900 dark:bg-white flex items-center justify-center text-white dark:text-zinc-900 transition-transform group-hover:scale-105 duration-200">
                    <i data-lucide="radio" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="flex items-center justify-center gap-1.5">
                <h1 class="text-2xl font-black tracking-tight text-zinc-900 dark:text-white">notify<span class="text-zinc-500 font-medium">.xyz</span></h1>
            </div>
            <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 mt-1 uppercase tracking-widest">Get Started Today</p>
        </div>

        <!-- Auth Card -->
        <div class="neu-flat rounded-3xl p-7 sm:p-9 relative overflow-hidden">
            <!-- Subtle Top Accent Bar -->
            <div class="absolute top-0 left-8 right-8 h-1 bg-zinc-400/40 dark:bg-zinc-600/40"></div>

            <!-- Segmented Route Switcher (Monochrome) -->
            <div class="flex p-1 rounded-2xl neu-inset mb-6">
                <a href="{{ route('login') }}"
                   class="flex-1 py-2 text-center text-xs font-semibold rounded-xl transition-all duration-150 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white flex items-center justify-center gap-1.5">
                    Sign In
                </a>
                <a href="{{ route('register') }}"
                   class="flex-1 py-2 text-center text-xs font-bold rounded-xl transition-all duration-150 bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 shadow-sm flex items-center justify-center gap-1.5">
                    Create Account
                </a>
            </div>

            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white tracking-tight">Create Account</h2>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Stay updated with instant system dispatches</p>
                </div>
                <div class="flex items-center gap-1.5 neu-sm px-2.5 py-1 rounded-full text-[10px] font-bold text-zinc-700 dark:text-zinc-300">
                    Step <span x-text="step" class="font-black text-zinc-900 dark:text-white"></span> of 2
                </div>
            </div>

            <!-- Error Banner -->
            @if ($errors->any())
                <div class="mb-5 p-4 rounded-2xl neu-inset border border-zinc-400/40 dark:border-zinc-700">
                    <div class="flex items-start gap-2.5">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-zinc-700 dark:text-zinc-300 shrink-0 mt-0.5"></i>
                        <div class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <p class="text-xs text-zinc-800 dark:text-zinc-200 font-semibold">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Step Progress Indicator -->
            <div class="flex items-center gap-2 mb-6">
                <div class="flex-1 h-1.5 rounded-full transition-all duration-300"
                     :class="step >= 1 ? 'bg-zinc-900 dark:bg-white' : 'bg-zinc-300 dark:bg-zinc-800'"></div>
                <div class="flex-1 h-1.5 rounded-full transition-all duration-300"
                     :class="step >= 2 ? 'bg-zinc-900 dark:bg-white' : 'bg-zinc-300 dark:bg-zinc-800'"></div>
            </div>

            <form method="POST" action="{{ route('register') }}" @submit="loading = true" class="space-y-4">
                @csrf

                <!-- Step 1: Profile Details -->
                <div x-show="step === 1" class="space-y-4">
                    <div>
                        <label for="name" class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1.5">Full Name</label>
                        <div class="relative">
                            <i data-lucide="user" class="absolute left-4 top-3.5 text-zinc-400 w-4 h-4"></i>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   x-model="name"
                                   required
                                   autofocus
                                   placeholder="Alex Morgan"
                                   class="w-full pl-11 pr-4 py-3 neu-inset rounded-2xl text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none font-medium">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1.5">Email Address</label>
                        <div class="relative">
                            <i data-lucide="mail" class="absolute left-4 top-3.5 text-zinc-400 w-4 h-4"></i>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   x-model="email"
                                   required
                                   placeholder="alex@example.com"
                                   class="w-full pl-11 pr-4 py-3 neu-inset rounded-2xl text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none font-medium">
                        </div>
                    </div>

                    <button type="button"
                            @click="goToStep2()"
                            class="w-full py-3.5 neu-btn-dark rounded-2xl text-sm font-bold flex items-center justify-center gap-2 cursor-pointer mt-2">
                        <span>Next: Security</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- Step 2: Password & Confirmation -->
                <div x-show="step === 2" class="space-y-4" x-cloak>
                    <div>
                        <label for="password" class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1.5">Create Password</label>
                        <div class="relative">
                            <i data-lucide="lock" class="absolute left-4 top-3.5 text-zinc-400 w-4 h-4"></i>
                            <input :type="showPass ? 'text' : 'password'"
                                   id="password"
                                   name="password"
                                   x-model="password"
                                   required
                                   placeholder="At least 8 characters"
                                   class="w-full pl-11 pr-11 py-3 neu-inset rounded-2xl text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none font-medium">
                            <button type="button"
                                    @click="showPass = !showPass; $nextTick(() => { if (window.lucide) lucide.createIcons(); })"
                                    class="absolute right-3.5 top-3.5 text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 transition-colors p-0.5">
                                <i :data-lucide="showPass ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1.5">Confirm Password</label>
                        <div class="relative">
                            <i data-lucide="shield-check" class="absolute left-4 top-3.5 text-zinc-400 w-4 h-4"></i>
                            <input :type="showPass ? 'text' : 'password'"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   x-model="passwordConfirmation"
                                   required
                                   placeholder="Repeat password"
                                   class="w-full pl-11 pr-4 py-3 neu-inset rounded-2xl text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none font-medium">
                        </div>
                    </div>

                    <div class="flex gap-2.5 pt-2">
                        <button type="button"
                                @click="step = 1"
                                class="flex-1 py-3.5 neu-btn-light rounded-2xl text-xs font-bold flex items-center justify-center gap-1.5 cursor-pointer">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
                        </button>
                        <button type="submit"
                                :disabled="loading"
                                class="flex-[2] py-3.5 neu-btn-dark rounded-2xl text-xs font-bold flex items-center justify-center gap-2 cursor-pointer disabled:opacity-75">
                            <span x-show="!loading" class="flex items-center gap-1.5">
                                <span>Complete Registration</span>
                                <i data-lucide="check" class="w-4 h-4"></i>
                            </span>
                            <span x-show="loading" class="flex items-center gap-1.5" x-cloak>
                                <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                                <span>Registering...</span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Footer Link -->
            <div class="mt-6 text-center pt-4 border-t border-zinc-300/60 dark:border-zinc-800">
                <p class="text-xs text-zinc-500 dark:text-zinc-400 font-medium">
                    Already registered?
                    <a href="{{ route('login') }}" class="text-zinc-900 dark:text-white font-bold hover:underline transition-colors ml-1">Sign in →</a>
                </p>
            </div>
        </div>

        <div class="text-center mt-5">
            <p class="text-[11px] text-zinc-400 font-medium">notify.xyz · Tactile Announcement Console</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) lucide.createIcons();
        });
        document.addEventListener('click', () => {
            setTimeout(() => { if (window.lucide) lucide.createIcons(); }, 50);
        });
    </script>
</body>
</html>
