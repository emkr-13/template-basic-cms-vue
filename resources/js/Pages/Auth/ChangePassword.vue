<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Card from '../../Components/Card.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import StatusBadge from '../../Components/StatusBadge.vue';
import TextInput from '../../Components/TextInput.vue';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const form = useForm({
    password: '',
    password_confirmation: ''
});

const submit = () => {
    form.put('/change-password');
};
</script>

<template>
    <Head title="Account Settings — CMS Template" />

    <AuthenticatedLayout title="Account Settings">
        <div class="max-w-2xl mx-auto space-y-6">
            <!-- Profile Info Card -->
            <Card>
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-indigo-600 font-bold text-white text-xl shadow-lg shadow-indigo-600/30">
                        {{ user?.name ? user.name.charAt(0).toUpperCase() : 'U' }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white truncate">{{ user?.name }}</h2>
                            <StatusBadge type="indigo">
                                {{ user?.isSuperAdmin ? 'Super Admin' : (user?.roles?.join(', ') || 'User') }}
                            </StatusBadge>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-mono mt-0.5">{{ user?.email }}</p>
                    </div>
                </div>
            </Card>

            <!-- Password Security Form Card -->
            <form @submit.prevent="submit">
                <Card>
                    <template #header>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Security & Password Settings</h2>
                            <p class="mt-1 text-xs text-slate-600 dark:text-slate-400">Update your account security password.</p>
                        </div>
                    </template>

                    <div class="space-y-5">
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
                    </div>

                    <template #footer>
                        <div class="flex items-center justify-end gap-3 pt-2">
                            <Link href="/" class="text-xs font-semibold text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition-colors">
                                Cancel
                            </Link>
                            <PrimaryButton type="submit" :loading="form.processing">
                                Save New Password
                            </PrimaryButton>
                        </div>
                    </template>
                </Card>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
