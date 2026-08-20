<script setup>
import AuthenticatedLayout from '../Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <AuthenticatedLayout title="Dashboard">
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="text-xl font-semibold text-slate-900">Dashboard</h1>
            <p class="mt-2 text-sm text-slate-600">Selamat datang. Gunakan menu untuk mengelola user dan role sesuai akses Anda.</p>
        </div>
    </AuthenticatedLayout>
</template>
