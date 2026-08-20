<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
    modelValue: { type: [String, Number], default: '' },
    label: { type: String, default: '' },
    error: { type: String, default: '' },
    placeholder: { type: String, default: 'Select an option...' },
    options: { type: Array, default: () => [] },
    required: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    id: { type: String, default: () => 'select-' + Math.random().toString(36).substr(2, 9) }
});

const emit = defineEmits(['update:modelValue']);

const isOpen = ref(false);
const containerRef = ref(null);

const parsedOptions = computed(() => {
    return props.options.map(opt => {
        if (typeof opt === 'object' && opt !== null) {
            const val = opt.value !== undefined ? opt.value : (opt.id !== undefined ? opt.id : opt.name);
            const lbl = opt.label || opt.name || opt.title || String(val);
            return { value: val, label: lbl };
        }
        return { value: opt, label: String(opt) };
    });
});

const selectedOption = computed(() => {
    return parsedOptions.value.find(opt => String(opt.value) === String(props.modelValue));
});

const displayLabel = computed(() => {
    if (selectedOption.value) {
        return selectedOption.value.label;
    }
    if (props.modelValue !== '' && props.modelValue !== null && props.modelValue !== undefined) {
        return String(props.modelValue);
    }
    return props.placeholder;
});

function toggle() {
    if (!props.disabled) {
        isOpen.value = !isOpen.value;
    }
}

function selectOption(val) {
    emit('update:modelValue', val);
    isOpen.value = false;
}

function handleClickOutside(e) {
    if (containerRef.value && !containerRef.value.contains(e.target)) {
        isOpen.value = false;
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div ref="containerRef" class="relative">
        <label v-if="label" :for="id" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
            {{ label }} <span v-if="required" class="text-red-500">*</span>
        </label>

        <!-- Custom Select Trigger Button -->
        <button
            :id="id"
            type="button"
            :disabled="disabled"
            :class="[
                'w-full flex items-center justify-between rounded-xl border px-4 py-3 text-sm text-left transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20',
                isOpen
                    ? 'border-indigo-500 bg-white ring-2 ring-indigo-500/20 dark:border-indigo-500 dark:bg-slate-900'
                    : 'border-slate-300 bg-slate-50/80 hover:border-slate-400 dark:border-slate-800 dark:bg-slate-950/80 dark:hover:border-slate-700',
                disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer',
                error ? 'border-red-500 focus:border-red-500 focus:ring-red-500/20' : ''
            ]"
            @click="toggle"
        >
            <span
                :class="[
                    'truncate font-medium',
                    selectedOption || (modelValue !== '' && modelValue !== null && modelValue !== undefined)
                        ? 'text-slate-900 dark:text-white'
                        : 'text-slate-400 dark:text-slate-500'
                ]"
            >
                {{ displayLabel }}
            </span>

            <svg
                :class="[
                    'h-4 w-4 shrink-0 text-slate-400 dark:text-slate-500 transition-transform duration-200',
                    isOpen ? 'rotate-180 text-indigo-600 dark:text-indigo-400' : ''
                ]"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Dropdown Options Popup -->
        <Transition
            enter-active-class="transition ease-out duration-150"
            enter-from-class="opacity-0 scale-95 -translate-y-1"
            enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition ease-in duration-100"
            leave-from-class="opacity-100 scale-100 translate-y-0"
            leave-to-class="opacity-0 scale-95 -translate-y-1"
        >
            <div
                v-if="isOpen"
                class="absolute left-0 right-0 z-40 mt-1 max-h-60 overflow-y-auto rounded-xl border border-slate-200 bg-white/95 p-1.5 shadow-xl backdrop-blur-md dark:border-slate-800 dark:bg-slate-900/95"
            >
                <!-- Placeholder / Clear option if placeholder present and select optional -->
                <button
                    v-if="placeholder && !required"
                    type="button"
                    :class="[
                        'w-full flex items-center justify-between rounded-lg px-3 py-2 text-xs font-medium transition-colors text-slate-400 dark:text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800/60',
                        modelValue === '' ? 'bg-slate-100 text-slate-900 dark:bg-slate-800 dark:text-white font-semibold' : ''
                    ]"
                    @click="selectOption('')"
                >
                    <span>{{ placeholder }}</span>
                    <svg v-if="modelValue === ''" class="h-3.5 w-3.5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </button>

                <!-- List of parsed options -->
                <button
                    v-for="opt in parsedOptions"
                    :key="opt.value"
                    type="button"
                    :class="[
                        'w-full flex items-center justify-between rounded-lg px-3 py-2 text-xs font-medium transition-colors',
                        String(opt.value) === String(modelValue)
                            ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-600/15 dark:text-indigo-400 font-semibold'
                            : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800/60'
                    ]"
                    @click="selectOption(opt.value)"
                >
                    <span class="truncate">{{ opt.label }}</span>
                    <svg
                        v-if="String(opt.value) === String(modelValue)"
                        class="h-3.5 w-3.5 text-indigo-600 dark:text-indigo-400 shrink-0"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </button>

                <div v-if="!parsedOptions.length && !placeholder" class="px-3 py-2 text-xs text-slate-400 dark:text-slate-500 text-center">
                    No options available
                </div>
            </div>
        </Transition>

        <p v-if="error" class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">{{ error }}</p>
    </div>
</template>
