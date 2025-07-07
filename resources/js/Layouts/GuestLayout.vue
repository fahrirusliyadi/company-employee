<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useFlashMessages } from '@/composables/useFlashMessages';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

// Initialize flash message handling
useFlashMessages();
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0"
    >
        <div>
            <Link href="/">
                <ApplicationLogo class="h-16 w-auto fill-current text-gray-500" />
            </Link>
        </div>

        <div
            class="mt-10 w-full overflow-hidden bg-white p-8 shadow-sm sm:max-w-md sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
