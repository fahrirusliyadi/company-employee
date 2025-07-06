import type { Employee, EmployeeFilters, PaginatedData } from '@/types';
import type { ColumnsType } from 'ant-design-vue/es/table';
import { computed, Ref, type ComputedRef } from 'vue';

/**
 * Composable for employee table columns configuration.
 * @param filters - Reactive filters for sorting
 * @param meta - Reactive meta for index calculation
 * @param visibleColumns - Reactive array of visible column keys
 * @returns Object containing all columns and filtered columns
 */
export function useEmployeeTableColumns(
    filters?: ComputedRef<EmployeeFilters | undefined>,
    meta?: ComputedRef<PaginatedData<unknown>['meta'] | undefined>,
    visibleColumns?: Ref<string[]>,
) {
    const sortOrder = (key: string) =>
        filters?.value?.sort_by === key
            ? filters.value.sort_direction === 'asc'
                ? 'ascend'
                : 'descend'
            : null;

    /**
     * All available table columns configuration.
     */
    const allColumns = computed<ColumnsType<Employee>>(() => [
        {
            title: '#',
            key: 'index',
            width: 56,
            customRender: ({ index }: { index: number }) => {
                if (!meta?.value) return index + 1;
                return (
                    (meta.value.current_page - 1) * meta.value.per_page +
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
            sortOrder: sortOrder('name'),
            customRender: ({ record }: { record: Employee }) => {
                return record.first_name + ' ' + record.last_name;
            },
        },
        {
            title: 'Company',
            key: 'company',
        },
        {
            title: 'Email',
            dataIndex: 'email',
            key: 'email',
            sorter: true,
            ellipsis: true,
            sortOrder: sortOrder('email'),
        },
        {
            title: 'Phone',
            dataIndex: 'phone',
            key: 'phone',
            ellipsis: true,
            sorter: true,
            sortOrder: sortOrder('phone'),
        },
        {
            key: 'actions',
            width: 56,
        },
    ]);

    /**
     * Filtered columns based on visibility settings.
     */
    const columns = computed<ColumnsType<Employee>>(() => {
        if (!visibleColumns?.value) return allColumns.value;
        return allColumns.value.filter((column) =>
            visibleColumns.value.includes(column.key as string),
        );
    });

    return {
        allColumns,
        columns,
    };
}
