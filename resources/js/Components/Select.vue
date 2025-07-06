<script setup lang="ts">
import { Select } from 'ant-design-vue';
</script>

<template>
    <Select v-bind="$attrs">
        <template v-for="(_, slot) in $slots" v-slot:[slot]="scope">
            <slot :name="slot" v-bind="scope" />
        </template>
    </Select>
</template>

<style scoped>
.ant-select {
    font-size: 1rem;
}

.ant-select :deep(.ant-select-selector) {
    height: 42px;
    font-size: inherit;
}

.ant-select :deep(.ant-select-selector) .ant-select-selection-search-input {
    height: 40px;
}

.ant-select :deep(.ant-select-selector) .ant-select-selection-placeholder,
.ant-select :deep(.ant-select-selector) .ant-select-selection-item {
    line-height: 40px;
}
</style>
