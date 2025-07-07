<script setup lang="ts">
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Employee } from '@/types';
import { LoadingOutlined } from '@ant-design/icons-vue';
import { router } from '@inertiajs/vue3';
import { Modal } from 'ant-design-vue';
import { ref } from 'vue';

interface Props {
    /** Controls whether the confirmation modal is visible */
    isOpen: boolean;
    /** The employee to be deleted */
    employee: Employee | null;
}

interface Emits {
    /** Emitted when the modal should be closed */
    (e: 'close'): void;
}

/** The props passed to the component */
const props = defineProps<Props>();
/** The emits for the component */
const emit = defineEmits<Emits>();
/** State to track if the delete operation is in progress */
const isDeleting = ref(false);

/**
 * Handles the deletion of the employee
 */
const handleDelete = () => {
    router.delete(route('employees.destroy', props.employee?.id), {
        onStart: () => {
            isDeleting.value = true;
        },
        onSuccess: () => {
            emit('close');
        },
        onFinish: () => {
            isDeleting.value = false;
        },
    });
};
</script>

<template>
    <Modal centered :footer="null" :open="isOpen" @cancel="emit('close')">
        <div class="space-y-6">
            <div class="space-y-1">
                <h2 class="me-8 text-lg font-medium text-gray-900">
                    Are you sure you want to delete "{{ employee?.first_name }}
                    {{ employee?.last_name }}"?
                </h2>

                <p class="text-sm text-gray-600">
                    Once this employee is deleted, all of their data will be
                    permanently removed. This action cannot be undone.
                </p>
            </div>

            <div class="flex justify-end">
                <SecondaryButton @click="emit('close')">Cancel</SecondaryButton>
                <DangerButton
                    @click="handleDelete"
                    class="ml-2 gap-2"
                    :class="{ 'cursor-wait opacity-50': isDeleting }"
                    :disabled="isDeleting"
                >
                    <LoadingOutlined v-if="isDeleting" />
                    Delete Employee
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
