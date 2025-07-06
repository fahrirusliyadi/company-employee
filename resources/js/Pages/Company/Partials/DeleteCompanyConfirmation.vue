<script setup lang="ts">
import { Modal } from 'ant-design-vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Company } from '@/types';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Props {
    /** Controls whether the confirmation modal is visible */
    isOpen: boolean;
    /** The company to be deleted */
    company: Company | null;
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
 * Handles the deletion of the company
 */
const handleDelete = () => {
    router.delete(route('companies.destroy', props.company?.id), {
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
    <Modal centered :footer="null" :open="props.isOpen" @cancel="emit('close')">
        <div class="space-y-6">
            <div class="space-y-1">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete "{{ props.company?.name }}"?
                </h2>

                <p class="text-sm text-gray-600">
                    Once this company is deleted, all of its data will be
                    permanently removed. This action cannot be undone.
                </p>
            </div>

            <div class="flex justify-end">
                <SecondaryButton @click="emit('close')">Cancel</SecondaryButton>
                <DangerButton
                    @click="handleDelete"
                    class="ml-2"
                    :class="{ 'opacity-25': isDeleting }"
                    :disabled="isDeleting"
                >
                    Delete Company
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
