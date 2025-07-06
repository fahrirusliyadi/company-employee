<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Company } from '@/types';
import { InboxOutlined } from '@ant-design/icons-vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { Modal, UploadDragger } from 'ant-design-vue';
import { ref, watch } from 'vue';

/**
 * Component props interface
 * @interface Props
 */
interface Props {
    /** Controls whether the modal is visible */
    isOpen: boolean;
    /** The company object to edit, or null for creating a new company */
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
const page = usePage();

/**
 * Form instance for handling company creation
 * Contains form data and validation state
 */
const logoPreview = ref<string | null>(null);

const form = useForm({
    /** Company name field */
    name: '',
    /** Company email field */
    email: '',
    /** Company website field */
    website: '',
    /** Company logo file */
    logo: null as File | null,
    /** HTTP method for form spoofing */
    _method: 'post' as 'post' | 'put',
});

/**
 * Resets the form fields to their initial state.
 * Clears the logo preview and resets all form fields.
 */
const resetForm = () => {
    form.reset();
    logoPreview.value = null;
};

/**
 * Handles form submission for creating or updating a company.
 * Posts form data to the companies.store route and handles success/error states
 */
const handleSubmit = () => {
    /**
     * Callback function executed on successful form submission.
     * Only closes the modal and resets the form if there are no general errors.
     */
    const onSuccess = () => {
        // Check if there's a general error flash message
        const flash = page.props.flash as { error?: string };

        // Only close the form if there's no general error
        if (!flash?.error) {
            emit('close');
            resetForm();
        }
    };

    if (props.company) {
        form._method = 'put';
        form.post(route('companies.update', props.company.id), {
            onSuccess,
            forceFormData: true,
        });
    } else {
        form._method = 'post';
        form.post(route('companies.store'), { onSuccess });
    }
};

/**
 * Handles file change event for logo upload.
 * Updates the form's logo field and generates a preview URL.
 * @param {any} info - The file information from the upload component.
 */
const handleFileChange = (info: any) => {
    const file = info.fileList[0]?.originFileObj;
    if (file) {
        form.logo = file;
        logoPreview.value = URL.createObjectURL(file);
    } else {
        form.logo = null;
        logoPreview.value = null;
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
            logoPreview.value = company.logo?.autox140 ?? null;
        } else {
            resetForm();
        }
    },
);
</script>

<template>
    <Modal centered :footer="null" :open="isOpen" @cancel="$emit('close')">
        <form class="space-y-6" @submit.prevent="handleSubmit">
            <div class="space-y-1">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ company ? 'Edit Company' : 'Create Company' }}
                </h2>

                <p class="text-sm text-gray-600">
                    {{
                        company
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

            <div class="space-y-1">
                <InputLabel for="logo" value="Logo" />

                <UploadDragger
                    class="block"
                    accept=".png,.jpg,.jpeg,.svg"
                    :max-count="1"
                    :show-upload-list="false"
                    :beforeUpload="() => false"
                    @change="handleFileChange"
                >
                    <img
                        v-if="logoPreview"
                        :src="logoPreview"
                        alt="Logo Preview"
                        class="mx-auto mb-4 max-h-[140px] w-auto rounded-md object-contain"
                    />
                    <p class="ant-upload-drag-icon" v-else>
                        <InboxOutlined />
                    </p>
                    <p class="ant-upload-text">
                        Click or drag file to this area to upload
                    </p>
                    <p class="ant-upload-hint">
                        Accepts PNG, JPG, and SVG formats only.
                    </p>
                </UploadDragger>
                <InputError :message="form.errors.logo" />
            </div>

            <div class="flex justify-end">
                <SecondaryButton @click="$emit('close')">
                    Cancel
                </SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    {{ company ? 'Update Company' : 'Create Company' }}
                </PrimaryButton>
            </div>
        </form>
    </Modal>
</template>
