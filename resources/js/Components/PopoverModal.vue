<script setup>
import { onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    show: { type: Boolean, default: false },
    title: { type: String, default: '' },
    subtitle: { type: String, default: '' },
    maxWidth: { type: String, default: 'max-w-lg' },
    closeable: { type: Boolean, default: true }
});

const emit = defineEmits(['close', 'update:show']);

function close() {
    if (props.closeable) {
        emit('update:show', false);
        emit('close');
    }
}

function handleKeyDown(e) {
    if (e.key === 'Escape' && props.show) {
        close();
    }
}

watch(() => props.show, (newVal) => {
    if (newVal) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
});

onMounted(() => {
    window.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
    document.body.style.overflow = '';
    window.removeEventListener('keydown', handleKeyDown);
});
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center min-h-screen"
            >
                <!-- Backdrop overlay -->
                <div
                    class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-sm transition-opacity"
                    @click="close"
                />

                <!-- Dialog card -->
                <Transition
                    enter-active-class="transition ease-out duration-200 transform"
                    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="transition ease-in duration-150 transform"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <div
                        v-if="show"
                        :class="[
                            'relative w-full overflow-hidden rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl transition-all my-auto',
                            maxWidth
                        ]"
                    >
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800/80 px-6 py-4">
                            <slot name="header">
                                <div>
                                    <h3 v-if="title" class="text-lg font-bold text-slate-900 dark:text-white">
                                        {{ title }}
                                    </h3>
                                    <p v-if="subtitle" class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                        {{ subtitle }}
                                    </p>
                                </div>
                            </slot>

                            <button
                                v-if="closeable"
                                type="button"
                                class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200 transition-colors ml-auto"
                                @click="close"
                            >
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="p-6">
                            <slot />
                        </div>

                        <!-- Modal Footer -->
                        <div v-if="$slots.footer" class="flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-950/40 px-6 py-4">
                            <slot name="footer" />
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
