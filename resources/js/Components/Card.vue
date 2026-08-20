<script setup>
defineProps({
    title: { type: String, default: '' },
    subtitle: { type: String, default: '' },
    customClass: {
        type: String,
        default: 'rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/80 backdrop-blur-sm transition-colors'
    }
});
</script>

<template>
    <div :class="customClass">
        <!-- Optional Header -->
        <div v-if="$slots.header || title" class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-5 border-b border-slate-200 dark:border-slate-800">
            <slot name="header">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white sm:text-2xl">{{ title }}</h2>
                    <p v-if="subtitle" class="mt-1 text-xs sm:text-sm text-slate-600 dark:text-slate-400">{{ subtitle }}</p>
                </div>
            </slot>
        </div>

        <!-- Default Content Slot -->
        <div :class="[($slots.header || title) ? 'mt-5' : '']">
            <slot />
        </div>

        <!-- Optional Footer -->
        <div v-if="$slots.footer" class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-800/80">
            <slot name="footer" />
        </div>
    </div>
</template>
