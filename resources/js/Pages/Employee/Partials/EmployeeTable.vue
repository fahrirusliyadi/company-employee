<script setup lang="ts">
import ButtonLink from '@/Components/ButtonLink.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownButton from '@/Components/DropdownButton.vue';
import Table from '@/Components/Table.vue';
import { usePermissions } from '@/composables/usePermissions';
import type { Employee, PaginatedData } from '@/types';
import {
    DeleteOutlined,
    EditOutlined,
    EllipsisOutlined,
} from '@ant-design/icons-vue';
import { TableProps } from 'ant-design-vue';
import type { ColumnsType } from 'ant-design-vue/es/table';
import { computed } from 'vue';

/**
 * Props for the Employee Table component.
 * @interface Props
 */
interface Props {
    /** The paginated data of employees. */
    employees: PaginatedData<Employee>;
    /** Loading state for the table during data fetching operations. */
    isLoading?: boolean;
    /** Columns configuration for the Ant Design Vue table. */
    columns: ColumnsType<Employee>;
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
    /** Emitted when the company detail action is triggered for an employee */
    (e: 'row-company-detail', employee: Employee): void;
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
    position: ['bottomCenter'],
}));

/** Use permissions composable to check user permissions. */
const { hasPermission } = usePermissions();

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
        :row-key="(record: Employee) => record.id"
        :pagination="pagination"
        :loading="isLoading"
        :scroll="{ x: 'max-content' }"
        @change="handleTableChange"
    >
        <template #bodyCell="{ column, record }">
            <template v-if="column.key === 'company'">
                <ButtonLink
                    v-if="record.company"
                    class="text-start"
                    @click="$emit('row-company-detail', record as Employee)"
                >
                    {{ record.company.name }}
                </ButtonLink>
                <span v-else class="text-gray-400">No company</span>
            </template>
            <template v-else-if="column.key === 'actions'">
                <Dropdown placement="bottomRight" width="48">
                    <template #trigger>
                        <ButtonLink aria-label="Actions" class="no-underline">
                            <EllipsisOutlined />
                        </ButtonLink>
                    </template>
                    <template #content>
                        <DropdownButton
                            v-if="hasPermission('update-employees')"
                            class="flex items-center gap-2"
                            @click="$emit('row-edit', record as Employee)"
                        >
                            <EditOutlined /> Edit Employee
                        </DropdownButton>
                        <DropdownButton
                            v-if="hasPermission('delete-employees')"
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
