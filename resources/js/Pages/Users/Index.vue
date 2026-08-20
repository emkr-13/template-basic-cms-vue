<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Card from '../../Components/Card.vue';
import DangerButton from '../../Components/DangerButton.vue';
import Pagination from '../../Components/Pagination.vue';
import PopoverModal from '../../Components/PopoverModal.vue';
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

const isRoleModalOpen = ref(false);
const selectedRole = ref(null);

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

function openRoleModal(role) {
    if (typeof role === 'string') {
        selectedRole.value = { name: role, permissions: [] };
    } else {
        selectedRole.value = role;
    }
    isRoleModalOpen.value = true;
}
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
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <template v-if="item.roles && item.roles.length">
                                        <button
                                            v-for="roleItem in item.roles"
                                            :key="typeof roleItem === 'string' ? roleItem : roleItem.id || roleItem.name"
                                            type="button"
                                            class="group cursor-pointer text-left"
                                            title="Click to view role details & permissions"
                                            @click="openRoleModal(roleItem)"
                                        >
                                            <StatusBadge type="indigo">
                                                <span class="font-medium group-hover:underline">
                                                    {{ typeof roleItem === 'string' ? roleItem : roleItem.name }}
                                                </span>
                                                <svg class="h-3 w-3 text-indigo-400 opacity-80 group-hover:opacity-100 transition-opacity ml-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </StatusBadge>
                                        </button>
                                    </template>
                                    <span v-else class="text-xs text-slate-400 dark:text-slate-500 font-mono">No Role</span>
                                </div>
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
                        <div class="flex items-center gap-1.5">
                            <span class="text-slate-500">Role: </span>
                            <template v-if="item.roles && item.roles.length">
                                <button
                                    v-for="roleItem in item.roles"
                                    :key="typeof roleItem === 'string' ? roleItem : roleItem.id || roleItem.name"
                                    type="button"
                                    class="group cursor-pointer"
                                    @click="openRoleModal(roleItem)"
                                >
                                    <StatusBadge type="indigo">
                                        <span class="font-medium group-hover:underline">
                                            {{ typeof roleItem === 'string' ? roleItem : roleItem.name }}
                                        </span>
                                    </StatusBadge>
                                </button>
                            </template>
                            <span v-else class="text-slate-400 font-mono">No Role</span>
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

        <!-- Reusable Role Details Popover Modal -->
        <PopoverModal
            v-model:show="isRoleModalOpen"
            :title="selectedRole ? `Role Details: ${selectedRole.name}` : 'Role Details'"
            subtitle="System access control and assigned permissions summary."
            max-width="max-w-lg"
        >
            <div v-if="selectedRole" class="space-y-5">
                <!-- Role Header Badge Card -->
                <div class="flex items-center gap-3.5 p-4 rounded-xl border border-indigo-100 bg-indigo-50/50 dark:border-indigo-900/30 dark:bg-indigo-950/20">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/30">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold text-slate-900 dark:text-white text-base capitalize">
                            {{ selectedRole.name }}
                        </div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">
                            {{ selectedRole.name === 'super_admin' ? 'Full System Administrator Access' : 'Custom Role Access' }}
                        </div>
                    </div>
                </div>

                <!-- Granted Permissions List -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            Assigned Permissions
                        </h4>
                        <span class="rounded-full bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5 text-[11px] font-semibold text-slate-600 dark:text-slate-300">
                            {{ selectedRole.name === 'super_admin' ? 'All Permissions' : `${selectedRole.permissions?.length || 0} permissions` }}
                        </span>
                    </div>

                    <div v-if="selectedRole.name === 'super_admin'" class="rounded-xl border border-amber-200 bg-amber-50/80 p-3.5 text-xs text-amber-900 dark:border-amber-900/30 dark:bg-amber-950/30 dark:text-amber-300">
                        <div class="font-semibold mb-1">Super Admin Role Privilege</div>
                        Super Admin automatically possesses all system permissions across user management, role management, export, and configuration modules.
                    </div>

                    <div v-else-if="selectedRole.permissions && selectedRole.permissions.length" class="flex flex-wrap gap-1.5 max-h-52 overflow-y-auto p-1">
                        <span
                            v-for="perm in selectedRole.permissions"
                            :key="perm"
                            class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-mono font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700/80"
                        >
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            <span>{{ perm }}</span>
                        </span>
                    </div>

                    <div v-else class="rounded-xl border border-slate-200 bg-slate-50 p-6 text-center text-xs text-slate-500 dark:border-slate-800 dark:bg-slate-950/60">
                        No specific permissions assigned to this role.
                    </div>
                </div>
            </div>

            <template #footer>
                <SecondaryButton type="button" @click="isRoleModalOpen = false">
                    Close
                </SecondaryButton>
            </template>
        </PopoverModal>
    </AuthenticatedLayout>
</template>
