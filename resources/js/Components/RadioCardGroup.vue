<script setup>
defineProps({
    modelValue: { type: [String, Number], default: '' },
    label: { type: String, default: '' },
    options: { type: Array, default: () => [] }
});

const emit = defineEmits(['update:modelValue']);

function select(value) {
    emit('update:modelValue', value);
}
</script>

<template>
    <div>
        <label v-if="label" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-3">
            {{ label }}
        </label>
        <div class="space-y-3">
            <label
                v-for="opt in options"
                :key="opt.value"
                :class="[
                    'flex items-start gap-3 rounded-xl border p-4 cursor-pointer transition-all',
                    modelValue === opt.value
                        ? 'bg-indigo-50 border-indigo-300 dark:bg-indigo-600/10 dark:border-indigo-500/30'
                        : 'bg-slate-50 border-slate-200 hover:border-slate-300 dark:bg-slate-950/60 dark:border-slate-800 dark:hover:border-slate-700'
                ]"
                @click="select(opt.value)"
            >
                <input
                    type="radio"
                    :value="opt.value"
                    :checked="modelValue === opt.value"
                    class="mt-0.5 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 bg-white dark:border-slate-700 dark:bg-slate-900"
                    @change="select(opt.value)"
                />
                <div>
                    <div class="text-xs font-semibold text-slate-900 dark:text-white">{{ opt.title }}</div>
                    <div v-if="opt.description" class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ opt.description }}</div>
                </div>
            </label>
        </div>
    </div>
</template>
