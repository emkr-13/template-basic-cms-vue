<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
const form = useForm({ email: '' });
</script>
<template>
    <Head title="Lupa Password" />
    <main class="grid min-h-screen place-items-center bg-slate-50 p-4"><form class="w-full max-w-md rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="form.post('/forgot-password')"><h1 class="text-xl font-semibold">Reset password</h1><p class="mt-1 text-sm text-slate-600">Kami akan mengirim tautan reset password.</p><label class="mt-5 block text-sm font-medium">Email<input v-model="form.email" type="email" class="mt-1 w-full rounded-lg border-slate-300" /></label><p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p><button class="mt-5 w-full rounded-lg bg-slate-900 px-4 py-2 text-sm text-white">Kirim tautan</button><Link href="/login" class="mt-4 block text-center text-sm underline">Kembali ke login</Link></form></main>
</template>
