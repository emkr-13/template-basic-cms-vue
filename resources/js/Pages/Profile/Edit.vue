<script setup>
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import Card from '../../Components/Card.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import TextInput from '../../Components/TextInput.vue';

const props = defineProps({
    user: { type: Object, required: true }
});

const avatarInput = ref(null);
const avatarPreview = ref(null);

const form = useForm({
    _method: 'PUT',
    name: props.user.name,
    email: props.user.email,
    avatar: null
});

function handleAvatarChange(e) {
    const file = e.target.files[0];
    if (file) {
        form.avatar = file;
        avatarPreview.value = URL.createObjectURL(file);
    }
}

function submitProfile() {
    form.post('/profile', {
        preserveScroll: true,
        onSuccess: () => {
            avatarPreview.value = null;
            if (avatarInput.value) avatarInput.value.value = '';
        }
    });
}

function removeAvatar() {
    if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
        router.delete('/profile/avatar', {
            preserveScroll: true,
            onSuccess: () => {
                avatarPreview.value = null;
                if (avatarInput.value) avatarInput.value.value = '';
            }
        });
    }
}
</script>

<template>
    <Head title="Profil Saya" />

    <AuthenticatedLayout title="Profil Saya">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Flash Banner -->
            <div v-if="$page.props.flash?.success" class="p-4 rounded-xl bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 text-sm font-medium flex items-center gap-2 shadow-sm">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ $page.props.flash.success }}</span>
            </div>

            <!-- Page Title -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Pengaturan Profil</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola informasi diri, foto profil, dan kredensial akun Anda.</p>
                </div>
            </div>

            <Card>
                <form @submit.prevent="submitProfile" class="space-y-6">
                    <!-- Avatar Section -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 pb-6 border-b border-slate-200 dark:border-slate-800">
                        <div class="relative group">
                            <img
                                :src="avatarPreview || user.avatar_url"
                                :alt="user.name"
                                class="h-24 w-24 rounded-full object-cover ring-4 ring-indigo-500/20 shadow-md"
                            />
                        </div>

                        <div class="space-y-2">
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Foto Profil</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Format yang didukung: JPG, PNG, WEBP. Maksimal 2MB.</p>

                            <div class="flex flex-wrap gap-2 pt-1">
                                <input
                                    ref="avatarInput"
                                    type="file"
                                    accept="image/*"
                                    class="hidden"
                                    @change="handleAvatarChange"
                                />

                                <SecondaryButton type="button" @click="$refs.avatarInput.click()">
                                    Pilih Foto Baru
                                </SecondaryButton>

                                <button
                                    v-if="user.avatar || avatarPreview"
                                    type="button"
                                    class="px-3 py-1.5 text-xs font-semibold text-red-600 hover:text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-xl transition-colors"
                                    @click="removeAvatar"
                                >
                                    Hapus Foto
                                </button>
                            </div>
                            <div v-if="form.errors.avatar" class="text-xs text-red-500 font-medium">{{ form.errors.avatar }}</div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="space-y-4">
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">Informasi Diri</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
                                <TextInput
                                    v-model="form.name"
                                    type="text"
                                    required
                                    placeholder="Masukkan nama lengkap"
                                />
                                <div v-if="form.errors.name" class="text-xs text-red-500 font-medium mt-1">{{ form.errors.name }}</div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Alamat Email</label>
                                <TextInput
                                    v-model="form.email"
                                    type="email"
                                    required
                                    placeholder="nama@email.com"
                                />
                                <div v-if="form.errors.email" class="text-xs text-red-500 font-medium mt-1">{{ form.errors.email }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
                        <PrimaryButton type="submit" :disabled="form.processing">
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                        </PrimaryButton>
                    </div>
                </form>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
