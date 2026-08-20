<script setup>
import { computed } from 'vue';

const props = defineProps({
    status: { type: String, default: 'active' },
    type: { type: String, default: '' }
});

const badgeStyle = computed(() => {
    const val = props.type || props.status;
    if (val === 'active' || val === 'success') {
        return 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20';
    }
    if (val === 'invitation_pending' || val === 'warning' || val === 'pending') {
        return 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20';
    }
    if (val === 'indigo' || val === 'info') {
        return 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-300 dark:border-indigo-500/20';
    }
    if (val === 'blue') {
        return 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20';
    }
    return 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700';
});

const statusText = computed(() => {
    if (props.status === 'active') return 'Active';
    if (props.status === 'invitation_pending') return 'Pending Invitation';
    if (props.status === 'disabled') return 'Disabled';
    return props.status;
});
</script>

<template>
    <span :class="['inline-flex items-center rounded-full border px-2.5 py-0.5 text-[10px] font-semibold tracking-wide', badgeStyle]">
        <slot>{{ statusText }}</slot>
    </span>
</template>
