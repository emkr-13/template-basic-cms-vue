<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import Card from '../../Components/Card.vue';
import Pagination from '../../Components/Pagination.vue';
import SearchFilterBar from '../../Components/SearchFilterBar.vue';

const props = defineProps({
    logs: { type: Object, required: true },
    filters: { type: Object, default: () => ({ search: '', action: '' }) },
    stats: { type: Object, required: true }
});

const search = ref(props.filters.search || '');

watch(search, (val) => {
    router.get(
        '/audit-logs',
        { search: val },
        { preserveState: true, replace: true }
    );
});

function getActionBadgeClass(action) {
    if (action.startsWith('auth.')) return 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800';
    if (action.includes('created')) return 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800';
    if (action.includes('updated')) return 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800';
    if (action.includes('deleted') || action.includes('revoked')) return 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800';
    return 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700';
}
</script>

<template>
    <Head title="Super Admin Activity Monitor" />

    <AuthenticatedLayout title="Activity Monitor">
        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">System Activity Monitor</h1>
                        <span class="px-2.5 py-0.5 text-[10px] font-bold font-mono tracking-wider uppercase rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                            Super Admin Only
                        </span>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Audit trail real-time pencatatan aktivitas, login, dan perubahan data di dalam sistem.</p>
                </div>
            </div>

            <!-- Dashboard pulse summary cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Total Logs -->
                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Audit Log</div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ stats.total_logs }}</div>
                    </div>
                    <div class="h-11 w-11 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>

                <!-- Card 2: Today Logs -->
                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Aktivitas Hari Ini</div>
                        <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ stats.today_logs }}</div>
                    </div>
                    <div class="h-11 w-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Card 3: Unique Users -->
                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">User Aktif</div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ stats.unique_users }}</div>
                    </div>
                    <div class="h-11 w-11 rounded-xl bg-blue-50 dark:bg-blue-950/50 flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Card 4: Auth Events -->
                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Event Autentikasi</div>
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400 mt-1">{{ stats.auth_logs }}</div>
                    </div>
                    <div class="h-11 w-11 rounded-xl bg-purple-50 dark:bg-purple-950/50 flex items-center justify-center text-purple-600 dark:text-purple-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Main Log Table -->
            <Card>
                <div class="space-y-4">
                    <SearchFilterBar
                        v-model:search="search"
                        placeholder="Cari kata kunci aksi, deskripsi, user, atau IP address..."
                    />

                    <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:bg-slate-900/80 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                                <tr>
                                    <th class="px-4 py-3">Pengguna</th>
                                    <th class="px-4 py-3">Event / Action</th>
                                    <th class="px-4 py-3">Deskripsi Aktivitas</th>
                                    <th class="px-4 py-3">IP Address</th>
                                    <th class="px-4 py-3 text-right">Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 font-medium">
                                <tr v-for="log in logs.data" :key="log.id" class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div v-if="log.user" class="flex items-center gap-2.5">
                                            <img :src="log.user.avatar_url" :alt="log.user.name" class="h-7 w-7 rounded-full object-cover shrink-0 ring-1 ring-slate-200 dark:ring-slate-700" />
                                            <div>
                                                <div class="font-semibold text-slate-900 dark:text-white text-xs">{{ log.user.name }}</div>
                                                <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">{{ log.user.email }}</div>
                                            </div>
                                        </div>
                                        <div v-else class="text-xs text-slate-400 italic">Sistem / Anonym</div>
                                    </td>

                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span :class="['inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold font-mono border', getActionBadgeClass(log.action)]">
                                            {{ log.action }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-300 text-xs">
                                        {{ log.description }}
                                    </td>

                                    <td class="px-4 py-3 whitespace-nowrap text-xs font-mono text-slate-500 dark:text-slate-400">
                                        {{ log.ip_address || 'N/A' }}
                                    </td>

                                    <td class="px-4 py-3 whitespace-nowrap text-right text-xs">
                                        <div class="font-semibold text-slate-900 dark:text-white">{{ log.created_at }}</div>
                                        <div class="text-[10px] text-slate-500 dark:text-slate-400">{{ log.created_at_human }}</div>
                                    </td>
                                </tr>

                                <tr v-if="logs.data.length === 0">
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400 text-sm">
                                        Tidak ada data log aktivitas yang ditemukan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <Pagination :links="logs.links" />
                </div>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
