<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({ title: { type: String, required: true } });

const page = usePage();
const user = computed(() => page.props.auth.user);
const can = permission => user.value?.isSuperAdmin || user.value?.permissions.includes(permission);

function logout() {
    router.post('/logout');
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-900">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6">
                <Link href="/" class="font-semibold">{{ $page.props.app?.name || 'Laravel' }}</Link>
                <div class="flex items-center gap-3 text-sm">
                    <span class="hidden text-slate-600 sm:block">{{ user?.name }}</span>
                    <button type="button" class="text-slate-600 hover:text-slate-950" @click="logout">Logout</button>
                </div>
            </div>
        </header>
        <div class="mx-auto grid max-w-7xl gap-6 px-4 py-6 sm:px-6 md:grid-cols-[200px_minmax(0,1fr)]">
            <aside class="rounded-xl border border-slate-200 bg-white p-3">
                <nav class="space-y-1 text-sm">
                    <Link href="/" class="block rounded-lg px-3 py-2 hover:bg-slate-100">Dashboard</Link>
                    <Link v-if="can('user.view')" href="/users" class="block rounded-lg px-3 py-2 hover:bg-slate-100">User Management</Link>
                    <Link v-if="can('role.view')" href="/roles" class="block rounded-lg px-3 py-2 hover:bg-slate-100">Role Management</Link>
                </nav>
            </aside>
            <main>
                <div v-if="$page.props.flash?.success" class="mb-4 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ $page.props.flash.success }}</div>
                <slot />
            </main>
        </div>
    </div>
</template>
