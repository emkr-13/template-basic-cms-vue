<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
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
        <div class="max-w-2xl mx-auto">
            <form
                class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900/80 backdrop-blur-md space-y-6 transition-colors"
                @submit.prevent="submit"
            >
                <!-- Form Header -->
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-5">
                    <div>
                        <h1 class="text-xl font-bold text-slate-900 dark:text-white sm:text-2xl">
                            {{ user ? 'Edit User Account' : 'Add New User' }}
                        </h1>
                        <p class="mt-1 text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                            {{ user ? 'Update account details and role permissions.' : 'Create a new user account and set initial credentials.' }}
                        </p>
                    </div>
                    <Link href="/users" class="text-xs font-semibold text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition-colors">
                        &larr; Back
                    </Link>
                </div>

                <!-- Full Name -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">Full Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        required
                        placeholder="John Doe"
                        class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-950/80 dark:text-white dark:placeholder-slate-500 transition-all"
                    />
                    <p v-if="form.errors.name" class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">{{ form.errors.name }}</p>
                </div>

                <!-- Email Address -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">Email Address</label>
                    <input
                        v-model="form.email"
                        type="email"
                        required
                        placeholder="john@example.com"
                        class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-950/80 dark:text-white dark:placeholder-slate-500 transition-all"
                    />
                    <p v-if="form.errors.email" class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">{{ form.errors.email }}</p>
                </div>

                <!-- Role Selection -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">Assigned Role</label>
                    <select
                        v-model="form.role"
                        required
                        class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-950/80 dark:text-white transition-all"
                    >
                        <option value="" disabled>Select a role...</option>
                        <option v-for="roleItem in roles" :key="roleItem.id" :value="roleItem.name">
                            {{ roleItem.name }}
                        </option>
                    </select>
                    <p v-if="form.errors.role" class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">{{ form.errors.role }}</p>
                </div>

                <!-- Status Select (When Editing) -->
                <template v-if="user">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">Account Status</label>
                        <select
                            v-model="form.status"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-950/80 dark:text-white transition-all"
                        >
                            <option value="active">Active</option>
                            <option value="invitation_pending">Pending Invitation</option>
                            <option value="disabled">Disabled</option>
                        </select>
                    </div>
                </template>

                <!-- Credential Options (When Creating) -->
                <template v-else>
                    <div class="pt-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-3">Credential Delivery</label>
                        <div class="space-y-3">
                            <label class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 cursor-pointer hover:border-slate-300 dark:border-slate-800 dark:bg-slate-950/60 dark:hover:border-slate-700 transition-all">
                                <input
                                    v-model="form.credential_delivery"
                                    value="invitation"
                                    type="radio"
                                    class="mt-0.5 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 bg-white dark:border-slate-700 dark:bg-slate-900"
                                />
                                <div>
                                    <div class="text-xs font-semibold text-slate-900 dark:text-white">Send Email Invitation</div>
                                    <div class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">Send an email link allowing the user to create their own password.</div>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 cursor-pointer hover:border-slate-300 dark:border-slate-800 dark:bg-slate-950/60 dark:hover:border-slate-700 transition-all">
                                <input
                                    v-model="form.credential_delivery"
                                    value="temporary_password"
                                    type="radio"
                                    class="mt-0.5 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 bg-white dark:border-slate-700 dark:bg-slate-900"
                                />
                                <div>
                                    <div class="text-xs font-semibold text-slate-900 dark:text-white">Set Temporary Password</div>
                                    <div class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">Manually specify a temporary password without sending an invitation email.</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Password Inputs if temporary_password chosen -->
                    <template v-if="form.credential_delivery === 'temporary_password'">
                        <div class="space-y-4 pt-2">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">Temporary Password</label>
                                <input
                                    v-model="form.password"
                                    type="password"
                                    required
                                    placeholder="••••••••"
                                    class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-950/80 dark:text-white dark:placeholder-slate-500 transition-all"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">Confirm Password</label>
                                <input
                                    v-model="form.password_confirmation"
                                    type="password"
                                    required
                                    placeholder="••••••••"
                                    class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-950/80 dark:text-white dark:placeholder-slate-500 transition-all"
                                />
                            </div>
                            <p v-if="form.errors.password" class="text-xs font-medium text-red-600 dark:text-red-400">{{ form.errors.password }}</p>
                        </div>
                    </template>
                </template>

                <!-- Submit Button Bar -->
                <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-end gap-3">
                    <Link
                        href="/users"
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
                        <span>{{ form.processing ? 'Saving...' : (user ? 'Update User' : 'Save User') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
