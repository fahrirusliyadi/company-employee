<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { LoadingOutlined } from '@ant-design/icons-vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-6 space-y-2">
            <h1 class="text-xl font-semibold leading-tight text-gray-800">
                Forgot your password?
            </h1>
            <p class="text-sm text-gray-600">
                No problem. Just let us know your email address and we will
                email you a password reset link that will allow you to choose a
                new one.
            </p>
            <div v-if="status" class="text-sm font-medium text-green-600">
                {{ status }}
            </div>
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <PrimaryButton
                    class="gap-2"
                    :class="{ 'cursor-wait opacity-50': form.processing }"
                    :disabled="form.processing"
                >
                    <LoadingOutlined v-if="form.processing" />
                    Email Password Reset Link
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
