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
    <div class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 font-sans transition-colors duration-200 selection:bg-indigo-500 selection:text-white">
        <!-- 1. Fixed Full-Height Desktop Sidebar -->
        <aside class="hidden md:fixed md:inset-y-0 md:left-0 md:z-30 md:flex md:w-64 md:flex-col border-r border-slate-200/80 bg-white dark:border-slate-800/80 dark:bg-slate-900 transition-colors duration-200">
            <!-- Sidebar Header Brand -->
            <div class="flex h-16 items-center gap-3 border-b border-slate-200/80 dark:border-slate-800/80 px-6">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-500 p-0.5 shadow-md shadow-indigo-500/20">
                    <img :src="logoUrl" alt="CMS Logo" class="h-full w-full rounded-[9px] object-cover" />
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-base tracking-tight text-slate-900 dark:text-white leading-none">
                        {{ $page.props.app?.name || 'CMS Template' }}
                    </span>
                    <span class="mt-1 text-[10px] font-mono tracking-wider text-indigo-600 dark:text-indigo-400 font-semibold">by emkr-13</span>
                </div>
            </div>

            <!-- Sidebar Navigation Links (Grouped) -->
            <div class="flex-1 overflow-y-auto p-4 space-y-6">
                <!-- Overview Group -->
                <div>
                    <div class="mb-2 px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        Overview
                    </div>
                    <nav class="space-y-1">
                        <Link
                            href="/"
                            :class="[
                                'relative flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all',
                                isActive('/')
                                    ? 'bg-indigo-50 text-indigo-700 font-semibold dark:bg-indigo-600/15 dark:text-indigo-400 before:absolute before:left-0 before:top-2 before:bottom-2 before:w-1 before:bg-indigo-600 before:rounded-r'
                                    : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-white'
                            ]"
                        >
                            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Dashboard</span>
                        </Link>
                    </nav>
                </div>

                <!-- Administration Group -->
                <div v-if="can('user.view') || can('role.view')">
                    <div class="mb-2 px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        Administration
                    </div>
                    <nav class="space-y-1">
                        <Link
                            v-if="can('user.view')"
                            href="/users"
                            :class="[
                                'relative flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all',
                                isActive('/users')
                                    ? 'bg-indigo-50 text-indigo-700 font-semibold dark:bg-indigo-600/15 dark:text-indigo-400 before:absolute before:left-0 before:top-2 before:bottom-2 before:w-1 before:bg-indigo-600 before:rounded-r'
                                    : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-white'
                            ]"
                        >
                            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>User Management</span>
                        </Link>

                        <Link
                            v-if="can('role.view')"
                            href="/roles"
                            :class="[
                                'relative flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all',
                                isActive('/roles')
                                    ? 'bg-indigo-50 text-indigo-700 font-semibold dark:bg-indigo-600/15 dark:text-indigo-400 before:absolute before:left-0 before:top-2 before:bottom-2 before:w-1 before:bg-indigo-600 before:rounded-r'
                                    : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-white'
                            ]"
                        >
                            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span>Role Management</span>
                        </Link>
                    </nav>
                </div>
            </div>

            <!-- Pinned Bottom Sidebar Account & Settings Card -->
            <div class="border-t border-slate-200/80 bg-slate-50/50 p-4 dark:border-slate-800/80 dark:bg-slate-950/40">
                <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-3">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-600 font-bold text-white text-sm">
                            {{ user?.name ? user.name.charAt(0).toUpperCase() : 'U' }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="truncate text-xs font-semibold text-slate-900 dark:text-white">{{ user?.name }}</div>
                            <div class="truncate text-[10px] text-slate-500 dark:text-slate-400 font-mono">{{ user?.email }}</div>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-100 dark:border-slate-800 flex flex-col gap-1.5">
                        <Link
                            href="/change-password"
                            :class="[
                                'flex items-center justify-between rounded-lg px-2.5 py-1.5 text-xs font-medium transition-colors',
                                isActive('/change-password') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-600/10 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800'
                            ]"
                        >
                            <span class="flex items-center gap-2">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Account Settings</span>
                            </span>
                        </Link>

                        <button
                            type="button"
                            class="flex w-full items-center justify-between rounded-lg px-2.5 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/40 transition-colors"
                            @click="logout"
                        >
                            <span class="flex items-center gap-2">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Sign Out</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <!-- 2. Mobile Drawer Navigation -->
        <div v-if="isMobileMenuOpen" class="fixed inset-0 z-50 md:hidden">
            <div class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-sm transition-opacity" @click="closeMobileMenu"></div>
            <div class="fixed inset-y-0 left-0 w-4/5 max-w-xs bg-white dark:bg-slate-900 p-6 shadow-2xl border-r border-slate-200 dark:border-slate-800 flex flex-col justify-between transition-colors">
                <div>
                    <div class="flex items-center justify-between pb-6 border-b border-slate-200 dark:border-slate-800">
                        <div class="flex items-center gap-2.5">
                            <img :src="logoUrl" alt="Logo" class="h-8 w-8 rounded-lg" />
                            <span class="font-bold text-slate-900 dark:text-white text-sm">CMS Menu</span>
                        </div>
                        <button type="button" class="text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white" @click="closeMobileMenu">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Account Settings</span>
                        </Link>
                    </nav>
                </div>

                <div class="border-t border-slate-200 dark:border-slate-800 pt-4">
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-xl bg-red-50 px-3.5 py-2.5 text-xs font-semibold text-red-600 dark:bg-red-950/40 dark:text-red-400"
                        @click="logout"
                    >
                        <span>Sign Out</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- 3. Main Workspace Area (Offset by Sidebar Width md:pl-64) -->
        <div class="md:pl-64 flex flex-col min-h-screen">
            <!-- Top Header Bar -->
            <header class="sticky top-0 z-20 flex h-16 w-full items-center justify-between border-b border-slate-200/80 bg-white/90 dark:border-slate-800/80 dark:bg-slate-900/90 backdrop-blur-md px-4 sm:px-6 lg:px-8 transition-colors duration-200">
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white md:hidden"
                        @click="toggleMobileMenu"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <h1 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">
                        {{ title }}
                    </h1>
                </div>

                <div class="flex items-center gap-3">
                    <ThemeToggle />
                </div>
            </header>

            <!-- Main Content Container -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
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

            <!-- Bottom Footer -->
            <footer class="mt-auto border-t border-slate-200/80 bg-white/60 text-slate-500 dark:border-slate-800/80 dark:bg-slate-900/40 dark:text-slate-400 py-4 text-center text-xs transition-colors">
                <div class="mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 px-4 sm:px-6 lg:px-8">
                    <div>Basic CMS Template — All rights reserved.</div>
                    <div class="flex items-center gap-1.5 font-mono">
                        <span>Designed & Developed by</span>
                        <span class="rounded bg-indigo-500/10 px-2 py-0.5 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 font-semibold">emkr-13</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>
