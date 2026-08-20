<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    prevPageUrl: { type: String, default: null },
    nextPageUrl: { type: String, default: null },
    totalCount: { type: Number, default: 0 },
    currentCount: { type: Number, default: 0 }
});
</script>

<template>
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
        <div class="text-slate-500 dark:text-slate-400">
            Showing <span class="font-semibold text-slate-900 dark:text-white">{{ currentCount || totalCount }}</span> items
        </div>

        <div class="flex items-center gap-2">
            <Link
                v-if="prevPageUrl"
                :href="prevPageUrl"
                class="rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-1.5 font-medium text-slate-700 hover:bg-slate-100 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white transition-all"
            >
                &larr; Previous
            </Link>
            <Link
                v-if="nextPageUrl"
                :href="nextPageUrl"
                class="rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-1.5 font-medium text-slate-700 hover:bg-slate-100 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white transition-all"
            >
                Next &rarr;
            </Link>
        </div>
    </div>
</template>
