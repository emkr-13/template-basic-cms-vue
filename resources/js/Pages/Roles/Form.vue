<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    role: Object,
    permissionGroups: Array
});

const form = useForm({
    name: props.role?.name || '',
    permissions: props.role?.permissions || []
});

const toggle = name => {
    form.permissions = form.permissions.includes(name)
        ? form.permissions.filter(permission => permission !== name)
        : [...form.permissions, name];
};

const toggleGroup = group => {
    const groupPermNames = group.permissions.map(p => p.name);
    const allSelected = groupPermNames.every(p => form.permissions.includes(p));

    if (allSelected) {
        form.permissions = form.permissions.filter(p => !groupPermNames.includes(p));
    } else {
        const set = new Set([...form.permissions, ...groupPermNames]);
        form.permissions = Array.from(set);
    }
};

const isGroupAllSelected = group => {
    if (!group.permissions?.length) return false;
    return group.permissions.every(p => form.permissions.includes(p.name));
};

const submit = () => {
    if (props.role) {
        form.put(`/roles/${props.role.id}`);
    } else {
        form.post('/roles');
    }
};
</script>

<template>
    <Head :title="role ? 'Edit Role — CMS Template' : 'Add Role — CMS Template'" />

    <AuthenticatedLayout :title="role ? 'Edit Role' : 'Add Role'">
        <div class="max-w-3xl mx-auto">
            <form
                class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900/80 backdrop-blur-md space-y-6 transition-colors"
                @submit.prevent="submit"
            >
                <!-- Form Header -->
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-5">
                    <div>
                        <h1 class="text-xl font-bold text-slate-900 dark:text-white sm:text-2xl">
                            {{ role ? 'Edit Role Permissions' : 'Create New Role' }}
                        </h1>
                        <p class="mt-1 text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                            {{ role ? 'Configure permission grants for this role.' : 'Define a new role and assign operational access scopes.' }}
                        </p>
                    </div>
                    <Link href="/roles" class="text-xs font-semibold text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition-colors">
                        &larr; Back
                    </Link>
                </div>

                <!-- Role Name Input -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">Role Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        required
                        placeholder="e.g. Editor, Manager, Auditor"
                        class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-950/80 dark:text-white dark:placeholder-slate-500 transition-all"
                    />
                    <p v-if="form.errors.name" class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">{{ form.errors.name }}</p>
                </div>

                <!-- Permission Matrix -->
                <div class="pt-2">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            Permission Matrix
                        </label>
                        <span class="text-xs font-mono text-indigo-600 dark:text-indigo-400 font-semibold">
                            {{ form.permissions.length }} selected
                        </span>
                    </div>

                    <div class="space-y-4">
                        <section
                            v-for="group in permissionGroups"
                            :key="group.name"
                            class="rounded-xl border border-slate-200 bg-slate-50 p-4 sm:p-5 dark:border-slate-800 dark:bg-slate-950/60"
                        >
                            <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800/80">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200">
                                    {{ group.name }}
                                </h3>
                                <button
                                    type="button"
                                    class="text-[11px] font-semibold text-indigo-600 dark:text-indigo-400 hover:underline transition-colors"
                                    @click="toggleGroup(group)"
                                >
                                    {{ isGroupAllSelected(group) ? 'Deselect Group' : 'Select All' }}
                                </button>
                            </div>

                            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                <label
                                    v-for="permission in group.permissions"
                                    :key="permission.name"
                                    :class="[
                                        'flex items-center gap-3 rounded-lg border p-3 cursor-pointer transition-all',
                                        form.permissions.includes(permission.name)
                                            ? 'bg-indigo-50 border-indigo-300 text-indigo-900 dark:bg-indigo-600/10 dark:border-indigo-500/30 dark:text-white font-medium'
                                            : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300 dark:bg-slate-900/40 dark:border-slate-800/60 dark:text-slate-400 dark:hover:border-slate-700 dark:hover:text-slate-300'
                                    ]"
                                >
                                    <input
                                        :checked="form.permissions.includes(permission.name)"
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-slate-300 bg-white text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950"
                                        @change="toggle(permission.name)"
                                    />
                                    <span class="text-xs font-medium">{{ permission.label || permission.name }}</span>
                                </label>
                            </div>
                        </section>
                    </div>

                    <p v-if="form.errors.permissions" class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ form.errors.permissions }}
                    </p>
                </div>

                <!-- Submit Action Bar -->
                <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-end gap-3">
                    <Link
                        href="/roles"
                        class="rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-600 hover:text-slate-900 dark:border-slate-800 dark:text-slate-400 dark:hover:text-white transition-all"
                    >
                        Cancel
                    </Link>
                    <button
                        :disabled="form.processing"
                        type="submit"
                        class="rounded-xl bg-indigo-600 px-5 py-2.5 text-xs font-semibold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 disabled:opacity-50 transition-all flex items-center gap-2"
                    >
                        <svg v-if="form.processing" class="h-4 w-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>{{ form.processing ? 'Saving...' : (role ? 'Update Role' : 'Save Role') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
