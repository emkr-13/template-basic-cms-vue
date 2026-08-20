<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';

defineProps({ roles: Array });
const user = usePage().props.auth.user;
const can = permission => user.isSuperAdmin || user.permissions.includes(permission);
const remove = role => { if (confirm(`Hapus role ${role.name}?`)) router.delete(`/roles/${role.id}`); };
</script>
<template>
    <Head title="Role Management" />
    <AuthenticatedLayout title="Role Management">
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm"><div class="flex items-center justify-between"><div><h1 class="text-xl font-semibold">Role Management</h1><p class="mt-1 text-sm text-slate-600">Atur role dan permission operasional.</p></div><Link v-if="can('role.create')" href="/roles/create" class="rounded-lg bg-slate-900 px-4 py-2 text-sm text-white">Tambah role</Link></div><p v-if="$page.props.errors?.role" class="mt-4 text-sm text-red-600">{{ $page.props.errors.role }}</p><div class="mt-6 overflow-x-auto"><table class="min-w-full text-sm"><thead class="border-b text-left text-slate-500"><tr><th class="px-3 py-2">Role</th><th class="px-3 py-2">Permission</th><th class="px-3 py-2">User</th><th class="px-3 py-2 text-right">Aksi</th></tr></thead><tbody><tr v-for="role in roles" :key="role.id" class="border-b"><td class="px-3 py-3 font-medium">{{ role.name }}</td><td class="px-3 py-3">{{ role.permissions_count }}</td><td class="px-3 py-3">{{ role.users_count }}</td><td class="px-3 py-3 text-right"><Link v-if="can('role.update')" :href="`/roles/${role.id}/edit`" class="mr-3 text-slate-700 underline">Edit</Link><button v-if="can('role.delete')" class="text-red-700 underline" @click="remove(role)">Hapus</button></td></tr><tr v-if="!roles.length"><td colspan="4" class="px-3 py-8 text-center text-slate-500">Belum ada role yang dapat dikelola.</td></tr></tbody></table></div></div>
    </AuthenticatedLayout>
</template>
