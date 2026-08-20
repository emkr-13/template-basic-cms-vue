<script setup>
defineProps({
    search: { type: String, default: '' },
    status: { type: String, default: '' },
    searchPlaceholder: { type: String, default: 'Search records...' },
    statusOptions: {
        type: Array,
        default: () => [
            { value: '', label: 'All Statuses' },
            { value: 'active', label: 'Active' },
            { value: 'invitation_pending', label: 'Pending Invitation' },
            { value: 'disabled', label: 'Disabled' }
        ]
    }
});

const emit = defineEmits(['update:search', 'update:status', 'filter', 'clear']);

function onSearchInput(e) {
    emit('update:search', e.target.value);
}

function onStatusChange(e) {
    emit('update:status', e.target.value);
}

function onSubmit() {
    emit('filter');
}

function onClear() {
    emit('clear');
}
</script>

<template>
    <form class="flex flex-col gap-2.5 sm:flex-row sm:items-center" @submit.prevent="onSubmit">
        <div class="relative flex-1">
            <input
                :value="search"
                type="text"
                :placeholder="searchPlaceholder"
                class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-950/80 dark:text-white dark:placeholder-slate-500 transition-all"
                @input="onSearchInput"
            />
        </div>

        <div v-if="statusOptions.length" class="w-full sm:w-48">
            <select
                :value="status"
                class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-xs text-slate-900 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-950/80 dark:text-white transition-all"
                @change="onStatusChange"
            >
                <option v-for="opt in statusOptions" :key="opt.value" :value="opt.value">
                    {{ opt.label }}
                </option>
            </select>
        </div>

        <div class="flex items-center gap-2">
            <button
                type="submit"
                class="rounded-xl border border-slate-300 bg-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-800 hover:bg-slate-300 dark:border-slate-800 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700 transition-all"
            >
                Filter
            </button>
            <button
                v-if="search || status"
                type="button"
                class="rounded-xl border border-slate-200 px-3 py-2.5 text-xs font-medium text-slate-500 hover:text-slate-900 dark:border-slate-800 dark:text-slate-400 dark:hover:text-white transition-all"
                @click="onClear"
            >
                Clear
            </button>
        </div>
    </form>
</template>
