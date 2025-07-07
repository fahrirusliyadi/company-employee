<script setup lang="ts">
import { Table } from 'ant-design-vue';
</script>

<template>
    <!--
      The Table component acts as a wrapper for Ant Design Vue's Table.
      All props, events, and slots are implicitly passed down to the A-Table component.
      This allows for full flexibility of the Ant Design Vue Table API while
      maintaining a consistent look and feel through scoped styles.
    -->
    <Table>
        <!--
          This loop iterates over all slots passed to this component and
          forwards them to the underlying Ant Design Vue Table component.
          This ensures that any content provided via slots is rendered correctly
          by the Ant Design Vue component.
        -->
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

:deep(.ant-spin-nested-loading) .ant-spin {
    max-height: none;
}

@media (min-width: 640px) {
    :deep(.ant-pagination-total-text),
    :deep(.ant-pagination-options) {
        display: inline-block;
    }
}
</style>
