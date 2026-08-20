<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({ email: '', password: '', remember: false });
const submit = () => form.post('/login');
</script>

<template>
    <Head title="Login" />
    <main class="grid min-h-screen place-items-center bg-slate-50 p-4">
        <form class="w-full max-w-md rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <h1 class="text-xl font-semibold">Login</h1>
            <p class="mt-1 text-sm text-slate-600">Masuk untuk melanjutkan.</p>
            <label class="mt-5 block text-sm font-medium">Email<input v-model="form.email" type="email" autocomplete="email" class="mt-1 w-full rounded-lg border-slate-300" /></label>
            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
            <label class="mt-4 block text-sm font-medium">Password<input v-model="form.password" type="password" autocomplete="current-password" class="mt-1 w-full rounded-lg border-slate-300" /></label>
            <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</p>
            <label class="mt-4 flex items-center gap-2 text-sm"><input v-model="form.remember" type="checkbox" class="rounded border-slate-300" /> Ingat saya</label>
            <button :disabled="form.processing" class="mt-5 w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white disabled:opacity-50">Login</button>
            <Link href="/forgot-password" class="mt-4 block text-center text-sm text-slate-600 underline">Lupa password?</Link>
        </form>
    </main>
</template>
