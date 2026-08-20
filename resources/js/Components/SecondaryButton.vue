<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    type: { type: String, default: 'button' },
    href: { type: String, default: '' },
    disabled: { type: Boolean, default: false },
    customClass: {
        type: String,
        default: 'inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 hover:text-slate-900 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white transition-all shadow-sm'
    }
});
</script>

<template>
    <Link v-if="href" :href="href" :class="customClass">
        <slot />
    </Link>
    <button v-else :type="type" :disabled="disabled" :class="customClass">
        <slot />
    </button>
</template>
