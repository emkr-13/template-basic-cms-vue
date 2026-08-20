<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import logoUrl from '../../../asset/icon.png';

const props = defineProps({
    email: String,
    token: String
});

const form = useForm({
    email: props.email,
    token: props.token,
    password: '',
    password_confirmation: ''
});

const isDark = ref(false);

onMounted(() => {
    isDark.value = localStorage.getItem('theme') === 'dark';
});

function toggleTheme() {
    isDark.value = !isDark.value;
    if (isDark.value) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
}

const submit = () => {
    form.post('/reset-password');
};
</script>

<template>
    <Head title="Set Password — CMS Template" />

    <main class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 flex flex-col justify-between items-center p-4 sm:p-6 selection:bg-indigo-500 selection:text-white transition-colors duration-200">
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-indigo-500/10 dark:bg-indigo-600/15 rounded-full blur-3xl"></div>
        </div>

        <div class="w-full max-w-md flex justify-end pt-2">
            <button
                type="button"
                class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm hover:bg-slate-100 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 transition-all"
                :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
                @click="toggleTheme"
            >
                <svg v-if="isDark" class="h-4 w-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg v-else class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>
        </div>

        <div class="w-full max-w-md my-auto">
            <div class="text-center mb-8">
                <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 to-blue-500 p-0.5 shadow-xl shadow-indigo-500/25 mb-4">
                    <img :src="logoUrl" alt="Logo" class="h-full w-full rounded-[14px] object-cover" />
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Set Account Password</h1>
                <p class="mt-2 text-xs sm:text-sm text-slate-600 dark:text-slate-400">Set a new password for your account</p>
            </div>

            <form class="rounded-2xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900/80 p-6 sm:p-8 shadow-xl dark:shadow-2xl backdrop-blur-md space-y-5 transition-colors" @submit.prevent="submit">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">Email Address</label>
                    <input
                        v-model="form.email"
                        type="email"
                        required
                        class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-950/80 dark:text-white transition-all"
                    />
                </div>

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

                <p v-if="form.errors.email || form.errors.password" class="text-xs font-medium text-red-600 dark:text-red-400">
                    {{ form.errors.email || form.errors.password }}
                </p>

                <button
                    :disabled="form.processing"
                    type="submit"
                    class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 disabled:opacity-50 transition-all flex items-center justify-center gap-2"
                >
                    <span>{{ form.processing ? 'Saving...' : 'Save Password' }}</span>
                </button>
            </form>
        </div>

        <footer class="py-4 text-center text-xs text-slate-500 dark:text-slate-400 font-mono">
            <span>Basic CMS Vue Template</span>
            <span class="mx-1">•</span>
            <span class="text-indigo-600 dark:text-indigo-400 font-semibold">by emkr-13</span>
        </footer>
    </main>
</template>
