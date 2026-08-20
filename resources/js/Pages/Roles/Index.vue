<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';

defineProps({
    roles: Array
});

const user = usePage().props.auth.user;
const can = permission => user?.isSuperAdmin || user?.permissions?.includes(permission);

const remove = role => {
    if (confirm(`Are you sure you want to delete role "${role.name}"?`)) {
        router.delete(`/roles/${role.id}`);
    }
};
</script>

<template>
    <Head title="Role Management — CMS Template" />

    <AuthenticatedLayout title="Role Management">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/80 backdrop-blur-sm transition-colors">
            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-5 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <h1 class="text-xl font-bold text-slate-900 dark:text-white sm:text-2xl">Role Management</h1>
                    <p class="mt-1 text-xs sm:text-sm text-slate-600 dark:text-slate-400">Configure permission scopes and operational roles for users.</p>
                </div>

                <Link
                    v-if="can('role.create')"
                    href="/roles/create"
                    class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 transition-all self-start sm:self-auto"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Add Role</span>
                </Link>
            </div>

            <!-- Error Banner -->
            <div v-if="$page.props.errors?.role" class="mt-4 rounded-xl bg-red-50 border border-red-200 text-red-700 dark:bg-red-500/10 dark:border-red-500/20 dark:text-red-400 px-4 py-3 text-xs">
                {{ $page.props.errors.role }}
            </div>

            <!-- Desktop View: Table -->
            <div class="mt-6 hidden overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 md:block">
                <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-100/80 text-slate-700 dark:bg-slate-950/80 uppercase tracking-wider dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Role Name</th>
                            <th class="px-4 py-3 font-semibold">Permissions</th>
                            <th class="px-4 py-3 font-semibold">Assigned Users</th>
                            <th class="px-4 py-3 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800/80">
                        <tr v-for="role in roles" :key="role.id" class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="px-4 py-3.5 font-semibold text-slate-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 font-bold border border-blue-200 dark:border-blue-500/20">
                                        {{ role.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <span>{{ role.name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="rounded-full bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300 px-2.5 py-1 text-[11px] font-semibold border border-indigo-200 dark:border-indigo-500/20">
                                    {{ role.permissions_count }} permissions
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="rounded-full bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 px-2.5 py-1 text-[11px] font-medium border border-slate-200 dark:border-slate-700">
                                    {{ role.users_count }} users
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-right font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <Link
                                        v-if="can('role.update')"
                                        :href="`/roles/${role.id}/edit`"
                                        class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        v-if="can('role.delete')"
                                        class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                        @click="remove(role)"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!roles.length">
                            <td colspan="4" class="px-4 py-12 text-center text-slate-500">
                                No custom roles defined yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile View: Touch Cards (HP Friendly) -->
            <div class="mt-6 space-y-3 md:hidden">
                <div
                    v-for="role in roles"
                    :key="role.id"
                    class="rounded-xl border border-slate-200 bg-slate-50 p-4 space-y-3 dark:border-slate-800 dark:bg-slate-950/60"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 font-bold text-sm border border-blue-200 dark:border-blue-500/20">
                                {{ role.name.charAt(0).toUpperCase() }}
                            </div>
                            <div class="font-semibold text-slate-900 dark:text-white text-sm">{{ role.name }}</div>
                        </div>

                        <span class="rounded-full bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300 px-2.5 py-0.5 text-[10px] font-semibold border border-indigo-200 dark:border-indigo-500/20">
                            {{ role.permissions_count }} permissions
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-200 dark:border-slate-800/80">
                        <span class="text-slate-500 dark:text-slate-400">Assigned Users: <strong class="text-slate-900 dark:text-white">{{ role.users_count }}</strong></span>

                        <div class="flex items-center gap-3">
                            <Link
                                v-if="can('role.update')"
                                :href="`/roles/${role.id}/edit`"
                                class="rounded-lg bg-indigo-50 border border-indigo-200 px-3 py-1.5 text-xs font-semibold text-indigo-700 dark:bg-indigo-600/10 dark:border-indigo-500/20 dark:text-indigo-400 hover:bg-indigo-600 hover:text-white transition-all"
                            >
                                Edit
                            </Link>
                            <button
                                v-if="can('role.delete')"
                                class="rounded-lg bg-red-50 border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-700 dark:bg-red-600/10 dark:border-red-500/20 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all"
                                @click="remove(role)"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>

                <div v-if="!roles.length" class="rounded-xl border border-slate-200 bg-slate-50 p-8 text-center text-xs text-slate-500 dark:border-slate-800 dark:bg-slate-950/60">
                    No custom roles defined yet.
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
