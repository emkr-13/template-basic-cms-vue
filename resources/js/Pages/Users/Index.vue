<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Card from '../../Components/Card.vue';
import DangerButton from '../../Components/DangerButton.vue';
import Pagination from '../../Components/Pagination.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import SearchFilterBar from '../../Components/SearchFilterBar.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import StatusBadge from '../../Components/StatusBadge.vue';
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
</script>

<template>
    <Head title="User Management — CMS Template" />

    <AuthenticatedLayout title="User Management">
        <Card title="User Management" subtitle="Manage user accounts, assigned roles, and access status.">
            <template #header>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 dark:text-white sm:text-2xl">User Management</h1>
                    <p class="mt-1 text-xs sm:text-sm text-slate-600 dark:text-slate-400">Manage user accounts, assigned roles, and access status.</p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <SecondaryButton v-if="can('user.export.pdf')" href="/users/export/pdf">
                        <svg class="h-4 w-4 text-red-500 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span>PDF</span>
                    </SecondaryButton>

                    <SecondaryButton v-if="can('user.export.excel')" href="/users/export/excel">
                        <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Excel</span>
                    </SecondaryButton>

                    <Link v-if="can('user.create')" href="/users/create">
                        <PrimaryButton type="button">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Add User</span>
                        </PrimaryButton>
                    </Link>
                </div>
            </template>

            <!-- Filter Bar Component -->
            <SearchFilterBar
                v-model:search="search"
                v-model:status="status"
                search-placeholder="Search name or email..."
                @filter="filter"
                @clear="clearFilter"
            />

            <!-- Desktop Table -->
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
                                <StatusBadge type="indigo">
                                    {{ item.roles?.join(', ') || 'No Role' }}
                                </StatusBadge>
                            </td>
                            <td class="px-4 py-3.5">
                                <StatusBadge :status="item.status" />
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
                                        <DangerButton v-if="can('user.delete')" @click="remove(item)">
                                            Delete
                                        </DangerButton>
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

            <!-- Mobile Touch Cards -->
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

                        <StatusBadge :status="item.status" />
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
                        <DangerButton v-if="can('user.delete')" @click="remove(item)">
                            Delete
                        </DangerButton>
                    </div>
                </div>

                <div v-if="!users.data.length" class="rounded-xl border border-slate-200 bg-slate-50 p-8 text-center text-xs text-slate-500 dark:border-slate-800 dark:bg-slate-950/60">
                    No user accounts found matching criteria.
                </div>
            </div>

            <!-- Footer Slot Pagination -->
            <template #footer>
                <Pagination
                    :prev-page-url="users.prev_page_url"
                    :next-page-url="users.next_page_url"
                    :current-count="users.data.length"
                />
            </template>
        </Card>
    </AuthenticatedLayout>
</template>
