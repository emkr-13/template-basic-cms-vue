<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Card from '../../Components/Card.vue';
import DangerButton from '../../Components/DangerButton.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import StatusBadge from '../../Components/StatusBadge.vue';
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
        <Card title="Role Management" subtitle="Configure permission scopes and operational roles for users.">
            <template #header>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 dark:text-white sm:text-2xl">Role Management</h1>
                    <p class="mt-1 text-xs sm:text-sm text-slate-600 dark:text-slate-400">Configure permission scopes and operational roles for users.</p>
                </div>

                <Link v-if="can('role.create')" href="/roles/create">
                    <PrimaryButton type="button">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Add Role</span>
                    </PrimaryButton>
                </Link>
            </template>

            <!-- Error Banner -->
            <div v-if="$page.props.errors?.role" class="mb-4 rounded-xl bg-red-50 border border-red-200 text-red-700 dark:bg-red-500/10 dark:border-red-500/20 dark:text-red-400 px-4 py-3 text-xs">
                {{ $page.props.errors.role }}
            </div>

            <!-- Desktop View: Table -->
            <div class="hidden overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 md:block">
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
                                <StatusBadge type="indigo">
                                    {{ role.permissions_count }} permissions
                                </StatusBadge>
                            </td>
                            <td class="px-4 py-3.5">
                                <StatusBadge type="default">
                                    {{ role.users_count }} users
                                </StatusBadge>
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
                                    <DangerButton v-if="can('role.delete')" @click="remove(role)">
                                        Delete
                                    </DangerButton>
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
            <div class="space-y-3 md:hidden">
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

                        <StatusBadge type="indigo">
                            {{ role.permissions_count }} permissions
                        </StatusBadge>
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
                            <DangerButton v-if="can('role.delete')" @click="remove(role)">
                                Delete
                            </DangerButton>
                        </div>
                    </div>
                </div>

                <div v-if="!roles.length" class="rounded-xl border border-slate-200 bg-slate-50 p-8 text-center text-xs text-slate-500 dark:border-slate-800 dark:bg-slate-950/60">
                    No custom roles defined yet.
                </div>
            </div>
        </Card>
    </AuthenticatedLayout>
</template>
