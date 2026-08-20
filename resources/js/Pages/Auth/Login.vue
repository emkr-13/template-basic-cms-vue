<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import logoUrl from '../../../asset/icon.png';
import CheckboxInput from '../../Components/CheckboxInput.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import TextInput from '../../Components/TextInput.vue';
import ThemeToggle from '../../Components/ThemeToggle.vue';

defineProps({
    status: String
});

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const submit = () => {
    form.post('/login');
};
</script>

<template>
    <Head title="Sign In — CMS Template" />

    <main class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 flex flex-col justify-between items-center p-4 sm:p-6 selection:bg-indigo-500 selection:text-white transition-colors duration-200">
        <!-- Background Orbs -->
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-indigo-500/10 dark:bg-indigo-600/15 rounded-full blur-3xl"></div>
        </div>

        <!-- Top Corner Theme Toggle -->
        <div class="w-full max-w-md flex justify-end pt-2">
            <ThemeToggle />
        </div>

        <div class="w-full max-w-md my-auto">
            <!-- Header Brand -->
            <div class="text-center mb-8">
                <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 to-blue-500 p-0.5 shadow-xl shadow-indigo-500/25 mb-4">
                    <img :src="logoUrl" alt="Logo" class="h-full w-full rounded-[14px] object-cover" />
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Welcome Back</h1>
                <p class="mt-2 text-xs sm:text-sm text-slate-600 dark:text-slate-400">Sign in to access your management dashboard</p>
            </div>

            <!-- Login Card -->
            <form class="rounded-2xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900/80 p-6 sm:p-8 shadow-xl dark:shadow-2xl backdrop-blur-md transition-colors" @submit.prevent="submit">
                <!-- Status / Flash Alert Notification -->
                <div
                    v-if="status || $page.props.flash?.success"
                    class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 dark:bg-emerald-500/10 dark:border-emerald-500/20 dark:text-emerald-400 p-4 text-xs flex items-start gap-3"
                >
                    <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <span>{{ status || $page.props.flash?.success }}</span>
                    </div>
                </div>

                <!-- Email Field -->
                <TextInput
                    v-model="form.email"
                    label="Email Address"
                    type="email"
                    required
                    autocomplete="email"
                    placeholder="admin@example.com"
                    :error="form.errors.email"
                />

                <!-- Password Field -->
                <div class="mt-5">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">Password *</label>
                        <Link href="/forgot-password" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline transition-colors">
                            Forgot password?
                        </Link>
                    </div>
                    <TextInput
                        v-model="form.password"
                        type="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        :error="form.errors.password"
                    />
                </div>

                <!-- Remember Me Checkbox -->
                <div class="mt-5 flex items-center">
                    <CheckboxInput v-model="form.remember" label="Remember me on this device" />
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <PrimaryButton
                        type="submit"
                        :loading="form.processing"
                        custom-class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 active:scale-[0.99] disabled:opacity-50 transition-all flex items-center justify-center gap-2"
                    >
                        <span>{{ form.processing ? 'Signing in...' : 'Sign In' }}</span>
                    </PrimaryButton>
                </div>
            </form>
        </div>

        <!-- Footer Signature -->
        <footer class="py-4 text-center text-xs text-slate-500 dark:text-slate-400 font-mono">
            <span>Basic CMS Vue Template</span>
            <span class="mx-1">•</span>
            <span class="text-indigo-600 dark:text-indigo-400 font-semibold">by emkr-13</span>
        </footer>
    </main>
</template>
