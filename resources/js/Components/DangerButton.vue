<script setup>
defineProps({
    type: { type: String, default: 'button' },
    loading: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    customClass: {
        type: String,
        default: 'inline-flex items-center justify-center gap-1.5 rounded-lg bg-red-50 border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-700 dark:bg-red-600/10 dark:border-red-500/20 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all'
    }
});
</script>

<template>
    <button :type="type" :disabled="disabled || loading" :class="customClass">
        <svg v-if="loading" class="h-3.5 w-3.5 animate-spin text-current shrink-0" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <slot />
    </button>
</template>
