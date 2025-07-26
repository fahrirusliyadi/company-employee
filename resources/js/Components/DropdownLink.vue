<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    href: string;
}>();
</script>

<template>
    <Link
        :href="href"
        class="ease-in-out. block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 hover:bg-gray-100 hover:text-gray-900 focus:bg-gray-100 focus:outline-none"
    >
        <slot />
    </Link>
</template>
