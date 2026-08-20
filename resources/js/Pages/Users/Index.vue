<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';

const props = defineProps({ users: Object, filters: Object });
const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');
const user = usePage().props.auth.user;
const can = permission => user.isSuperAdmin || user.permissions.includes(permission);
const filter = () => router.get('/users', { search: search.value || undefined, status: status.value || undefined }, { preserveState: true, replace: true });
const isCurrentUser = item => item.id === user.id;
const remove = item => { if (confirm(`Hapus user ${item.name}?`)) router.delete(`/users/${item.id}`); };
</script>
<template>
    <Head title="User Management" />
    <AuthenticatedLayout title="User Management"><div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm"><div class="flex flex-wrap items-center justify-between gap-3"><div><h1 class="text-xl font-semibold">User Management</h1><p class="mt-1 text-sm text-slate-600">Kelola akun, role, dan status user.</p></div><div class="flex gap-2"><a v-if="can('user.export.pdf')" href="/users/export/pdf" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">PDF</a><a v-if="can('user.export.excel')" href="/users/export/excel" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">Excel</a><Link v-if="can('user.create')" href="/users/create" class="rounded-lg bg-slate-900 px-4 py-2 text-sm text-white">Tambah user</Link></div></div><form class="mt-5 flex flex-wrap gap-2" @submit.prevent="filter"><input v-model="search" placeholder="Cari nama atau email" class="rounded-lg border-slate-300 text-sm" /><select v-model="status" class="rounded-lg border-slate-300 text-sm"><option value="">Semua status</option><option value="active">Active</option><option value="invitation_pending">Invitation pending</option><option value="disabled">Disabled</option></select><button class="rounded-lg border border-slate-300 px-3 py-2 text-sm">Filter</button></form><div class="mt-5 overflow-x-auto"><table class="min-w-full text-sm"><thead class="border-b text-left text-slate-500"><tr><th class="px-3 py-2">Name</th><th class="px-3 py-2">Email</th><th class="px-3 py-2">Role</th><th class="px-3 py-2">Status</th><th class="px-3 py-2">Created At</th><th class="px-3 py-2 text-right">Actions</th></tr></thead><tbody><tr v-for="item in users.data" :key="item.id" class="border-b"><td class="px-3 py-3 font-medium">{{ item.name }}</td><td class="px-3 py-3">{{ item.email }}</td><td class="px-3 py-3">{{ item.roles.join(', ') || '-' }}</td><td class="px-3 py-3">{{ item.status }}</td><td class="px-3 py-3">{{ item.created_at }}</td><td class="px-3 py-3 text-right"><template v-if="!isCurrentUser(item)"><Link v-if="can('user.update')" :href="`/users/${item.id}/edit`" class="mr-3 underline">Edit</Link><button v-if="can('user.delete')" class="text-red-700 underline" @click="remove(item)">Hapus</button></template><span v-else class="text-slate-400">-</span></td></tr><tr v-if="!users.data.length"><td colspan="6" class="px-3 py-8 text-center text-slate-500">Tidak ada user.</td></tr></tbody></table></div><div class="mt-4 flex justify-end gap-2 text-sm"><Link v-if="users.prev_page_url" :href="users.prev_page_url" class="underline">Sebelumnya</Link><Link v-if="users.next_page_url" :href="users.next_page_url" class="underline">Berikutnya</Link></div></div></AuthenticatedLayout>
</template>
