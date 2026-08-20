<script setup>
defineProps({
    modelValue: { type: Boolean, default: false },
    label: { type: String, default: '' },
    id: { type: String, default: () => 'checkbox-' + Math.random().toString(36).substr(2, 9) }
});

const emit = defineEmits(['update:modelValue']);

function onChange(event) {
    emit('update:modelValue', event.target.checked);
}
</script>

<template>
    <label :for="id" class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-slate-300 cursor-pointer select-none">
        <input
            :id="id"
            type="checkbox"
            :checked="modelValue"
            class="h-4 w-4 rounded border-slate-300 bg-white text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 transition-all"
            @change="onChange"
        />
        <span><slot>{{ label }}</slot></span>
    </label>
</template>
