<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import logoUrl from '../../asset/icon.png';
import ThemeToggle from '../Components/ThemeToggle.vue';

defineProps({
    title: { type: String, required: true }
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const can = permission => user.value?.isSuperAdmin || user.value?.permissions?.includes(permission);

const isMobileMenuOpen = ref(false);

function toggleMobileMenu() {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
}

function closeMobileMenu() {
    isMobileMenuOpen.value = false;
}

function logout() {
    router.post('/logout');
}

const currentUrl = computed(() => page.url);

function isActive(path) {
    if (path === '/' && currentUrl.value === '/') return true;
    if (path !== '/' && currentUrl.value.startsWith(path)) return true;
    return false;
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 flex flex-col font-sans transition-colors duration-200 selection:bg-indigo-500 selection:text-white">
        <!-- Top Navigation Bar -->
        <header class="sticky top-0 z-40 w-full border-b border-slate-200/80 bg-white/90 dark:border-slate-800/80 dark:bg-slate-900/90 backdrop-blur-md transition-colors duration-200">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <!-- Left Brand & Mobile Toggle -->
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 md:hidden transition-colors"
                        aria-label="Toggle Navigation Menu"
                        @click="toggleMobileMenu"
                    >
                        <svg v-if="!isMobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg v-else class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <Link href="/" class="flex items-center gap-2.5 transition-opacity hover:opacity-90">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-500 p-0.5 shadow-md shadow-indigo-500/20">
                            <img :src="logoUrl" alt="CMS Logo" class="h-full w-full rounded-[9px] object-cover" />
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold text-base tracking-tight text-slate-900 dark:text-white">
                                {{ $page.props.app?.name || 'CMS Template' }}
                            </span>
                            <span class="text-[10px] font-mono tracking-wider text-indigo-600 dark:text-indigo-400 font-semibold">by emkr-13</span>
                        </div>
                    </Link>
                </div>

                <!-- Right User & Theme Controls -->
                <div class="flex items-center gap-2.5 sm:gap-3">
                    <!-- Theme Toggle Button Component -->
                    <ThemeToggle />

                    <!-- User Info Pill -->
                    <div class="hidden items-center gap-2.5 rounded-full border border-slate-200 bg-slate-100/80 px-3 py-1.5 text-xs text-slate-700 dark:border-slate-800 dark:bg-slate-900/80 dark:text-slate-300 sm:flex">
                        <div class="flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white">
                            {{ user?.name ? user.name.charAt(0).toUpperCase() : 'U' }}
                        </div>
                        <span class="font-medium text-slate-800 dark:text-slate-200">{{ user?.name }}</span>
                        <span v-if="user?.isSuperAdmin" class="rounded bg-indigo-500/10 px-1.5 py-0.5 text-[10px] font-semibold text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-300">
                            Super Admin
                        </span>
                    </div>

                    <Link
                        href="/change-password"
                        class="hidden rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100 hover:text-slate-900 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white transition-all sm:inline-flex"
                    >
                        Change Password
                    </Link>

                    <button
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-red-500/10 border border-red-500/20 px-3 py-1.5 text-xs font-medium text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all"
                        @click="logout"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Sign Out</span>
                    </button>
                </div>
            </div>
        </header>

        <!-- Mobile Drawer Navigation -->
        <div v-if="isMobileMenuOpen" class="fixed inset-0 z-50 md:hidden">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-sm transition-opacity" @click="closeMobileMenu"></div>

            <!-- Panel -->
            <div class="fixed inset-y-0 left-0 w-4/5 max-w-xs bg-white dark:bg-slate-900 p-6 shadow-2xl border-r border-slate-200 dark:border-slate-800 flex flex-col justify-between transition-colors">
                <div>
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-6 border-b border-slate-200 dark:border-slate-800">
                        <div class="flex items-center gap-2">
                            <img :src="logoUrl" alt="Logo" class="h-8 w-8 rounded-lg" />
                            <span class="font-bold text-slate-900 dark:text-white text-sm">CMS Menu</span>
                        </div>
                        <button type="button" class="text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white" @click="closeMobileMenu">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- User Details & Theme Toggle -->
                    <div class="py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                        <div>
                            <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ user?.name }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ user?.email }}</div>
                        </div>
                        <ThemeToggle />
                    </div>

                    <!-- Navigation Links -->
                    <nav class="mt-6 space-y-1.5">
                        <Link
                            href="/"
                            :class="[
                                'flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all',
                                isActive('/') ? 'bg-indigo-600 text-white font-semibold shadow-md shadow-indigo-600/30' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white'
                            ]"
                            @click="closeMobileMenu"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Dashboard</span>
                        </Link>

                        <Link
                            v-if="can('user.view')"
                            href="/users"
                            :class="[
                                'flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all',
                                isActive('/users') ? 'bg-indigo-600 text-white font-semibold shadow-md shadow-indigo-600/30' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white'
                            ]"
                            @click="closeMobileMenu"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>User Management</span>
                        </Link>

                        <Link
                            v-if="can('role.view')"
                            href="/roles"
                            :class="[
                                'flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all',
                                isActive('/roles') ? 'bg-indigo-600 text-white font-semibold shadow-md shadow-indigo-600/30' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white'
                            ]"
                            @click="closeMobileMenu"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span>Role Management</span>
                        </Link>

                        <Link
                            href="/change-password"
                            :class="[
                                'flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all',
                                isActive('/change-password') ? 'bg-indigo-600 text-white font-semibold shadow-md shadow-indigo-600/30' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white'
                            ]"
                            @click="closeMobileMenu"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 017.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            <span>Change Password</span>
                        </Link>
                    </nav>
                </div>

                <!-- Footer Credit -->
                <div class="border-t border-slate-200 dark:border-slate-800 pt-4">
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span>CMS Template</span>
                        <span class="rounded bg-indigo-500/10 px-2 py-0.5 font-mono text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 font-semibold">by emkr-13</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Body Grid -->
        <div class="mx-auto flex w-full max-w-7xl flex-1 gap-6 px-4 py-6 sm:px-6 lg:px-8">
            <!-- Desktop Sidebar -->
            <aside class="hidden w-64 shrink-0 md:block">
                <div class="sticky top-22 rounded-2xl border border-slate-200 bg-white/80 dark:border-slate-800/80 dark:bg-slate-900/60 p-4 backdrop-blur-sm shadow-sm transition-colors">
                    <div class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-400">
                        Navigation
                    </div>
                    <nav class="space-y-1.5">
                        <Link
                            href="/"
                            :class="[
                                'flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all',
                                isActive('/') ? 'bg-indigo-600 text-white font-semibold shadow-md shadow-indigo-600/30' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white'
                            ]"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Dashboard</span>
                        </Link>

                        <Link
                            v-if="can('user.view')"
                            href="/users"
                            :class="[
                                'flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all',
                                isActive('/users') ? 'bg-indigo-600 text-white font-semibold shadow-md shadow-indigo-600/30' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white'
                            ]"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>User Management</span>
                        </Link>

                        <Link
                            v-if="can('role.view')"
                            href="/roles"
                            :class="[
                                'flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all',
                                isActive('/roles') ? 'bg-indigo-600 text-white font-semibold shadow-md shadow-indigo-600/30' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white'
                            ]"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span>Role Management</span>
                        </Link>
                    </nav>

                    <div class="mt-8 border-t border-slate-200 dark:border-slate-800 pt-4">
                        <div class="rounded-xl bg-slate-100/80 dark:bg-slate-950/60 p-3 border border-slate-200 dark:border-slate-800/60 text-xs">
                            <div class="font-medium text-slate-700 dark:text-slate-300">Basic CMS Vue</div>
                            <div class="mt-1 flex items-center justify-between text-slate-500">
                                <span>Template</span>
                                <span class="font-mono text-indigo-600 dark:text-indigo-400 font-semibold">by emkr-13</span>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 min-w-0">
                <!-- Flash Notification Banner -->
                <div v-if="$page.props.flash?.success" class="mb-6 flex items-center justify-between rounded-xl bg-emerald-500/10 border border-emerald-500/20 px-4 py-3 text-sm font-medium text-emerald-700 dark:text-emerald-300 backdrop-blur-sm">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $page.props.flash.success }}</span>
                    </div>
                </div>

                <slot />
            </main>
        </div>

        <!-- Footer -->
        <footer class="mt-auto border-t border-slate-200/80 bg-white/60 text-slate-500 dark:border-slate-800/80 dark:bg-slate-900/40 dark:text-slate-400 py-4 text-center text-xs transition-colors">
            <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-2 px-4 sm:flex-row sm:px-6 lg:px-8">
                <div>Basic CMS Template — All rights reserved.</div>
                <div class="flex items-center gap-1.5 font-mono">
                    <span>Designed & Developed by</span>
                    <span class="rounded bg-indigo-500/10 px-2 py-0.5 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 font-semibold">emkr-13</span>
                </div>
            </div>
        </footer>
    </div>
</template>
