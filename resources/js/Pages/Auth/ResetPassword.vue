<script setup>
import { Head, useForm } from '@inertiajs/vue3';
const props = defineProps({ email: String, token: String });
const form = useForm({ email: props.email, token: props.token, password: '', password_confirmation: '' });
</script>
<template>
    <Head title="Atur Password" />
    <main class="grid min-h-screen place-items-center bg-slate-50 p-4"><form class="w-full max-w-md rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="form.post('/reset-password')"><h1 class="text-xl font-semibold">Atur password</h1><label class="mt-5 block text-sm font-medium">Email<input v-model="form.email" type="email" class="mt-1 w-full rounded-lg border-slate-300" /></label><label class="mt-4 block text-sm font-medium">Password<input v-model="form.password" type="password" class="mt-1 w-full rounded-lg border-slate-300" /></label><label class="mt-4 block text-sm font-medium">Konfirmasi password<input v-model="form.password_confirmation" type="password" class="mt-1 w-full rounded-lg border-slate-300" /></label><p v-if="form.errors.email || form.errors.password" class="mt-2 text-sm text-red-600">{{ form.errors.email || form.errors.password }}</p><button class="mt-5 w-full rounded-lg bg-slate-900 px-4 py-2 text-sm text-white">Simpan password</button></form></main>
</template>
