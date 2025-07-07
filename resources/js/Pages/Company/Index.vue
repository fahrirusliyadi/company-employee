<script setup lang="ts">
import ColumnVisibilitySelector from '@/Components/ColumnVisibilitySelector.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useCompanyTableColumns } from '@/composables/useCompanyTableColumns';
import type { Company, CompanyFilters, PaginatedData } from '@/types';
import { PlusOutlined } from '@ant-design/icons-vue';
import { Head, router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { usePermissions } from '@/composables/usePermissions';
import { computed, ref, watch } from 'vue';
import CompanyForm from './Partials/CompanyForm.vue';
import CompanyTable from './Partials/CompanyTable.vue';
import DeleteCompanyConfirmation from './Partials/DeleteCompanyConfirmation.vue';

/**
 * Props for the Company Index component.
 * @interface Props
 */
interface Props {
    /** The paginated data of companies. */
    companies: PaginatedData<Company>;
    /** Filter parameters for pagination, search, and sorting */
    filters?: CompanyFilters;
}

const props = defineProps<Props>();
/** Search input value, initialized from filters or empty string. */
const search = ref(props.filters?.search || '');
/** Currently selected company for editing, or null if creating a new one. */
const selectedCompany = ref<Company | null>(null);
/** Loading state for the table during data fetching operations. */
const isLoading = ref(false);
/** Visibility state for the company form modal. */
const isCompanyFormOpen = ref(false);
/** Visibility state for the delete confirmation modal. */
const isDeleteConfirmationOpen = ref(false);
/** Array of visible column keys for the company table. */
const visibleColumns = ref(['index', 'name', 'email', 'website', 'actions']);
/** Use permissions composable to check user permissions. */
const { hasPermission } = usePermissions();
/** Use company table columns composable to get column definitions. */
const { allColumns, columns } = useCompanyTableColumns(
    computed(() => props.filters),
    computed(() => props.companies.meta),
    visibleColumns,
);

/**
 * Fetches company data based on provided parameters.
 * @param {Record<string, any>} params - The parameters for fetching companies (e.g., page, per_page, search, sort_by, sort_direction).
 */
const fetchCompanies = (params: Record<string, any>) => {
    router.get(route('companies.index'), params, {
        preserveState: true,
        preserveScroll: true,
        onStart: () => {
            isLoading.value = true;
        },
        onFinish: () => {
            isLoading.value = false;
        },
    });
};

/**
 * Debounced search handler that triggers when search input changes.
 * Resets pagination to first page and performs a new search with the current search term.
 * Uses a 300ms debounce delay to prevent excessive API calls during typing.
 */
const handleSearch = debounce(() => {
    const params: Record<string, any> = {
        ...props.filters,
        page: 1, // Reset to first page on search
        search: search.value,
    };
    fetchCompanies(params);
}, 300);

/**
 * Handles the change event of the Company Table component.
 * This function updates the URL with pagination and sorting parameters
 * and triggers a new Inertia.js request to fetch data.
 *
 * @param {any} pagination - The pagination object from the table.
 * @param {any} _filters - The filters object from the table (unused).
 * @param {any} sorter - The sorter object(s) from the table.
 */
const handleTableChange = (pagination: any, _filters: any, sorter: any) => {
    const params: Record<string, any> = {
        page: pagination.current,
        per_page: pagination.pageSize,
        search: search.value || undefined,
    };

    const currentSorter = Array.isArray(sorter) ? sorter[0] : sorter;

    if (currentSorter && currentSorter.field && currentSorter.order) {
        params.sort_by = currentSorter.field;
        params.sort_direction =
            currentSorter.order === 'ascend' ? 'asc' : 'desc';
    }

    fetchCompanies(params);
};

/**
 * Handles the edit event from the CompanyTable component.
 * Sets the selected company and makes the form visible for editing.
 * @param {Company} company - The company object to be edited.
 */
const handleTableRowEdit = (company: Company) => {
    selectedCompany.value = company;
    isCompanyFormOpen.value = true;
};

/**
 * Handles the delete event from the CompanyTable component.
 * Sets the selected company and makes the delete confirmation modal visible.
 * @param {Company} company - The company object to be deleted.
 */
const handleTableRowDelete = (company: Company) => {
    selectedCompany.value = company;
    isDeleteConfirmationOpen.value = true;
};

/**
 * Handles the close event from the CompanyForm component.
 * Hides the form and clears the selected company.
 */
const handleCompanyFormClose = () => {
    isCompanyFormOpen.value = false;
    selectedCompany.value = null;
};

/**
 * Watches the search input for changes and triggers the debounced search handler.
 * This enables real-time search functionality as the user types.
 */
watch(search, handleSearch);
</script>

<template>
    <Head title="Companies" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Companies
            </h2>
        </template>

        <div class="py-6 sm:py-8 xl:py-12">
            <div class="mx-auto max-w-7xl space-y-6 px-6 lg:px-8">
                <div class="flex flex-wrap items-center gap-2">
                    <TextInput
                        v-model="search"
                        type="search"
                        placeholder="Search companies..."
                        class="w-full sm:me-auto sm:w-1/3"
                    />
                    <ColumnVisibilitySelector
                        :columns="allColumns"
                        v-model:visible-columns="visibleColumns"
                    />
                    <PrimaryButton
                        v-if="hasPermission('create-companies')"
                        class="grow justify-center gap-2 sm:grow-0"
                        @click="isCompanyFormOpen = true"
                    >
                        <PlusOutlined /> Create Company
                    </PrimaryButton>
                </div>
                <CompanyTable
                    :companies="companies"
                    :isLoading="isLoading"
                    :columns="columns"
                    @change="handleTableChange"
                    @row-edit="handleTableRowEdit"
                    @row-delete="handleTableRowDelete"
                />
                <CompanyForm
                    :company="selectedCompany"
                    :is-open="isCompanyFormOpen"
                    @close="handleCompanyFormClose"
                />
                <DeleteCompanyConfirmation
                    :is-open="isDeleteConfirmationOpen"
                    :company="selectedCompany"
                    @close="isDeleteConfirmationOpen = false"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
