<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Card from '../../Components/Card.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import RadioCardGroup from '../../Components/RadioCardGroup.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import SelectInput from '../../Components/SelectInput.vue';
import TextInput from '../../Components/TextInput.vue';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    user: Object,
    roles: Array
});

const form = useForm({
    name: props.user?.name || '',
    email: props.user?.email || '',
    role: props.user?.role || '',
    status: props.user?.status || 'active',
    credential_delivery: 'invitation',
    password: '',
    password_confirmation: ''
});

const credentialOptions = [
    {
        value: 'invitation',
        title: 'Send Email Invitation',
        description: 'Send an email link allowing the user to create their own password securely.'
    },
    {
        value: 'temporary_password',
        title: 'Set Temporary Password',
        description: 'Manually specify a temporary password without waiting for email delivery.'
    }
];

const statusOptions = [
    { value: 'active', label: 'Active Account' },
    { value: 'invitation_pending', label: 'Pending Invitation' },
    { value: 'disabled', label: 'Disabled / Suspended' }
];

function generatePassword() {
    const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*';
    let pwd = '';
    for (let i = 0; i < 14; i++) {
        pwd += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    form.password = pwd;
    form.password_confirmation = pwd;
}

const submit = () => {
    if (props.user) {
        form.put(`/users/${props.user.id}`);
    } else {
        form.post('/users');
    }
};
</script>

<template>
    <Head :title="user ? 'Edit User — CMS Template' : 'Add User — CMS Template'" />

    <AuthenticatedLayout :title="user ? 'Edit User' : 'Add User'">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Bar -->
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-2xl">
                            {{ user ? 'Edit User Account' : 'Add New User' }}
                        </h1>
                        <span
                            :class="[
                                'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                user
                                    ? 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-500/20'
                                    : 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400 border border-indigo-500/20'
                            ]"
                        >
                            {{ user ? 'Mode: Edit' : 'Mode: Create' }}
                        </span>
                    </div>
                    <p class="mt-1 text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                        {{ user ? 'Update user account details, role permissions, and access status.' : 'Create a new user account, select initial credentials, and assign system role.' }}
                    </p>
                </div>

                <Link
                    href="/users"
                    class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 hover:text-slate-900 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white transition-colors"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Back to List</span>
                </Link>
            </div>

            <!-- Main Form Card -->
            <form @submit.prevent="submit" class="space-y-6">
                <Card>
                    <div class="space-y-8">
                        <!-- Section 1: Account Information -->
                        <div>
                            <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800/80 mb-5">
                                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-600/10 text-indigo-600 dark:text-indigo-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200">
                                    Account Information
                                </h2>
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <TextInput
                                    v-model="form.name"
                                    label="Full Name"
                                    required
                                    placeholder="e.g. John Doe"
                                    :error="form.errors.name"
                                />

                                <TextInput
                                    v-model="form.email"
                                    label="Email Address"
                                    type="email"
                                    required
                                    placeholder="e.g. john@example.com"
                                    :error="form.errors.email"
                                />
                            </div>
                        </div>

                        <!-- Section 2: Role & Access -->
                        <div>
                            <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800/80 mb-5">
                                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-600/10 text-indigo-600 dark:text-indigo-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200">
                                    Role & Permissions
                                </h2>
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <SelectInput
                                    v-model="form.role"
                                    label="Assigned Role (Optional)"
                                    placeholder="No Role (Without Role)"
                                    :options="roles.map(r => ({ value: r.name, label: r.name }))"
                                    :error="form.errors.role"
                                />

                                <template v-if="user">
                                    <SelectInput
                                        v-model="form.status"
                                        label="Account Status"
                                        :options="statusOptions"
                                        :error="form.errors.status"
                                    />
                                </template>
                            </div>
                        </div>

                        <!-- Section 3: Credentials & Security (When Creating) -->
                        <template v-if="!user">
                            <div>
                                <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800/80 mb-5">
                                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-600/10 text-indigo-600 dark:text-indigo-400">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                    </div>
                                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200">
                                        Credential Delivery & Security
                                    </h2>
                                </div>

                                <RadioCardGroup
                                    v-model="form.credential_delivery"
                                    label="Choose Provisioning Method"
                                    :options="credentialOptions"
                                />

                                <!-- Temporary Password Inputs with Eye Toggle -->
                                <Transition
                                    enter-active-class="transition ease-out duration-200"
                                    enter-from-class="opacity-0 -translate-y-2"
                                    enter-to-class="opacity-100 translate-y-0"
                                    leave-active-class="transition ease-in duration-150"
                                    leave-from-class="opacity-100 translate-y-0"
                                    leave-to-class="opacity-0 -translate-y-2"
                                >
                                    <div
                                        v-if="form.credential_delivery === 'temporary_password'"
                                        class="mt-6 rounded-2xl border border-indigo-100 bg-indigo-50/40 p-5 dark:border-indigo-900/30 dark:bg-indigo-950/20 space-y-4"
                                    >
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3 class="text-xs font-bold uppercase tracking-wider text-indigo-900 dark:text-indigo-300">
                                                    Specify Temporary Password
                                                </h3>
                                                <p class="text-xs text-slate-600 dark:text-slate-400">
                                                    Set an initial temporary password. The user will be prompted to update it upon first login.
                                                </p>
                                            </div>

                                            <button
                                                type="button"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-indigo-200 bg-white px-2.5 py-1 text-xs font-semibold text-indigo-700 shadow-sm hover:bg-indigo-50 dark:border-indigo-800 dark:bg-slate-900 dark:text-indigo-400 dark:hover:bg-indigo-950 transition-colors"
                                                @click="generatePassword"
                                            >
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                </svg>
                                                <span>Generate Password</span>
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 pt-1">
                                            <TextInput
                                                v-model="form.password"
                                                label="Temporary Password"
                                                type="password"
                                                required
                                                placeholder="••••••••••••"
                                                :error="form.errors.password"
                                            />

                                            <TextInput
                                                v-model="form.password_confirmation"
                                                label="Confirm Password"
                                                type="password"
                                                required
                                                placeholder="••••••••••••"
                                            />
                                        </div>
                                    </div>
                                </Transition>
                            </div>
                        </template>
                    </div>

                    <!-- Footer Action Buttons Slot -->
                    <template #footer>
                        <div class="flex items-center justify-end gap-3 pt-2">
                            <SecondaryButton href="/users">
                                Cancel
                            </SecondaryButton>
                            <PrimaryButton type="submit" :loading="form.processing">
                                {{ user ? 'Update User Account' : 'Save New User' }}
                            </PrimaryButton>
                        </div>
                    </template>
                </Card>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
