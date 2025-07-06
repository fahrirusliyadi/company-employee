<script setup lang="ts">
import {
    DeleteOutlined,
    EditOutlined,
    EllipsisOutlined,
} from '@ant-design/icons-vue';
import { Table, TableProps } from 'ant-design-vue';
import type { ColumnsType } from 'ant-design-vue/es/table';
import { computed } from 'vue';
import type { Employee, EmployeeFilters, PaginatedData } from '@/types';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownButton from '@/Components/DropdownButton.vue';

/**
 * Props for the Employee Table component.
 * @interface Props
 */
interface Props {
    /** The paginated data of employees. */
    employees: PaginatedData<Employee>;
    /** Loading state for the table during data fetching operations. */
    isLoading?: boolean;
    /** Filter parameters for pagination, search, and sorting */
    filters?: EmployeeFilters;
}

/**
 * Emits for the Employee Table component.
 * @interface Emits
 */
interface Emits {
    /** Emitted when table state changes (pagination, sorting, etc.) */
    (e: 'change', pagination: any, filters: any, sorter: any): void;
    /** Emitted when the edit action is triggered for an employee */
    (e: 'row-edit', employee: Employee): void;
    /** Emitted when the delete action is triggered for an employee */
    (e: 'row-delete', employee: Employee): void;
}

/**
 * Defines the props for the component with default values.
 * @type {Readonly<Props>}
 */
const props = withDefaults(defineProps<Props>(), {
    isLoading: false,
});

/**
 * Defines the emits for the component.
 * @type {Readonly<Emits>}
 */
const emit = defineEmits<Emits>();

/**
 * Computed pagination configuration for the Ant Design Vue table.
 * @returns {Object} Pagination configuration object with current page, page size, total count, and display options.
 */
const pagination = computed(() => ({
    current: props.employees.meta.current_page,
    pageSize: props.employees.meta.per_page,
    total: props.employees.meta.total,
    hideOnSinglePage: true,
    showSizeChanger: true,
    showTotal: (total: number, range: [number, number]) => {
        const [start, end] = range;
        return `Showing ${start}-${end} of ${total} employees`;
    },
}));

/**
 * Computed table columns configuration for the Ant Design Vue table.
 * Defines column structure, sorting, responsiveness, and custom rendering for the employees table.
 * @returns {ColumnsType<Employee>} Array of column configuration objects.
 */
const columns = computed<ColumnsType<Employee>>(() => [
    {
        title: '#',
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
        title: 'Full Name',
        dataIndex: 'first_name',
        key: 'name',
        sorter: true,
        sortOrder:
            props.filters?.sort_by === 'first_name'
                ? props.filters.sort_direction === 'asc'
                    ? 'ascend'
                    : 'descend'
                : null,
        customRender: ({ record }: { record: Employee }) => {
            return record.first_name + ' ' + record.last_name;
        },
    },
    {
        title: 'Company',
        key: 'company',
        responsive: ['md'],
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
        title: 'Phone',
        dataIndex: 'phone',
        key: 'phone',
        ellipsis: true,
        sorter: true,
        responsive: ['sm'],
        sortOrder:
            props.filters?.sort_by === 'phone'
                ? props.filters.sort_direction === 'asc'
                    ? 'ascend'
                    : 'descend'
                : null,
    },
    {
        key: 'actions',
        width: 56,
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
        :dataSource="employees.data"
        :columns="columns"
        :row-key="(record) => record.id"
        :pagination="pagination"
        :loading="isLoading"
        @change="handleTableChange"
    >
        <template #bodyCell="{ column, text, record }">
            <template v-if="column.key === 'company'">
                <span v-if="record.company">{{ record.company.name }}</span>
                <span v-else class="text-gray-400">No company</span>
            </template>
            <template v-else-if="column.key === 'actions'">
                <Dropdown align="right" width="48">
                    <template #trigger>
                        <button aria-label="Actions">
                            <EllipsisOutlined />
                        </button>
                    </template>
                    <template #content>
                        <DropdownButton
                            class="flex items-center gap-2"
                            @click="$emit('row-edit', record as Employee)"
                        >
                            <EditOutlined /> Edit Employee
                        </DropdownButton>
                        <DropdownButton
                            class="flex items-center gap-2 text-red-600 hover:text-red-800"
                            @click="$emit('row-delete', record as Employee)"
                        >
                            <DeleteOutlined />
                            Delete Employee
                        </DropdownButton>
                    </template>
                </Dropdown>
            </template>
        </template>
    </Table>
</template>
