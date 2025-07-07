<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    href: string;
}>();
</script>

<template>
    <Link
        :href="href"
        class="underline transition duration-150 ease-in-out hover:no-underline hover:text-primary-500 focus:no-underline focus:text-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
    >
        <slot />
    </Link>
</template>
