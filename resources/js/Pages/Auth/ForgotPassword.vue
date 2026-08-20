<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import logoUrl from '../../../asset/icon.png';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import TextInput from '../../Components/TextInput.vue';
import ThemeToggle from '../../Components/ThemeToggle.vue';

defineProps({
    status: String
});

const form = useForm({ email: '' });

const submit = () => {
    form.post('/forgot-password');
};
</script>

<template>
    <Head title="Forgot Password — CMS Template" />

    <main class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 flex flex-col justify-between items-center p-4 sm:p-6 selection:bg-indigo-500 selection:text-white transition-colors duration-200">
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-indigo-500/10 dark:bg-indigo-600/15 rounded-full blur-3xl"></div>
        </div>

        <div class="w-full max-w-md flex justify-end pt-2">
            <ThemeToggle />
        </div>

        <div class="w-full max-w-md my-auto">
            <div class="text-center mb-8">
                <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 to-blue-500 p-0.5 shadow-xl shadow-indigo-500/25 mb-4">
                    <img :src="logoUrl" alt="Logo" class="h-full w-full rounded-[14px] object-cover" />
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Reset Password</h1>
                <p class="mt-2 text-xs sm:text-sm text-slate-600 dark:text-slate-400">Enter your email address to receive a password reset link</p>
            </div>

            <form class="rounded-2xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900/80 p-6 sm:p-8 shadow-xl dark:shadow-2xl backdrop-blur-md transition-colors" @submit.prevent="submit">
                <!-- Success Alert Notification -->
                <div
                    v-if="status || $page.props.flash?.success"
                    class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 dark:bg-emerald-500/10 dark:border-emerald-500/20 dark:text-emerald-400 p-4 text-xs flex items-start gap-3"
                >
                    <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <div class="font-bold mb-0.5">Reset Link Sent</div>
                        <span>{{ status || $page.props.flash?.success }}</span>
                    </div>
                </div>

                <TextInput
                    v-model="form.email"
                    label="Email Address"
                    type="email"
                    required
                    placeholder="you@example.com"
                    :error="form.errors.email"
                />

                <div class="mt-6">
                    <PrimaryButton
                        type="submit"
                        :loading="form.processing"
                        custom-class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 disabled:opacity-50 transition-all flex items-center justify-center gap-2"
                    >
                        <span>{{ form.processing ? 'Sending Link...' : 'Send Password Reset Link' }}</span>
                    </PrimaryButton>
                </div>

                <div class="mt-6 text-center">
                    <Link href="/login" class="text-xs text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition-colors">
                        &larr; Back to Sign In
                    </Link>
                </div>
            </form>
        </div>

        <footer class="py-4 text-center text-xs text-slate-500 dark:text-slate-400 font-mono">
            <span>Basic CMS Vue Template</span>
            <span class="mx-1">•</span>
            <span class="text-indigo-600 dark:text-indigo-400 font-semibold">by emkr-13</span>
        </footer>
    </main>
</template>
