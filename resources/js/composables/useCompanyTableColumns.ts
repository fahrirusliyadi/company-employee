import type { Company, CompanyFilters, PaginatedData } from '@/types';
import type { ColumnsType } from 'ant-design-vue/es/table';
import { computed, Ref, type ComputedRef } from 'vue';
import { usePermissions } from '@/composables/usePermissions';

/**
 * Composable for company table columns configuration.
 * @param filters - Reactive filters for sorting
 * @param meta - Reactive meta for index calculation
 * @param visibleColumns - Reactive array of visible column keys
 * @returns Object containing all columns and filtered columns
 */
export function useCompanyTableColumns(
    filters?: ComputedRef<CompanyFilters | undefined>,
    meta?: ComputedRef<PaginatedData<unknown>['meta'] | undefined>,
    visibleColumns?: Ref<string[]>,
) {
    const { hasAnyPermission } = usePermissions();

    const sortOrder = (key: string) =>
        filters?.value?.sort_by === key
            ? filters.value.sort_direction === 'asc'
                ? 'ascend'
                : 'descend'
            : null;

    /**
     * All available table columns configuration.
     */
    const allColumns = computed<ColumnsType<Company>>(() => {
        const columns: ColumnsType<Company> = [
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
                title: 'Name',
                dataIndex: 'name',
                key: 'name',
                sorter: true,
                sortOrder: sortOrder('name'),
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
                title: 'Website',
                dataIndex: 'website',
                key: 'website',
                ellipsis: true,
                sorter: true,
                sortOrder: sortOrder('website'),
            },
        ];

        if (hasAnyPermission(['update-companies', 'delete-companies'])) {
            columns.push({
                key: 'actions',
                width: 56,
            });
        }
        return columns;
    });

    /**
     * Filtered columns based on visibility settings.
     */
    const columns = computed<ColumnsType<Company>>(() => {
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
