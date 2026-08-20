<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Card from '../../Components/Card.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import TextInput from '../../Components/TextInput.vue';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';

const form = useForm({
    password: '',
    password_confirmation: ''
});

const submit = () => {
    form.put('/change-password');
};
</script>

<template>
    <Head title="Change Password — CMS Template" />

    <AuthenticatedLayout title="Change Password">
        <div class="max-w-xl mx-auto">
            <Card>
                <template #header>
                    <div>
                        <h1 class="text-xl font-bold text-slate-900 dark:text-white">Change Password</h1>
                        <p class="mt-1 text-xs text-slate-600 dark:text-slate-400">Update your security password before proceeding.</p>
                    </div>
                    <Link href="/" class="text-xs font-semibold text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition-colors">
                        Cancel
                    </Link>
                </template>

                <form class="space-y-5" @submit.prevent="submit">
                    <TextInput
                        v-model="form.password"
                        label="New Password"
                        type="password"
                        required
                        placeholder="••••••••"
                        :error="form.errors.password"
                    />

                    <TextInput
                        v-model="form.password_confirmation"
                        label="Confirm New Password"
                        type="password"
                        required
                        placeholder="••••••••"
                    />

                    <div class="pt-2">
                        <PrimaryButton
                            type="submit"
                            :loading="form.processing"
                            custom-class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 disabled:opacity-50 transition-all flex items-center justify-center gap-2"
                        >
                            <span>{{ form.processing ? 'Saving...' : 'Update Password' }}</span>
                        </PrimaryButton>
                    </div>
                </form>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
