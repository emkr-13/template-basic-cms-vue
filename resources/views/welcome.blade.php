<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Basic CMS Template — Vue & Inertia by emkr-13</title>
    <link rel="icon" type="image/png" href="/asset/icon.png">

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        function toggleLandingTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 font-sans antialiased flex flex-col justify-between selection:bg-indigo-500 selection:text-white transition-colors duration-200">
    <!-- Background Decorator -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-500/10 dark:bg-indigo-600/20 rounded-full blur-3xl"></div>
        <div class="absolute top-1/3 -left-40 w-96 h-96 bg-blue-500/10 dark:bg-blue-600/15 rounded-full blur-3xl"></div>
    </div>

    <!-- Header Navigation -->
    <header class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-500 p-0.5 shadow-lg shadow-indigo-500/20">
                <img src="/asset/icon.png" alt="Logo" class="w-full h-full object-cover rounded-[10px]" />
            </div>
            <div>
                <span class="font-bold text-lg tracking-tight text-slate-900 dark:text-white">CMS Vue Template</span>
                <span class="block text-[10px] text-indigo-600 dark:text-indigo-400 font-mono uppercase tracking-widest font-semibold">by emkr-13</span>
            </div>
        </div>

        <nav class="flex items-center gap-3">
            <!-- Theme Toggle Button -->
            <button
                type="button"
                onclick="toggleLandingTheme()"
                class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm hover:bg-slate-100 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 transition-all"
                title="Toggle Light / Dark Mode"
            >
                <svg class="h-4 w-4 hidden dark:block text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg class="h-4 w-4 block dark:hidden text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>

            @auth
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium transition-all shadow-md shadow-indigo-600/30">
                    Dashboard
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-100 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800 text-sm font-medium transition-all shadow-sm">
                    Sign In
                </a>
            @endauth
        </nav>
    </header>

    <!-- Main Content -->
    <main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20 flex-1 flex flex-col justify-center">
        <div class="max-w-3xl mx-auto text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 dark:bg-indigo-500/10 dark:border-indigo-500/20 dark:text-indigo-300 text-xs font-medium mb-6 backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                Senior Vue 3 & Inertia CMS Starter
                <span class="text-slate-400">•</span>
                <span class="text-slate-500 dark:text-slate-400">v1.0 by emkr-13</span>
            </div>

            <!-- Title -->
            <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-tight">
                Modern Management Dashboard <span class="bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-600 dark:from-indigo-400 dark:via-blue-400 dark:to-cyan-400 bg-clip-text text-transparent">Reimagined</span>
            </h1>

            <p class="mt-6 text-base sm:text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto leading-relaxed">
                A high-performance responsive CMS boilerplate powered by Laravel, Inertia.js, Vue 3, and Tailwind CSS. Tailored for touch-friendly mobile responsiveness with native Light & Dark mode support.
            </p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                @auth
                    <a href="{{ url('/') }}" class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-xl shadow-indigo-600/30 transition-all hover:scale-[1.02]">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-xl shadow-indigo-600/30 transition-all hover:scale-[1.02]">
                        Sign In to CMS
                    </a>
                @endauth
            </div>
        </div>

        <!-- Feature Grid -->
        <div class="mt-16 sm:mt-24 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="p-6 rounded-2xl bg-white border border-slate-200 dark:bg-slate-900/60 dark:border-slate-800/80 backdrop-blur-sm hover:border-slate-300 dark:hover:border-slate-700 shadow-sm transition-all">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">User & Access Management</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Granular role-based permissions, pending invitation workflow, and full user profile state controls.</p>
            </div>

            <div class="p-6 rounded-2xl bg-white border border-slate-200 dark:bg-slate-900/60 dark:border-slate-800/80 backdrop-blur-sm hover:border-slate-300 dark:hover:border-slate-700 shadow-sm transition-all">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 border border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Fully Mobile Responsive</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Engineered with a mobile-first philosophy to work seamlessly from small smartphones up to ultra-wide displays.</p>
            </div>

            <div class="p-6 rounded-2xl bg-white border border-slate-200 dark:bg-slate-900/60 dark:border-slate-800/80 backdrop-blur-sm hover:border-slate-300 dark:hover:border-slate-700 shadow-sm transition-all sm:col-span-2 lg:col-span-1">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">PDF & Excel Data Exports</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Integrated instant export generation for user records formatted cleanly for reports and audit logs.</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full border-t border-slate-200/80 bg-white/60 dark:border-slate-800/60 dark:bg-slate-950/80 py-6 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500 dark:text-slate-400">
            <div class="flex items-center gap-2">
                <img src="/asset/icon.png" alt="Logo" class="w-5 h-5 rounded-md opacity-80" />
                <span>Basic CMS Vue Template &copy; {{ date('Y') }}</span>
            </div>
            <div class="flex items-center gap-1 font-mono">
                <span>Designed & Built by</span>
                <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-600 border border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20 font-semibold">emkr-13</span>
            </div>
        </div>
    </footer>
</body>
</html>
