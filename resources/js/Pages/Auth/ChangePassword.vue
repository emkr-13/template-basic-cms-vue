<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';

const form = useForm({
    password: '',
    password_confirmation: ''
});

const submit = () => {
    form.put('/change-password');
};
</script>

<template>
    <Head title="Change Password — CMS Template" />

    <AuthenticatedLayout title="Change Password">
        <div class="max-w-xl mx-auto">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900/80 backdrop-blur-md transition-colors">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-5">
                    <div>
                        <h1 class="text-xl font-bold text-slate-900 dark:text-white">Change Password</h1>
                        <p class="mt-1 text-xs text-slate-600 dark:text-slate-400">Update your security password before proceeding.</p>
                    </div>
                    <Link href="/" class="text-xs font-semibold text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition-colors">
                        Cancel
                    </Link>
                </div>

                <form class="mt-6 space-y-5" @submit.prevent="submit">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">New Password</label>
                        <input
                            v-model="form.password"
                            type="password"
                            required
                            placeholder="••••••••"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-950/80 dark:text-white dark:placeholder-slate-500 transition-all"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">Confirm New Password</label>
                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            required
                            placeholder="••••••••"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-950/80 dark:text-white dark:placeholder-slate-500 transition-all"
                        />
                    </div>

                    <p v-if="form.errors.password" class="text-xs font-medium text-red-600 dark:text-red-400">{{ form.errors.password }}</p>

                    <button
                        :disabled="form.processing"
                        type="submit"
                        class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 disabled:opacity-50 transition-all flex items-center justify-center gap-2"
                    >
                        <span>{{ form.processing ? 'Saving...' : 'Update Password' }}</span>
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
