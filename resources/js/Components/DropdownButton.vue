<script setup lang="ts">
defineProps<{
    type?: 'submit' | 'button' | 'reset';
}>();
</script>

<template>
    <button
        :type="type"
        class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
    >
        <slot />
    </button>
</template>
