<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
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
            <form @submit.prevent="submit">
                <Card>
                    <template #header>
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
                    </template>

                    <div class="space-y-6">
                        <!-- Role Name Input -->
                        <TextInput
                            v-model="form.name"
                            label="Role Name"
                            required
                            placeholder="e.g. Editor, Manager, Auditor"
                            :error="form.errors.name"
                        />

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
                                            <CheckboxInput
                                                :model-value="form.permissions.includes(permission.name)"
                                                @update:model-value="toggle(permission.name)"
                                            >
                                                <span class="text-xs font-medium">{{ permission.label || permission.name }}</span>
                                            </CheckboxInput>
                                        </label>
                                    </div>
                                </section>
                            </div>

                            <p v-if="form.errors.permissions" class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                                {{ form.errors.permissions }}
                            </p>
                        </div>
                    </div>

                    <!-- Footer Actions Slot -->
                    <template #footer>
                        <div class="flex items-center justify-end gap-3 pt-2">
                            <SecondaryButton href="/roles">
                                Cancel
                            </SecondaryButton>
                            <PrimaryButton type="submit" :loading="form.processing">
                                {{ role ? 'Update Role' : 'Save Role' }}
                            </PrimaryButton>
                        </div>
                    </template>
                </Card>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
