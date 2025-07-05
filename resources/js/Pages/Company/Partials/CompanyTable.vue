<script setup lang="ts">
import type { Company, CompanyFilters, PaginatedData } from '@/types';
import { Table, TableProps } from 'ant-design-vue';
import type { ColumnsType } from 'ant-design-vue/es/table';
import { computed, h } from 'vue';

/**
 * Props for the Company Table component.
 * @interface Props
 */
interface Props {
    /** The paginated data of companies. */
    companies: PaginatedData<Company>;
    /** Loading state for the table during data fetching operations. */
    loading?: boolean;
    /** Filter parameters for pagination, search, and sorting */
    filters?: CompanyFilters;
}

/**
 * Emits for the Company Table component.
 * @interface Emits
 */
interface Emits {
    /** Emitted when table state changes (pagination, sorting, etc.) */
    (e: 'change', pagination: any, filters: any, sorter: any): void;
}

const props = withDefaults(defineProps<Props>(), {
    loading: false,
});

const emit = defineEmits<Emits>();

/**
 * Computed pagination configuration for the Ant Design Vue table.
 * @returns {Object} Pagination configuration object with current page, page size, total count, and display options.
 */
const pagination = computed(() => ({
    current: props.companies.meta.current_page,
    pageSize: props.companies.meta.per_page,
    total: props.companies.meta.total,
    hideOnSinglePage: true,
    showSizeChanger: true,
    showTotal: (total: number, range: [number, number]) => {
        const [start, end] = range;
        return `Showing ${start}-${end} of ${total} companies`;
    },
}));

/**
 * Computed table columns configuration for the Ant Design Vue table.
 * Defines column structure, sorting, responsiveness, and custom rendering for the companies table.
 * @returns {ColumnsType<Company>} Array of column configuration objects.
 */
const columns = computed<ColumnsType<Company>>(() => [
    {
        title: '#',
        dataIndex: 'index',
        key: 'index',
        width: 56,
        responsive: ['sm'],
        customRender: ({ index }: { index: number }) => {
            return (
                (pagination.value.current - 1) * pagination.value.pageSize +
                index +
                1
            );
        },
    },
    {
        title: 'Name',
        dataIndex: 'name',
        key: 'name',
        sorter: true,
        sortOrder:
            props.filters?.sort_by === 'name'
                ? props.filters.sort_direction === 'asc'
                    ? 'ascend'
                    : 'descend'
                : null,
    },
    {
        title: 'Email',
        dataIndex: 'email',
        key: 'email',
        sorter: true,
        ellipsis: true,
        sortOrder:
            props.filters?.sort_by === 'email'
                ? props.filters.sort_direction === 'asc'
                    ? 'ascend'
                    : 'descend'
                : null,
    },
    {
        title: 'Website',
        dataIndex: 'website',
        key: 'website',
        ellipsis: true,
        sorter: true,
        responsive: ['sm'],
        sortOrder:
            props.filters?.sort_by === 'website'
                ? props.filters.sort_direction === 'asc'
                    ? 'ascend'
                    : 'descend'
                : null,
        customRender: ({ text }: { text: string }) => {
            if (!text) {
                return '';
            }
            return h(
                'a',
                { href: text, target: '_blank', rel: 'noopener noreferrer' },
                text,
            );
        },
    },
]);

/**
 * Handles the change event of the Ant Design Vue table.
 * This function emits the change event to the parent component.
 *
 * @param {any} pagination - The pagination object from the table.
 * @param {any} filters - The filters object from the table.
 * @param {any} sorter - The sorter object(s) from the table.
 */
const handleTableChange: TableProps['onChange'] = (
    pagination,
    filters,
    sorter,
) => {
    emit('change', pagination, filters, sorter);
};
</script>

<template>
    <Table
        :dataSource="companies.data"
        :columns="columns"
        :row-key="(record) => record.id"
        :pagination="pagination"
        :loading="loading"
        @change="handleTableChange"
    />
</template>
