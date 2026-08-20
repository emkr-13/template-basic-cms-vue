<script setup>
import { onMounted, onUnmounted, watch } from 'vue';
import SecondaryButton from './SecondaryButton.vue';

const props = defineProps({
    show: { type: Boolean, default: false },
    variant: {
        type: String,
        default: 'danger',
        validator: (value) => ['danger', 'warning', 'info', 'success'].includes(value)
    },
    title: { type: String, default: 'Confirm Action' },
    message: { type: String, default: 'Are you sure you want to proceed with this action?' },
    confirmText: { type: String, default: 'Confirm' },
    cancelText: { type: String, default: 'Cancel' },
    loading: { type: Boolean, default: false },
    closeable: { type: Boolean, default: true }
});

const emit = defineEmits(['close', 'confirm', 'cancel', 'update:show']);

function close() {
    if (props.closeable && !props.loading) {
        emit('update:show', false);
        emit('cancel');
        emit('close');
    }
}

function handleConfirm() {
    if (!props.loading) {
        emit('confirm');
    }
}

function handleKeyDown(e) {
    if (e.key === 'Escape' && props.show) {
        close();
    }
}

watch(
    () => props.show,
    (newVal) => {
        if (newVal) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }
);

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
                        class="relative w-full max-w-md overflow-hidden rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl transition-all my-auto p-6"
                    >
                        <!-- Header & Icon -->
                        <div class="flex items-start gap-4">
                            <!-- Variant Icon Container -->
                            <div
                                :class="[
                                    'flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border transition-colors',
                                    variant === 'danger'
                                        ? 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400 border-red-200 dark:border-red-900/40'
                                        : variant === 'warning'
                                        ? 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 border-amber-200 dark:border-amber-900/40'
                                        : variant === 'info'
                                        ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400 border-indigo-200 dark:border-indigo-900/40'
                                        : 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border-emerald-200 dark:border-emerald-900/40'
                                ]"
                            >
                                <!-- Danger Icon (Warning Triangle) -->
                                <svg
                                    v-if="variant === 'danger'"
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                    />
                                </svg>

                                <!-- Warning Icon (Exclamation Circle) -->
                                <svg
                                    v-else-if="variant === 'warning'"
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>

                                <!-- Info Icon -->
                                <svg
                                    v-else-if="variant === 'info'"
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>

                                <!-- Success Icon -->
                                <svg
                                    v-else
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <h3 class="text-base font-bold text-slate-900 dark:text-white">
                                    {{ title }}
                                </h3>
                                <p class="mt-1.5 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                                    <slot>{{ message }}</slot>
                                </p>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800/80">
                            <SecondaryButton
                                type="button"
                                :disabled="loading"
                                @click="close"
                            >
                                {{ cancelText }}
                            </SecondaryButton>

                            <button
                                type="button"
                                :disabled="loading"
                                :class="[
                                    'inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-xs font-semibold text-white shadow-lg active:scale-[0.99] disabled:opacity-50 transition-all',
                                    variant === 'danger'
                                        ? 'bg-red-600 hover:bg-red-700 shadow-red-600/30 dark:bg-red-600 dark:hover:bg-red-500'
                                        : variant === 'warning'
                                        ? 'bg-amber-600 hover:bg-amber-700 shadow-amber-600/30 dark:bg-amber-600 dark:hover:bg-amber-500'
                                        : variant === 'info'
                                        ? 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-600/30 dark:bg-indigo-600 dark:hover:bg-indigo-500'
                                        : 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/30 dark:bg-emerald-600 dark:hover:bg-emerald-500'
                                ]"
                                @click="handleConfirm"
                            >
                                <svg
                                    v-if="loading"
                                    class="h-3.5 w-3.5 animate-spin text-white shrink-0"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path>
                                </svg>
                                <span>{{ confirmText }}</span>
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
