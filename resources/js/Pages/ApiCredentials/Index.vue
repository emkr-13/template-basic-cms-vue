<script setup>
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import Card from '../../Components/Card.vue';
import ConfirmModal from '../../Components/ConfirmModal.vue';
import DangerButton from '../../Components/DangerButton.vue';
import PopoverModal from '../../Components/PopoverModal.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import StatusBadge from '../../Components/StatusBadge.vue';
import TextInput from '../../Components/TextInput.vue';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';

const props = defineProps({ credentials: { type: Array, required: true } });
const page = usePage();
const createModalOpen = ref(false);
const generatedCredential = ref(null);
const generatedModalOpen = ref(false);
const revokeState = ref({ show: false, credential: null, loading: false });
const form = useForm({ name: '' });
const flashCredential = computed(() => page.props.flash?.apiCredential);

watch(flashCredential, (credential) => {
    if (credential) {
        generatedCredential.value = credential;
        generatedModalOpen.value = true;
    }
}, { immediate: true });

function createCredential() {
    form.post('/api-credentials', {
        onSuccess: () => {
            createModalOpen.value = false;
            form.reset();
        }
    });
}

function askRevoke(credential) {
    revokeState.value = { show: true, credential, loading: false };
}

function revokeCredential() {
    const credential = revokeState.value.credential;
    if (!credential) return;

    revokeState.value.loading = true;
    router.delete(`/api-credentials/${credential.id}`, {
        onFinish: () => {
            revokeState.value = { show: false, credential: null, loading: false };
        }
    });
}

async function copy(value) {
    await navigator.clipboard.writeText(value);
}
</script>

<template>
    <Head title="API Credentials — CMS Template" />

    <AuthenticatedLayout title="API Credentials">
        <Card>
            <template #header>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 dark:text-white sm:text-2xl">API Credentials</h1>
                    <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 sm:text-sm">Kelola credential untuk menerbitkan temporary Bearer Token API.</p>
                </div>
                <PrimaryButton type="button" @click="createModalOpen = true">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span>Generate Credential</span>
                </PrimaryButton>
            </template>

            <div class="hidden overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 md:block">
                <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300">
                    <thead class="border-b border-slate-200 bg-slate-100/80 uppercase tracking-wider text-slate-700 dark:border-slate-800 dark:bg-slate-950/80 dark:text-slate-400">
                        <tr><th class="px-4 py-3 font-semibold">Name</th><th class="px-4 py-3 font-semibold">Client ID</th><th class="px-4 py-3 font-semibold">Status</th><th class="px-4 py-3 font-semibold">Last Used</th><th class="px-4 py-3 font-semibold">Created</th><th class="px-4 py-3 text-right font-semibold">Actions</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800/80">
                        <tr v-for="credential in props.credentials" :key="credential.id" class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/40">
                            <td class="px-4 py-3.5 font-semibold text-slate-900 dark:text-white">{{ credential.name }}</td>
                            <td class="px-4 py-3.5 font-mono text-slate-500 dark:text-slate-400">{{ credential.client_id }}</td>
                            <td class="px-4 py-3.5"><StatusBadge :type="credential.status === 'active' ? 'success' : 'default'">{{ credential.status === 'active' ? 'Active' : 'Revoked' }}</StatusBadge></td>
                            <td class="px-4 py-3.5 text-slate-500 dark:text-slate-400">{{ credential.last_used_at || 'Never' }}</td>
                            <td class="px-4 py-3.5 text-slate-500 dark:text-slate-400">{{ credential.created_at }}</td>
                            <td class="px-4 py-3.5 text-right"><DangerButton v-if="credential.status === 'active'" @click="askRevoke(credential)">Revoke</DangerButton></td>
                        </tr>
                        <tr v-if="!props.credentials.length"><td colspan="6" class="px-4 py-12 text-center text-slate-500">No API credentials generated yet.</td></tr>
                    </tbody>
                </table>
            </div>

            <div class="space-y-3 md:hidden">
                <div v-for="credential in props.credentials" :key="credential.id" class="space-y-3 rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                    <div class="flex items-start justify-between gap-3"><div><div class="text-sm font-semibold text-slate-900 dark:text-white">{{ credential.name }}</div><div class="mt-1 break-all font-mono text-[11px] text-slate-500 dark:text-slate-400">{{ credential.client_id }}</div></div><StatusBadge :type="credential.status === 'active' ? 'success' : 'default'">{{ credential.status === 'active' ? 'Active' : 'Revoked' }}</StatusBadge></div>
                    <div class="grid grid-cols-2 gap-3 border-t border-slate-200 pt-3 text-[11px] dark:border-slate-800"><div><span class="block text-slate-500">Last Used</span><span class="text-slate-900 dark:text-white">{{ credential.last_used_at || 'Never' }}</span></div><div><span class="block text-slate-500">Created</span><span class="text-slate-900 dark:text-white">{{ credential.created_at }}</span></div></div>
                    <div v-if="credential.status === 'active'" class="flex justify-end"><DangerButton @click="askRevoke(credential)">Revoke</DangerButton></div>
                </div>
                <div v-if="!props.credentials.length" class="rounded-xl border border-slate-200 bg-slate-50 p-8 text-center text-xs text-slate-500 dark:border-slate-800 dark:bg-slate-950/60">No API credentials generated yet.</div>
            </div>
        </Card>

        <PopoverModal v-model:show="createModalOpen" title="Generate API Credential" subtitle="Secret hanya akan ditampilkan satu kali." max-width="max-w-md">
            <form class="space-y-4" @submit.prevent="createCredential"><TextInput v-model="form.name" label="Credential Name" placeholder="e.g. Partner integration" :error="form.errors.name" /><p class="text-xs leading-relaxed text-slate-500 dark:text-slate-400">Credential ini digunakan untuk memperoleh Bearer Token dengan masa berlaku satu jam.</p><div class="flex justify-end gap-3 border-t border-slate-100 pt-4 dark:border-slate-800"><SecondaryButton type="button" @click="createModalOpen = false">Cancel</SecondaryButton><PrimaryButton type="submit" :loading="form.processing">Generate</PrimaryButton></div></form>
        </PopoverModal>

        <PopoverModal v-model:show="generatedModalOpen" title="Credential Generated" subtitle="Simpan secret ini sekarang; setelah modal ditutup, secret tidak dapat dilihat kembali." max-width="max-w-lg">
            <div v-if="generatedCredential" class="space-y-4"><div class="rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs text-amber-800 dark:border-amber-900/40 dark:bg-amber-950/30 dark:text-amber-300">Jangan masukkan secret ke source code, repository, atau log.</div><div class="space-y-1"><div class="text-xs font-semibold text-slate-700 dark:text-slate-300">Client ID</div><div class="flex gap-2"><code class="min-w-0 flex-1 break-all rounded-lg bg-slate-100 p-2 text-xs dark:bg-slate-800">{{ generatedCredential.client_id }}</code><SecondaryButton type="button" @click="copy(generatedCredential.client_id)">Copy</SecondaryButton></div></div><div class="space-y-1"><div class="text-xs font-semibold text-slate-700 dark:text-slate-300">Client Secret</div><div class="flex gap-2"><code class="min-w-0 flex-1 break-all rounded-lg bg-slate-100 p-2 text-xs dark:bg-slate-800">{{ generatedCredential.client_secret }}</code><SecondaryButton type="button" @click="copy(generatedCredential.client_secret)">Copy</SecondaryButton></div></div></div>
            <template #footer><PrimaryButton type="button" @click="generatedModalOpen = false">I Have Saved It</PrimaryButton></template>
        </PopoverModal>

        <ConfirmModal v-model:show="revokeState.show" variant="danger" title="Revoke API Credential" :message="`Revoke credential ${revokeState.credential?.name || ''}? All of its active Bearer Tokens will stop working immediately.`" confirm-text="Revoke Credential" cancel-text="Cancel" :loading="revokeState.loading" @confirm="revokeCredential" />
    </AuthenticatedLayout>
</template>
