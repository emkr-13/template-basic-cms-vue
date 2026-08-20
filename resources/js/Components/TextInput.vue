<script setup>
import { ref } from 'vue';

const props = defineProps({
    modelValue: { type: [String, Number], default: '' },
    label: { type: String, default: '' },
    error: { type: String, default: '' },
    type: { type: String, default: 'text' },
    placeholder: { type: String, default: '' },
    required: { type: Boolean, default: false },
    autocomplete: { type: String, default: 'off' },
    disabled: { type: Boolean, default: false },
    id: { type: String, default: () => 'input-' + Math.random().toString(36).substr(2, 9) }
});

const emit = defineEmits(['update:modelValue']);

const showPassword = ref(false);

function togglePasswordVisibility() {
    showPassword.value = !showPassword.value;
}

function onInput(event) {
    emit('update:modelValue', event.target.value);
}
</script>

<template>
    <div>
        <label v-if="label" :for="id" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
            {{ label }} <span v-if="required" class="text-red-500">*</span>
        </label>
        <div class="relative">
            <input
                :id="id"
                :type="type === 'password' ? (showPassword ? 'text' : 'password') : type"
                :value="modelValue"
                :placeholder="placeholder"
                :required="required"
                :autocomplete="autocomplete"
                :disabled="disabled"
                :class="[
                    'w-full rounded-xl border border-slate-300 bg-slate-50/80 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-800 dark:bg-slate-950/80 dark:text-white dark:placeholder-slate-500 disabled:opacity-50 transition-all',
                    type === 'password' ? 'pr-11' : '',
                    error ? 'border-red-500 focus:border-red-500 focus:ring-red-500/20' : ''
                ]"
                @input="onInput"
            />

            <!-- Eye Toggle Button for Password Fields -->
            <button
                v-if="type === 'password'"
                type="button"
                tabindex="-1"
                class="absolute right-3 top-1/2 -translate-y-1/2 rounded-lg p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors focus:outline-none"
                :title="showPassword ? 'Hide password' : 'Show password'"
                @click="togglePasswordVisibility"
            >
                <!-- Eye Icon (when hidden) -->
                <svg v-if="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <!-- Eye Slash Icon (when visible) -->
                <svg v-else class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.02 10.02 0 012.122-.363c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m-9.756-9.756l9.756 9.756" />
                </svg>
            </button>
        </div>
        <p v-if="error" class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">{{ error }}</p>
    </div>
</template>
