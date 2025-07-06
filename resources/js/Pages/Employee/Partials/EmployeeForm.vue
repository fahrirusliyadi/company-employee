<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { Modal } from 'ant-design-vue';
import { watch } from 'vue';
import { Employee } from '@/types';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import CompanySelect from '@/Components/CompanySelect.vue';

/**
 * Component props interface
 * @interface Props
 */
interface Props {
    /** Controls whether the modal is visible */
    isOpen: boolean;
    /** The employee object to edit, or null for creating a new employee */
    employee: Employee | null;
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
 * Form instance for handling employee creation/update
 * Contains form data and validation state
 */
const form = useForm({
    /** Company ID field */
    company_id: null as number | null,
    /** Employee first name field */
    first_name: '',
    /** Employee last name field */
    last_name: '',
    /** Employee email field */
    email: '',
    /** Employee phone field */
    phone: '',
    /** HTTP method for form spoofing */
    _method: 'post' as 'post' | 'put',
});

/**
 * Handles form submission for creating or updating an employee.
 * Posts form data to the employees.store or employees.update route and handles success/error states
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
            form.reset();
        }
    };

    if (props.employee) {
        form._method = 'put';
        form.post(route('employees.update', props.employee.id), {
            onSuccess,
        });
    } else {
        form._method = 'post';
        form.post(route('employees.store'), { onSuccess });
    }
};

const handleCancel = () => {
    form.reset();
    emit('close');
};

/**
 * Watches for changes in the `props.employee` object.
 * When an employee is provided, it populates the form fields with the employee's data.
 * When the employee is null or undefined, it resets the form.
 * @param {Employee | undefined} employee - The employee object to watch.
 */
watch(
    () => props.employee,
    (employee) => {
        if (employee) {
            form.company_id = employee.company_id;
            form.first_name = employee.first_name;
            form.last_name = employee.last_name;
            form.email = employee.email ?? '';
            form.phone = employee.phone ?? '';
        } else {
            form.reset();
        }
    },
    { immediate: true },
);
</script>

<template>
    <Modal centered :footer="null" :open="isOpen" @cancel="handleCancel">
        <form class="space-y-6" @submit.prevent="handleSubmit">
            <div class="space-y-1">
                <h2 class="text-lg font-medium text-gray-900 me-8">
                    {{ employee ? 'Edit Employee' : 'Create Employee' }}
                </h2>

                <p class="text-sm text-gray-600">
                    {{
                        employee
                            ? 'Update the employee details.'
                            : 'Fill in the details to create a new employee.'
                    }}
                </p>
            </div>

            <div class="space-y-1">
                <InputLabel for="company_id" value="Company" />
                <CompanySelect
                    id="company_id"
                    v-model="form.company_id"
                    class="w-full"
                    placeholder="Select a company"
                    :initial-company="employee?.company"
                />
                <InputError :message="form.errors.company_id" />
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="space-y-1">
                    <InputLabel for="first_name" value="First Name" />
                    <TextInput
                        id="first_name"
                        v-model="form.first_name"
                        class="w-full"
                        placeholder="Enter first name"
                    />
                    <InputError :message="form.errors.first_name" />
                </div>

                <div class="space-y-1">
                    <InputLabel for="last_name" value="Last Name" />
                    <TextInput
                        id="last_name"
                        v-model="form.last_name"
                        class="w-full"
                        placeholder="Enter last name"
                    />
                    <InputError :message="form.errors.last_name" />
                </div>
            </div>

            <div class="space-y-1">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="w-full"
                    placeholder="Enter email address"
                />
                <InputError :message="form.errors.email" />
            </div>

            <div class="space-y-1">
                <InputLabel for="phone" value="Phone" />
                <TextInput
                    id="phone"
                    v-model="form.phone"
                    class="w-full"
                    placeholder="Enter phone number"
                />
                <InputError :message="form.errors.phone" />
            </div>

            <div class="flex justify-end gap-3">
                <SecondaryButton type="button" @click="handleCancel">
                    Cancel
                </SecondaryButton>
                <PrimaryButton
                    type="submit"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    {{ employee ? 'Update Employee' : 'Create Employee' }}
                </PrimaryButton>
            </div>
        </form>
    </Modal>
</template>
