<script setup lang="ts">
import {
    DeleteOutlined,
    EditOutlined,
    EllipsisOutlined,
} from '@ant-design/icons-vue';
import { Avatar, TableProps } from 'ant-design-vue';
import { computed } from 'vue';
import type { Company, PaginatedData } from '@/types';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownButton from '@/Components/DropdownButton.vue';
import ExternalLink from '@/Components/ExternalLink.vue';
import Table from '@/Components/Table.vue';
import type { ColumnsType } from 'ant-design-vue/es/table';

/**
 * Props for the Company Table component.
 * @interface Props
 */
interface Props {
    /** The paginated data of companies. */
    companies: PaginatedData<Company>;
    /** Loading state for the table during data fetching operations. */
    isLoading?: boolean;
    /** The columns to display in the table. */
    columns: ColumnsType<Company>;
}

/**
 * Emits for the Company Table component.
 * @interface Emits
 */
interface Emits {
    /** Emitted when table state changes (pagination, sorting, etc.) */
    (e: 'change', pagination: any, filters: any, sorter: any): void;
    /** Emitted when the edit action is triggered for a company */
    (e: 'row-edit', company: Company): void;
    /** Emitted when the delete action is triggered for a company */
    (e: 'row-delete', company: Company): void;
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
    current: props.companies.meta.current_page,
    pageSize: props.companies.meta.per_page,
    total: props.companies.meta.total,
    hideOnSinglePage: true,
    showSizeChanger: true,
    showTotal: (total: number, range: [number, number]) => {
        const [start, end] = range;
        return `Showing ${start}-${end} of ${total} companies`;
    },
    position: ['bottomCenter'],
}));


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
        :row-key="(record: Company) => record.id"
        :pagination="pagination"
        :loading="isLoading"
        :scroll="{ x: 'max-content' }"
        @change="handleTableChange"
    >
        <template #bodyCell="{ column, text, record }">
            <template v-if="column.key === 'name'">
                <span class="flex items-center gap-4">
                    <img
                        v-if="record.logo"
                        width="50"
                        height="50"
                        class="shrink-0 rounded"
                        :src="record.logo['50x50']"
                        :alt="`${record.name} logo`"
                    />
                    <Avatar v-else shape="square" class="shrink-0" :size="50">
                        {{ record.name.charAt(0).toUpperCase() }}
                    </Avatar>
                    {{ text }}
                </span>
            </template>
            <template v-else-if="column.key === 'website'">
                <ExternalLink v-if="text" :href="text">
                    {{ text }}
                </ExternalLink>
            </template>
            <template v-else-if="column.key === 'actions'">
                <Dropdown placement="bottomRight" width="48">
                    <template #trigger>
                        <button aria-label="Actions">
                            <EllipsisOutlined />
                        </button>
                    </template>
                    <template #content>
                        <DropdownButton
                            class="flex items-center gap-2"
                            @click="$emit('row-edit', record as Company)"
                        >
                            <EditOutlined /> Edit Company
                        </DropdownButton>
                        <DropdownButton
                            class="flex items-center gap-2 text-red-600 hover:text-red-800"
                            @click="$emit('row-delete', record as Company)"
                        >
                            <DeleteOutlined />
                            Delete Company
                        </DropdownButton>
                    </template>
                </Dropdown>
            </template>
        </template>
    </Table>
</template>
