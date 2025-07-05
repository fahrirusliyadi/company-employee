<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Company } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

/**
 * Component props interface
 * @interface Props
 */
interface Props {
    /** Controls whether the modal is visible */
    show: boolean;
    company: Company | null;
}

/**
 * Component emits interface
 * @interface Emits
 */
interface Emits {
    /** Emitted when the modal should be closed */
    (e: 'close'): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

/**
 * Form instance for handling company creation
 * Contains form data and validation state
 */
const form = useForm({
    /** Company name field */
    name: '',
    /** Company email field */
    email: '',
    /** Company website field */
    website: '',
});

/**
 * Handles form submission for creating or updating a company.
 * Posts form data to the companies.store route and handles success/error states
 */
const handleSubmit = () => {
    /**
     * Callback function executed on successful form submission.
     * Closes the modal and resets the form fields.
     */
    const onSuccess = () => {
        emit('close');
        form.reset();
    };

    if (props.company) {
        form.put(route('companies.update', props.company.id), { onSuccess });
    } else {
        form.post(route('companies.store'), { onSuccess });
    }
};

/**
 * Watches for changes in the `props.company` object.
 * When a company is provided, it populates the form fields with the company's data.
 * When the company is null or undefined, it resets the form.
 * @param {Company | undefined} company - The company object to watch.
 */
watch(
    () => props.company,
    (company) => {
        if (company) {
            form.name = company.name ?? '';
            form.email = company.email ?? '';
            form.website = company.website ?? '';
        } else {
            form.reset();
        }
    },
);
</script>

<template>
    <Modal :show="props.show" @close="$emit('close')">
        <form class="space-y-6 p-6" @submit.prevent="handleSubmit">
            <div class="space-y-1">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ props.company ? 'Edit Company' : 'Create Company' }}
                </h2>

                <p class="text-sm text-gray-600">
                    {{
                        props.company
                            ? 'Update the company details.'
                            : 'Fill in the details to create a new company.'
                    }}
                </p>
            </div>

            <div class="space-y-1">
                <InputLabel for="name" value="Company Name" />
                <TextInput
                    id="name"
                    v-model="form.name"
                    class="w-full"
                    placeholder="Enter company name"
                />
                <InputError :message="form.errors.name" />
            </div>

            <div class="space-y-1">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="w-full"
                    placeholder="Enter company email"
                />
                <InputError :message="form.errors.email" />
            </div>

            <div class="space-y-1">
                <InputLabel for="website" value="Website" />
                <TextInput
                    id="website"
                    v-model="form.website"
                    type="url"
                    class="w-full"
                    placeholder="Enter company website"
                />
                <InputError :message="form.errors.website" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="$emit('close')">
                    Cancel
                </SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    {{ props.company ? 'Update Company' : 'Create Company' }}
                </PrimaryButton>
            </div>
        </form>
    </Modal>
</template>
