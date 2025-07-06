<script setup lang="ts">
import { PlusOutlined } from '@ant-design/icons-vue';
import { Head, router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { ref, watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { Employee, EmployeeFilters, PaginatedData } from '@/types';
import EmployeeForm from './Partials/EmployeeForm.vue';
import EmployeeTable from './Partials/EmployeeTable.vue';
import DeleteEmployeeConfirmation from './Partials/DeleteEmployeeConfirmation.vue';
import CompanyDetail from './Partials/CompanyDetail.vue';

/**
 * Props for the Employee Index component.
 * @interface Props
 */
interface Props {
    /** The paginated data of employees. */
    employees: PaginatedData<Employee>;
    /** Filter parameters for pagination, search, and sorting */
    filters?: EmployeeFilters;
}

const props = defineProps<Props>();

/** Search input value, initialized from filters or empty string. */
const search = ref(props.filters?.search || '');
/** Currently selected employee for editing, or null if creating a new one. */
const selectedEmployee = ref<Employee | null>(null);
/** Loading state for the table during data fetching operations. */
const isLoading = ref(false);
/** Visibility state for the employee form modal. */
const isEmployeeFormOpen = ref(false);
/** Visibility state for the delete confirmation modal. */
const isDeleteConfirmationOpen = ref(false);
/** Visibility state for the company detail modal. */
const isCompanyDetailOpen = ref(false);

/**
 * Fetches employee data based on provided parameters.
 * @param {Record<string, any>} params - The parameters for fetching employees (e.g., page, per_page, search, sort_by, sort_direction).
 */
const fetchEmployees = (params: Record<string, any>) => {
    router.get(route('employees.index'), params, {
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
    fetchEmployees(params);
}, 300);

/**
 * Handles the change event of the Employee Table component.
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
        search: search.value,
    };

    const currentSorter = Array.isArray(sorter) ? sorter[0] : sorter;

    if (currentSorter && currentSorter.field && currentSorter.order) {
        params.sort_by = currentSorter.field;
        params.sort_direction =
            currentSorter.order === 'ascend' ? 'asc' : 'desc';
    }

    fetchEmployees(params);
};

/**
 * Handles the edit event from the EmployeeTable component.
 * Sets the selected employee and makes the form visible for editing.
 * @param {Employee} employee - The employee object to be edited.
 */
const handleTableRowEdit = (employee: Employee) => {
    selectedEmployee.value = employee;
    isEmployeeFormOpen.value = true;
};

/**
 * Handles the delete event from the EmployeeTable component.
 * Sets the selected employee and makes the delete confirmation modal visible.
 * @param {Employee} employee - The employee object to be deleted.
 */
const handleTableRowDelete = (employee: Employee) => {
    selectedEmployee.value = employee;
    isDeleteConfirmationOpen.value = true;
};

/**
 * Handles the close event from the EmployeeForm component.
 * Hides the form and clears the selected employee.
 */
const handleEmployeeFormClose = () => {
    isEmployeeFormOpen.value = false;
    selectedEmployee.value = null;
};

/**
 * Handles the company detail event from the EmployeeTable component.
 * Sets the selected employee and makes the company detail modal visible.
 * @param {Employee} employee - The employee object whose company is to be viewed.
 */
const handleTableRowCompanyDetail = (employee: Employee) => {
    selectedEmployee.value = employee;
    isCompanyDetailOpen.value = true;
};

/**
 * Handles the close event from the CompanyDetail component.
 * Hides the company detail modal and clears the selected employee.
 */
const handleCompanyDetailClose = () => {
    isCompanyDetailOpen.value = false;
    selectedEmployee.value = null;
};

/**
 * Watches the search input for changes and triggers the debounced search handler.
 * This enables real-time search functionality as the user types.
 */
watch(search, handleSearch);
</script>

<template>
    <Head title="Employees" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Employees
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
                <div
                    class="flex flex-col items-center justify-between gap-2 sm:flex-row"
                >
                    <PrimaryButton
                        class="w-full justify-center gap-2 sm:order-1 sm:w-auto"
                        @click="isEmployeeFormOpen = true"
                    >
                        <PlusOutlined /> Create Employee
                    </PrimaryButton>
                    <TextInput
                        v-model="search"
                        type="search"
                        placeholder="Search employees..."
                        class="w-full sm:w-1/2 md:w-1/3"
                    />
                </div>
                <EmployeeTable
                    :employees="employees"
                    :isLoading="isLoading"
                    :filters="filters"
                    @change="handleTableChange"
                    @row-edit="handleTableRowEdit"
                    @row-delete="handleTableRowDelete"
                    @row-company-detail="handleTableRowCompanyDetail"
                />
                <EmployeeForm
                    :employee="selectedEmployee"
                    :is-open="isEmployeeFormOpen"
                    @close="handleEmployeeFormClose"
                />
                <DeleteEmployeeConfirmation
                    :is-open="isDeleteConfirmationOpen"
                    :employee="selectedEmployee"
                    @close="isDeleteConfirmationOpen = false"
                />
                <CompanyDetail
                    :is-open="isCompanyDetailOpen"
                    :company="selectedEmployee?.company"
                    @close="handleCompanyDetailClose"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
