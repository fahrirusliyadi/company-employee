<script setup lang="ts">
import { onMounted, ref } from 'vue';

const model = defineModel<string>({ required: true });

const input = ref<HTMLInputElement | null>(null);

onMounted(() => {
    if (input.value?.hasAttribute('autofocus')) {
        input.value?.focus();
    }
});

defineExpose({ focus: () => input.value?.focus() });
</script>

<template>
    <input
        class="hover:border-primary-500 focus:border-primary-500 focus:ring-primary-500 rounded-md border-gray-300 transition duration-150"
        v-model="model"
        ref="input"
    />
</template>
