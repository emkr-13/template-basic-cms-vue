<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AuthenticatedLayout from '../Layouts/AuthenticatedLayout.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
const can = permission => user.value?.isSuperAdmin || user.value?.permissions?.includes(permission);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout title="Dashboard">
        <div class="space-y-6">
            <!-- Welcome Hero Card -->
            <div class="relative overflow-hidden rounded-2xl border border-slate-800 bg-gradient-to-r from-slate-900 via-indigo-950/90 to-slate-900 p-6 sm:p-8 shadow-xl text-white">
                <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-indigo-600/20 blur-2xl"></div>

                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full bg-indigo-500/20 px-3 py-1 text-xs font-medium text-indigo-300 border border-indigo-500/30 mb-3">
                            <span class="h-1.5 w-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                            System Operational
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                            Welcome back, {{ user?.name }}!
                        </h1>
                        <p class="mt-2 text-sm text-slate-300 max-w-xl leading-relaxed">
                            Manage user accounts, configure operational role permissions, and monitor system access from your responsive control panel.
                        </p>
                    </div>

                    <div class="shrink-0 flex items-center gap-2">
                        <span class="rounded-xl border border-slate-700 bg-slate-900/90 px-4 py-2.5 text-xs text-slate-200 font-mono">
                            Role: <span class="font-semibold text-indigo-400">{{ user?.isSuperAdmin ? 'Super Admin' : (user?.roles?.join(', ') || 'User') }}</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Action Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- User Control Card -->
                <div class="rounded-2xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900/60 p-5 shadow-sm backdrop-blur-sm hover:border-slate-300 dark:hover:border-slate-700 transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <span class="text-xs font-mono text-slate-400">Accounts</span>
                        </div>
                        <h2 class="mt-4 font-semibold text-lg text-slate-900 dark:text-white">User Management</h2>
                        <p class="mt-1 text-xs text-slate-600 dark:text-slate-400">View, invite, edit user accounts and assign access roles.</p>
                    </div>
                    <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                        <Link v-if="can('user.view')" href="/users" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                            Manage Users &rarr;
                        </Link>
                        <span v-else class="text-xs text-slate-400">Restricted</span>
                    </div>
                </div>

                <!-- Role Control Card -->
                <div class="rounded-2xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900/60 p-5 shadow-sm backdrop-blur-sm hover:border-slate-300 dark:hover:border-slate-700 transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <span class="text-xs font-mono text-slate-400">Security</span>
                        </div>
                        <h2 class="mt-4 font-semibold text-lg text-slate-900 dark:text-white">Role Management</h2>
                        <p class="mt-1 text-xs text-slate-600 dark:text-slate-400">Configure permission sets and assign operational access levels.</p>
                    </div>
                    <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                        <Link v-if="can('role.view')" href="/roles" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                            Configure Roles &rarr;
                        </Link>
                        <span v-else class="text-xs text-slate-400">Restricted</span>
                    </div>
                </div>

                <!-- Template Attribution Card -->
                <div class="rounded-2xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900/60 p-5 shadow-sm backdrop-blur-sm hover:border-slate-300 dark:hover:border-slate-700 transition-all sm:col-span-2 lg:col-span-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span class="text-xs font-mono text-indigo-600 dark:text-indigo-400 font-semibold">by emkr-13</span>
                        </div>
                        <h2 class="mt-4 font-semibold text-lg text-slate-900 dark:text-white">Template Engine</h2>
                        <p class="mt-1 text-xs text-slate-600 dark:text-slate-400">Full Vue 3, Inertia.js & Laravel CMS stack pre-built for fast deployment.</p>
                    </div>
                    <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                        <Link href="/change-password" class="text-xs font-semibold text-purple-600 dark:text-purple-400 hover:underline">
                            Security Settings &rarr;
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
