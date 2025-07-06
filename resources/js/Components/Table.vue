<script setup lang="ts">
import { Table } from 'ant-design-vue';
</script>

<template>
    <Table v-bind="$attrs">
        <template v-for="(_, slot) in $slots" v-slot:[slot]="scope">
            <slot :name="slot" v-bind="scope" />
        </template>
    </Table>
</template>

<style scoped>
:deep(.ant-pagination-total-text),
:deep(.ant-pagination-options) {
    display: none;
}

@media (min-width: 640px) {
    :deep(.ant-pagination-total-text),
    :deep(.ant-pagination-options) {
        display: inline-block;
    }
}
</style>
