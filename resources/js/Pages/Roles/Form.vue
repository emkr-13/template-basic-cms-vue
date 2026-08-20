<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import Card from '../../Components/Card.vue';
import CheckboxInput from '../../Components/CheckboxInput.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import TextInput from '../../Components/TextInput.vue';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    role: Object,
    permissionGroups: Array
});

const form = useForm({
    name: props.role?.name || '',
    permissions: props.role?.permissions || []
});

const allPermissionNames = computed(() => {
    return (props.permissionGroups || []).flatMap(group => group.permissions.map(p => p.name));
});

const isAllGlobalSelected = computed(() => {
    if (!allPermissionNames.value.length) return false;
    return allPermissionNames.value.every(name => form.permissions.includes(name));
});

function toggleAllGlobal() {
    if (isAllGlobalSelected.value) {
        form.permissions = [];
    } else {
        form.permissions = [...allPermissionNames.value];
    }
}

const toggle = name => {
    const isCurrentlySelected = form.permissions.includes(name);
    const modulePrefix = name.split('.')[0];
    const viewPermissionName = `${modulePrefix}.view`;

    if (isCurrentlySelected) {
        // Deselecting permission
        if (name === viewPermissionName) {
            // Unchecking '.view' automatically unchecks all action permissions in this module
            form.permissions = form.permissions.filter(p => !p.startsWith(`${modulePrefix}.`));
        } else {
            // Unchecking action permission
            form.permissions = form.permissions.filter(permission => permission !== name);
        }
    } else {
        // Selecting permission
        if (name !== viewPermissionName) {
            // Checking an action permission automatically auto-selects '.view' for this module
            const set = new Set([...form.permissions, name, viewPermissionName]);
            form.permissions = Array.from(set);
        } else {
            // Checking '.view' permission
            form.permissions = [...form.permissions, name];
        }
    }
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

const getGroupSelectedCount = group => {
    if (!group.permissions?.length) return 0;
    return group.permissions.filter(p => form.permissions.includes(p.name)).length;
};

// Check if a view permission is required because other actions in the same module are selected
function isViewRequiredByActions(permissionName) {
    if (!permissionName.endsWith('.view')) return false;
    const modulePrefix = permissionName.split('.')[0];
    return form.permissions.some(p => p.startsWith(`${modulePrefix}.`) && p !== permissionName);
}

function getPermissionBadge(permissionName) {
    if (permissionName.endsWith('.view')) {
        return { label: 'READ', class: 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20' };
    }
    if (permissionName.endsWith('.create')) {
        return { label: 'CREATE', class: 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20' };
    }
    if (permissionName.endsWith('.update')) {
        return { label: 'UPDATE', class: 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20' };
    }
    if (permissionName.endsWith('.delete')) {
        return { label: 'DELETE', class: 'bg-red-50 text-red-700 border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20' };
    }
    return { label: 'EXPORT', class: 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-500/10 dark:text-purple-400 dark:border-purple-500/20' };
}

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
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Bar -->
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-2xl">
                            {{ role ? 'Edit Role Permissions' : 'Create New Role' }}
                        </h1>
                        <span
                            :class="[
                                'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                role
                                    ? 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-500/20'
                                    : 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400 border border-indigo-500/20'
                            ]"
                        >
                            {{ role ? 'Mode: Edit' : 'Mode: Create' }}
                        </span>
                    </div>
                    <p class="mt-1 text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                        {{ role ? 'Configure operational permission grants and access scopes for this role.' : 'Define a new custom role title and select specific module access grants.' }}
                    </p>
                </div>

                <Link
                    href="/roles"
                    class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 hover:text-slate-900 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white transition-colors"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Back to List</span>
                </Link>
            </div>

            <!-- Form Body Card -->
            <form @submit.prevent="submit" class="space-y-6">
                <Card>
                    <div class="space-y-8">
                        <!-- Section 1: Role Configuration -->
                        <div>
                            <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800/80 mb-5">
                                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-600/10 text-indigo-600 dark:text-indigo-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200">
                                    Role Identification
                                </h2>
                            </div>

                            <TextInput
                                v-model="form.name"
                                label="Role Name"
                                required
                                placeholder="e.g. Editor, Manager, Auditor, Operations"
                                :error="form.errors.name"
                            />
                        </div>

                        <!-- Section 2: Permission Matrix -->
                        <div>
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800/80 mb-5">
                                <div class="flex items-center gap-2">
                                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-600/10 text-indigo-600 dark:text-indigo-400">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200">
                                        Permission Access Matrix
                                    </h2>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="hidden sm:flex items-center gap-2">
                                        <div class="w-24 bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                                            <div
                                                class="bg-indigo-600 h-full transition-all duration-300"
                                                :style="{ width: `${allPermissionNames.length ? (form.permissions.length / allPermissionNames.length) * 100 : 0}%` }"
                                            ></div>
                                        </div>
                                        <span class="text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400">
                                            {{ form.permissions.length }} / {{ allPermissionNames.length }}
                                        </span>
                                    </div>

                                    <button
                                        type="button"
                                        class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors"
                                        @click="toggleAllGlobal"
                                    >
                                        {{ isAllGlobalSelected ? 'Deselect All' : 'Select All Permissions' }}
                                    </button>
                                </div>
                            </div>

                            <!-- Smart Dependency Banner Notice -->
                            <div class="mb-5 rounded-xl border border-indigo-100 bg-indigo-50/60 p-3 text-xs text-indigo-900 dark:border-indigo-900/30 dark:bg-indigo-950/30 dark:text-indigo-300 flex items-center gap-2.5">
                                <svg class="h-4 w-4 shrink-0 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>
                                    <strong>Smart Permission Dependency:</strong> Selecting Create, Update, Delete, or Export automatically grants View access. Unchecking View will disable all action permissions for that module.
                                </span>
                            </div>

                            <!-- Modules Grid -->
                            <div class="space-y-6">
                                <section
                                    v-for="group in permissionGroups"
                                    :key="group.name"
                                    class="rounded-2xl border border-slate-200 bg-slate-50/50 p-5 dark:border-slate-800 dark:bg-slate-950/40 space-y-4"
                                >
                                    <div class="flex items-center justify-between pb-3 border-b border-slate-200/80 dark:border-slate-800/80">
                                        <div class="flex items-center gap-2.5">
                                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">
                                                {{ group.name }}
                                            </h3>
                                            <span class="rounded-full bg-slate-200/80 px-2 py-0.5 text-[10px] font-mono font-bold text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                                {{ getGroupSelectedCount(group) }}/{{ group.permissions.length }} active
                                            </span>
                                        </div>

                                        <button
                                            type="button"
                                            class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline transition-colors"
                                            @click="toggleGroup(group)"
                                        >
                                            {{ isGroupAllSelected(group) ? 'Deselect Group' : 'Select Group' }}
                                        </button>
                                    </div>

                                    <!-- Permission Cards Grid -->
                                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                        <label
                                            v-for="permission in group.permissions"
                                            :key="permission.name"
                                            :class="[
                                                'relative flex items-start gap-3 rounded-xl border p-4 cursor-pointer transition-all duration-200 select-none',
                                                form.permissions.includes(permission.name)
                                                    ? 'bg-indigo-50/80 border-indigo-300 dark:bg-indigo-600/10 dark:border-indigo-500/40 shadow-sm'
                                                    : 'bg-white border-slate-200 hover:border-slate-300 dark:bg-slate-900/60 dark:border-slate-800 dark:hover:border-slate-700'
                                            ]"
                                        >
                                            <div class="pt-0.5 shrink-0">
                                                <CheckboxInput
                                                    :model-value="form.permissions.includes(permission.name)"
                                                    @update:model-value="toggle(permission.name)"
                                                />
                                            </div>

                                            <div class="min-w-0 flex-1 space-y-1">
                                                <div class="flex items-center justify-between gap-2">
                                                    <div class="flex items-center gap-1.5">
                                                        <span
                                                            :class="[
                                                                'text-xs font-bold',
                                                                form.permissions.includes(permission.name)
                                                                    ? 'text-slate-900 dark:text-white'
                                                                    : 'text-slate-700 dark:text-slate-300'
                                                            ]"
                                                        >
                                                            {{ permission.label || permission.name }}
                                                        </span>

                                                        <span
                                                            v-if="isViewRequiredByActions(permission.name)"
                                                            class="inline-flex items-center rounded bg-indigo-100 px-1.5 py-0.5 text-[9px] font-bold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300"
                                                        >
                                                            Auto-Required
                                                        </span>
                                                    </div>

                                                    <span
                                                        :class="[
                                                            'inline-flex items-center rounded-md border px-1.5 py-0.5 text-[9px] font-mono font-bold tracking-wider uppercase shrink-0',
                                                            getPermissionBadge(permission.name).class
                                                        ]"
                                                    >
                                                        {{ getPermissionBadge(permission.name).label }}
                                                    </span>
                                                </div>

                                                <p class="text-[11px] leading-relaxed text-slate-500 dark:text-slate-400">
                                                    {{ permission.description || `Grants access to ${permission.name} operations.` }}
                                                </p>
                                            </div>
                                        </label>
                                    </div>
                                </section>
                            </div>

                            <p v-if="form.errors.permissions" class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                                {{ form.errors.permissions }}
                            </p>
                        </div>
                    </div>

                    <!-- Footer Action Buttons Slot -->
                    <template #footer>
                        <div class="flex items-center justify-end gap-3 pt-2">
                            <SecondaryButton href="/roles">
                                Cancel
                            </SecondaryButton>
                            <PrimaryButton type="submit" :loading="form.processing">
                                {{ role ? 'Update Role Grants' : 'Save New Role' }}
                            </PrimaryButton>
                        </div>
                    </template>
                </Card>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
