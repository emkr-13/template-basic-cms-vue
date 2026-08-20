<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Card from '../../Components/Card.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import RadioCardGroup from '../../Components/RadioCardGroup.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import SelectInput from '../../Components/SelectInput.vue';
import TextInput from '../../Components/TextInput.vue';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    user: Object,
    roles: Array
});

const form = useForm({
    name: props.user?.name || '',
    email: props.user?.email || '',
    role: props.user?.role || '',
    status: props.user?.status || 'active',
    credential_delivery: 'invitation',
    password: '',
    password_confirmation: ''
});

const credentialOptions = [
    {
        value: 'invitation',
        title: 'Send Email Invitation',
        description: 'Send an email link allowing the user to create their own password.'
    },
    {
        value: 'temporary_password',
        title: 'Set Temporary Password',
        description: 'Manually specify a temporary password without sending an invitation email.'
    }
];

const statusOptions = [
    { value: 'active', label: 'Active' },
    { value: 'invitation_pending', label: 'Pending Invitation' },
    { value: 'disabled', label: 'Disabled' }
];

const submit = () => {
    if (props.user) {
        form.put(`/users/${props.user.id}`);
    } else {
        form.post('/users');
    }
};
</script>

<template>
    <Head :title="user ? 'Edit User — CMS Template' : 'Add User — CMS Template'" />

    <AuthenticatedLayout :title="user ? 'Edit User' : 'Add User'">
        <div class="max-w-2xl mx-auto">
            <form @submit.prevent="submit">
                <Card>
                    <template #header>
                        <div>
                            <h1 class="text-xl font-bold text-slate-900 dark:text-white sm:text-2xl">
                                {{ user ? 'Edit User Account' : 'Add New User' }}
                            </h1>
                            <p class="mt-1 text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                                {{ user ? 'Update account details and role permissions.' : 'Create a new user account and set initial credentials.' }}
                            </p>
                        </div>
                        <Link href="/users" class="text-xs font-semibold text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition-colors">
                            &larr; Back
                        </Link>
                    </template>

                    <div class="space-y-6">
                        <!-- Full Name Input -->
                        <TextInput
                            v-model="form.name"
                            label="Full Name"
                            required
                            placeholder="John Doe"
                            :error="form.errors.name"
                        />

                        <!-- Email Input -->
                        <TextInput
                            v-model="form.email"
                            label="Email Address"
                            type="email"
                            required
                            placeholder="john@example.com"
                            :error="form.errors.email"
                        />

                        <!-- Role Select Input (Optional) -->
                        <SelectInput
                            v-model="form.role"
                            label="Assigned Role (Optional)"
                            placeholder="No Role (Without Role)"
                            :error="form.errors.role"
                        >
                            <option value="">No Role (Without Role)</option>
                            <option v-for="roleItem in roles" :key="roleItem.id" :value="roleItem.name">
                                {{ roleItem.name }}
                            </option>
                        </SelectInput>

                        <!-- Status Select Input (When Editing) -->
                        <template v-if="user">
                            <SelectInput
                                v-model="form.status"
                                label="Account Status"
                                :options="statusOptions"
                            />
                        </template>

                        <!-- Credential Options (When Creating) -->
                        <template v-else>
                            <RadioCardGroup
                                v-model="form.credential_delivery"
                                label="Credential Delivery"
                                :options="credentialOptions"
                            />

                            <!-- Password Inputs -->
                            <template v-if="form.credential_delivery === 'temporary_password'">
                                <div class="space-y-4 pt-2">
                                    <TextInput
                                        v-model="form.password"
                                        label="Temporary Password"
                                        type="password"
                                        required
                                        placeholder="••••••••"
                                        :error="form.errors.password"
                                    />

                                    <TextInput
                                        v-model="form.password_confirmation"
                                        label="Confirm Password"
                                        type="password"
                                        required
                                        placeholder="••••••••"
                                    />
                                </div>
                            </template>
                        </template>
                    </div>

                    <!-- Footer Action Bar Slot -->
                    <template #footer>
                        <div class="flex items-center justify-end gap-3 pt-2">
                            <SecondaryButton href="/users">
                                Cancel
                            </SecondaryButton>
                            <PrimaryButton type="submit" :loading="form.processing">
                                {{ user ? 'Update User' : 'Save User' }}
                            </PrimaryButton>
                        </div>
                    </template>
                </Card>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
