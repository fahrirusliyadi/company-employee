<script setup lang="ts">
import type { DropdownProps } from 'ant-design-vue';
import { Dropdown as ADropdown } from 'ant-design-vue';
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        placement?: DropdownProps['placement'];
        width?: '48';
        contentClasses?: string;
    }>(),
    {
        placement: 'bottomRight',
        width: '48',
        contentClasses: 'py-1 bg-white',
    },
);

const widthClass = computed(() => {
    return {
        48: 'w-48',
    }[props.width.toString()];
});
</script>

<template>
    <ADropdown :placement="placement" trigger="click">
        <slot name="trigger" />
        <template #overlay>
            <div
                class="rounded-md shadow-lg ring-1 ring-black ring-opacity-5"
                :class="[widthClass, contentClasses]"
            >
                <slot name="content" />
            </div>
        </template>
    </ADropdown>
</template>
