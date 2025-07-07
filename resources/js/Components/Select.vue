<script setup lang="ts">
import { Select } from 'ant-design-vue';
</script>

<template>
    <!--
      The Select component acts as a wrapper for Ant Design Vue's Select.
      All props, events, and slots are implicitly passed down to the A-Select component.
      This allows for full flexibility of the Ant Design Vue Select API while
      maintaining a consistent look and feel through scoped styles.
    -->
    <Select>
        <!--
          This loop iterates over all slots passed to this component and
          forwards them to the underlying Ant Design Vue Select component.
          This ensures that any content provided via slots is rendered correctly
          by the Ant Design Vue component.
        -->
        <template v-for="(_, slot) in $slots" v-slot:[slot]="scope">
            <slot :name="slot" v-bind="scope" />
        </template>
    </Select>
</template>

<style scoped>
.ant-select {
    @apply text-base;
}

.ant-select :deep(.ant-select-selector) {
    height: 42px;
    font-size: inherit;
}

.ant-select :deep(.ant-select-selector) .ant-select-selection-search-input {
    @apply h-10;
}

.ant-select :deep(.ant-select-selector) .ant-select-selection-placeholder,
.ant-select :deep(.ant-select-selector) .ant-select-selection-item {
    @apply leading-10;
}
</style>
