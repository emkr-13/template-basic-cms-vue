<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';

const props = defineProps({ role: Object, permissionGroups: Array });
const form = useForm({ name: props.role?.name || '', permissions: props.role?.permissions || [] });
const toggle = name => form.permissions = form.permissions.includes(name) ? form.permissions.filter(permission => permission !== name) : [...form.permissions, name];
const submit = () => props.role ? form.put(`/roles/${props.role.id}`) : form.post('/roles');
</script>
<template>
    <Head :title="role ? 'Edit Role' : 'Tambah Role'" />
    <AuthenticatedLayout :title="role ? 'Edit Role' : 'Tambah Role'"><form class="max-w-3xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit"><div class="flex items-center justify-between"><h1 class="text-xl font-semibold">{{ role ? 'Edit Role' : 'Tambah Role' }}</h1><Link href="/roles" class="text-sm underline">Kembali</Link></div><label class="mt-6 block text-sm font-medium">Nama role<input v-model="form.name" class="mt-1 w-full rounded-lg border-slate-300" /></label><p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p><div class="mt-6"><h2 class="font-medium">Permission</h2><div class="mt-3 space-y-4"><section v-for="group in permissionGroups" :key="group.name" class="rounded-lg border border-slate-200 p-4"><h3 class="text-sm font-medium">{{ group.name }}</h3><label v-for="permission in group.permissions" :key="permission.name" class="mt-3 flex items-center gap-2 text-sm"><input :checked="form.permissions.includes(permission.name)" type="checkbox" class="rounded border-slate-300" @change="toggle(permission.name)" />{{ permission.label }}</label></section></div><p v-if="form.errors.permissions" class="mt-1 text-sm text-red-600">{{ form.errors.permissions }}</p></div><button :disabled="form.processing" class="mt-6 rounded-lg bg-slate-900 px-4 py-2 text-sm text-white">Simpan role</button></form></AuthenticatedLayout>
</template>
