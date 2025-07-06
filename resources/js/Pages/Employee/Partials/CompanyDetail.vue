<script setup lang="ts">
import ExternalLink from '@/Components/ExternalLink.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Company } from '@/types';
import { LinkOutlined, MailOutlined } from '@ant-design/icons-vue';
import { Avatar, Empty, Modal } from 'ant-design-vue';

/**
 * Component props interface
 * @interface Props
 */
interface Props {
    /** Controls whether the modal is visible */
    isOpen: boolean;
    /** The company object to edit, or null for creating a new company */
    company?: Company | null;
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
defineEmits<Emits>();
</script>

<template>
    <Modal :footer="null" :open="props.isOpen" @cancel="$emit('close')">
        <div class="space-y-6">
            <div class="space-y-1">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ company?.name }}
                </h2>

                <p class="text-sm text-gray-600">
                    View the details of the company.
                </p>
            </div>

            <div class="flex flex-col sm:items-center gap-4 sm:flex-row">
                <div class="shrink-0">
                    <div class="mx-auto max-h-[140px] max-w-[140px]">
                        <img
                            v-if="company?.logo"
                            :src="company.logo.url"
                            alt="Logo Preview"
                            class="h-full w-full rounded-md object-contain"
                        />
                        <Avatar v-else shape="square" :size="140">
                            <span class="text-4xl">
                                {{ company?.name?.charAt(0)?.toUpperCase() }}
                            </span>
                        </Avatar>
                    </div>
                </div>
                <div class="grow rounded border p-4">
                    <dl
                        class="grid gap-4"
                        v-if="company?.email || company?.website"
                    >
                        <div
                            class="flex items-start gap-4"
                            v-if="company.email"
                        >
                            <MailOutlined class="text-lg" />
                            <div>
                                <dt class="font-medium text-gray-700">
                                    Company Email
                                </dt>
                                <dd class="break-all">
                                    <ExternalLink
                                        :href="`mailto:${company.email}`"
                                    >
                                        {{ company.email }}
                                    </ExternalLink>
                                </dd>
                            </div>
                        </div>
                        <div
                            class="flex items-start gap-4"
                            v-if="company.website"
                        >
                            <LinkOutlined class="text-lg" />
                            <div>
                                <dt class="font-medium text-gray-700">
                                    Company Website
                                </dt>
                                <dd class="break-all">
                                    <ExternalLink :href="company.website">
                                        {{ company.website }}
                                    </ExternalLink>
                                </dd>
                            </div>
                        </div>
                    </dl>
                    <Empty
                        class="my-[1px]"
                        v-else
                        :image="Empty.PRESENTED_IMAGE_SIMPLE"
                    >
                        <template #description>
                            No additional contact information available
                        </template>
                    </Empty>
                </div>
            </div>

            <div class="flex justify-end">
                <SecondaryButton @click="$emit('close')">
                    Close
                </SecondaryButton>
            </div>
        </div>
    </Modal>
</template>
