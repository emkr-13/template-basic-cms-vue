<script setup>
defineProps({
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

function onChange(event) {
    emit('update:modelValue', event.target.value);
}
</script>

<template>
    <div>
        <label v-if="label" :for="id" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
            {{ label }} <span v-if="required" class="text-red-500">*</span>
        </label>
        <select
            :id="id"
            :value="modelValue"
            :required="required"
            :disabled="disabled"
            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-950/80 dark:text-white disabled:opacity-50 transition-all"
            @change="onChange"
        >
            <option v-if="placeholder" value="" disabled>{{ placeholder }}</option>
            <slot>
                <option v-for="opt in options" :key="opt.value || opt.id || opt" :value="opt.value !== undefined ? opt.value : opt">
                    {{ opt.label || opt.name || opt }}
                </option>
            </slot>
        </select>
        <p v-if="error" class="mt-2 text-xs font-medium text-red-600 dark:text-red-400">{{ error }}</p>
    </div>
</template>
