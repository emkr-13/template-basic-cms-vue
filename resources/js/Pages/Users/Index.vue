<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    users: Object,
    filters: Object
});

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');

const user = usePage().props.auth.user;
const can = permission => user?.isSuperAdmin || user?.permissions?.includes(permission);

const filter = () => {
    router.get(
        '/users',
        { search: search.value || undefined, status: status.value || undefined },
        { preserveState: true, replace: true }
    );
};

const clearFilter = () => {
    search.value = '';
    status.value = '';
    filter();
};

const isCurrentUser = item => item.id === user.id;

const remove = item => {
    if (confirm(`Are you sure you want to delete user "${item.name}"?`)) {
        router.delete(`/users/${item.id}`);
    }
};

function getStatusBadge(statusVal) {
    if (statusVal === 'active') return 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20';
    if (statusVal === 'invitation_pending') return 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20';
    return 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700';
}

function formatStatusText(statusVal) {
    if (statusVal === 'active') return 'Active';
    if (statusVal === 'invitation_pending') return 'Pending Invitation';
    if (statusVal === 'disabled') return 'Disabled';
    return statusVal || 'Unknown';
}
</script>

<template>
    <Head title="User Management — CMS Template" />

    <AuthenticatedLayout title="User Management">
        <div class="rounded-2xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900/80 p-5 sm:p-6 shadow-sm backdrop-blur-sm transition-colors">
            <!-- Top Header & Action Buttons -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-5 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <h1 class="text-xl font-bold text-slate-900 dark:text-white sm:text-2xl">User Management</h1>
                    <p class="mt-1 text-xs sm:text-sm text-slate-600 dark:text-slate-400">Manage user accounts, assigned roles, and access status.</p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a
                        v-if="can('user.export.pdf')"
                        href="/users/export/pdf"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white transition-all shadow-sm"
                    >
                        <svg class="h-4 w-4 text-red-500 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span>PDF</span>
                    </a>

                    <a
                        v-if="can('user.export.excel')"
                        href="/users/export/excel"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white transition-all shadow-sm"
                    >
                        <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Excel</span>
                    </a>

                    <Link
                        v-if="can('user.create')"
                        href="/users/create"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 transition-all"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Add User</span>
                    </Link>
                </div>
            </div>

            <!-- Filter Toolbar -->
            <form class="mt-5 flex flex-col gap-2.5 sm:flex-row sm:items-center" @submit.prevent="filter">
                <div class="relative flex-1">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search name or email..."
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-950/80 dark:text-white dark:placeholder-slate-500 transition-all"
                    />
                </div>

                <div class="w-full sm:w-48">
                    <select
                        v-model="status"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-xs text-slate-900 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-950/80 dark:text-white transition-all"
                    >
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="invitation_pending">Pending Invitation</option>
                        <option value="disabled">Disabled</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        type="submit"
                        class="rounded-xl border border-slate-300 bg-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-800 hover:bg-slate-300 dark:border-slate-800 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700 transition-all"
                    >
                        Filter
                    </button>
                    <button
                        v-if="search || status"
                        type="button"
                        class="rounded-xl border border-slate-200 px-3 py-2.5 text-xs font-medium text-slate-500 hover:text-slate-900 dark:border-slate-800 dark:text-slate-400 dark:hover:text-white transition-all"
                        @click="clearFilter"
                    >
                        Clear
                    </button>
                </div>
            </form>

            <!-- Desktop View: Table -->
            <div class="mt-6 hidden overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 md:block">
                <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-100/80 text-slate-700 dark:bg-slate-950/80 uppercase tracking-wider dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Name</th>
                            <th class="px-4 py-3 font-semibold">Email</th>
                            <th class="px-4 py-3 font-semibold">Role</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold">Created At</th>
                            <th class="px-4 py-3 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800/80">
                        <tr v-for="item in users.data" :key="item.id" class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="px-4 py-3.5 font-medium text-slate-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    <div class="flex h-7 w-7 items-center justify-center rounded-full bg-slate-200 dark:bg-slate-800 text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                        {{ item.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <span>{{ item.name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-slate-500 dark:text-slate-400 font-mono">{{ item.email }}</td>
                            <td class="px-4 py-3.5">
                                <span class="rounded bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 px-2 py-0.5 text-[11px] font-medium border border-slate-200 dark:border-slate-700">
                                    {{ item.roles?.join(', ') || 'No Role' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span :class="['inline-flex items-center rounded-full border px-2.5 py-0.5 text-[10px] font-semibold', getStatusBadge(item.status)]">
                                    {{ formatStatusText(item.status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-slate-400 dark:text-slate-500">{{ item.created_at }}</td>
                            <td class="px-4 py-3.5 text-right font-medium">
                                <template v-if="!isCurrentUser(item)">
                                    <div class="flex items-center justify-end gap-3">
                                        <Link
                                            v-if="can('user.update')"
                                            :href="`/users/${item.id}/edit`"
                                            class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors"
                                        >
                                            Edit
                                        </Link>
                                        <button
                                            v-if="can('user.delete')"
                                            class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                            @click="remove(item)"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </template>
                                <span v-else class="text-xs text-slate-400 dark:text-slate-600 font-mono">Current User</span>
                            </td>
                        </tr>
                        <tr v-if="!users.data.length">
                            <td colspan="6" class="px-4 py-12 text-center text-slate-500">
                                No user accounts found matching criteria.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile View: Touch Cards (HP Friendly) -->
            <div class="mt-6 space-y-3 md:hidden">
                <div
                    v-for="item in users.data"
                    :key="item.id"
                    class="rounded-xl border border-slate-200 bg-slate-50 p-4 space-y-3 dark:border-slate-800 dark:bg-slate-950/60"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-200 dark:bg-slate-800 text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                {{ item.name.charAt(0).toUpperCase() }}
                            </div>
                            <div>
                                <div class="font-semibold text-slate-900 dark:text-white text-sm">{{ item.name }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 font-mono">{{ item.email }}</div>
                            </div>
                        </div>

                        <span :class="['inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-semibold', getStatusBadge(item.status)]">
                            {{ formatStatusText(item.status) }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-200 dark:border-slate-800/80">
                        <div>
                            <span class="text-slate-500">Role: </span>
                            <span class="text-slate-800 dark:text-slate-300 font-medium">{{ item.roles?.join(', ') || 'No Role' }}</span>
                        </div>
                        <div class="text-slate-400 dark:text-slate-500 text-[11px]">{{ item.created_at }}</div>
                    </div>

                    <div v-if="!isCurrentUser(item)" class="flex items-center justify-end gap-3 pt-2">
                        <Link
                            v-if="can('user.update')"
                            :href="`/users/${item.id}/edit`"
                            class="rounded-lg bg-indigo-50 border border-indigo-200 px-3 py-1.5 text-xs font-semibold text-indigo-700 dark:bg-indigo-600/10 dark:border-indigo-500/20 dark:text-indigo-400 hover:bg-indigo-600 hover:text-white transition-all"
                        >
                            Edit
                        </Link>
                        <button
                            v-if="can('user.delete')"
                            class="rounded-lg bg-red-50 border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-700 dark:bg-red-600/10 dark:border-red-500/20 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all"
                            @click="remove(item)"
                        >
                            Delete
                        </button>
                    </div>
                </div>

                <div v-if="!users.data.length" class="rounded-xl border border-slate-200 bg-slate-50 p-8 text-center text-xs text-slate-500 dark:border-slate-800 dark:bg-slate-950/60">
                    No user accounts found matching criteria.
                </div>
            </div>

            <!-- Pagination Bar -->
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 border-t border-slate-200 dark:border-slate-800/80 text-xs">
                <div class="text-slate-500 dark:text-slate-400">
                    Showing <span class="font-semibold text-slate-900 dark:text-white">{{ users.data.length }}</span> users
                </div>

                <div class="flex items-center gap-2">
                    <Link
                        v-if="users.prev_page_url"
                        :href="users.prev_page_url"
                        class="rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-1.5 font-medium text-slate-700 hover:bg-slate-100 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white transition-all"
                    >
                        &larr; Previous
                    </Link>
                    <Link
                        v-if="users.next_page_url"
                        :href="users.next_page_url"
                        class="rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-1.5 font-medium text-slate-700 hover:bg-slate-100 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white transition-all"
                    >
                        Next &rarr;
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
