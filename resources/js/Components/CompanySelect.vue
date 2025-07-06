<script setup lang="ts">
import Select from '@/Components/Select.vue';
import { Company } from '@/types';
import { SelectValue } from 'ant-design-vue/es/select';
import axios from 'axios';
import { debounce, find } from 'lodash';
import { computed, ref, watch } from 'vue';

/**
 * Props interface for the CompanySelect component.
 */
interface Props {
    /** Placeholder text for the select input. */
    placeholder?: string;
    /** Initial selected company data to pre-populate the select and avoid immediate fetching. */
    initialCompany?: Company | null;
}

/**
 * Defines the v-model for the component, representing the selected company's ID.
 * Can be a number (company ID), null, or undefined.
 */
const model = defineModel<number | null | undefined>();

/**
 * Computed property to handle the model value for the Ant Design Vue Select component.
 * It converts `null` to `undefined` for the Select component's `v-model:value`
 * as `Select` does not directly support `null` values for unselected state.
 */
const selectValue = computed({
    get: () => model.value ?? undefined,
    set: (value) => {
        model.value = value ?? null;
    },
});

/**
 * Defines the component's props with default values.
 * @type {Readonly<Props>}
 */
const props = withDefaults(defineProps<Props>(), {
    placeholder: 'Select a company',
    initialCompany: null,
});

/** Reactive reference to store the list of companies fetched from the API. */
const companies = ref<Company[]>([]);
/** Reactive reference to cache fetched companies by search term to avoid redundant API calls. */
const cachedCompanies = ref<Map<string, Company[]>>(new Map());
/** Reactive reference to store the currently selected company object. */
const selectedCompany = ref<Company | null>(props.initialCompany || null);
/** Reactive reference to store the current search term for filtering companies. */
const searchTerm = ref<string>('');

/**
 * Computed property that transforms the `companies` array into an array of options
 * suitable for the Ant Design Vue Select component.
 */
const options = computed(() => {
    const available = companies.value.map((company) => ({
        label: company.name,
        value: company.id,
    }));

    // If an selected company is provided, ensure it is included in the options.
    if (
        !searchTerm.value &&
        selectedCompany.value &&
        !find(available, ['value', selectedCompany.value.id])
    ) {
        available.push({
            label: selectedCompany.value.name,
            value: selectedCompany.value.id,
        });
    }

    return available;
});

/**
 * Fetches companies from the API based on a search term.
 * @param {string} [search=''] - The search term to filter companies.
 * @returns {Promise<void>} A promise that resolves when the fetch operation is complete.
 */
const fetchCompanies = async (search: string = '') => {
    if (cachedCompanies.value.has(search)) {
        companies.value = cachedCompanies.value.get(search)!;
        return;
    }

    try {
        const response = await axios.get(route('internal.companies.options'), {
            params: {
                search: search || undefined,
                per_page: 20,
            },
        });

        companies.value = response.data.data;
        cachedCompanies.value.set(search, response.data.data);
    } catch (error) {
        console.warn('Error fetching companies:', error);
        companies.value = [];
    }
};

/**
 * Handles search input changes with debouncing to limit API calls.
 * Updates the `searchTerm` and triggers `fetchCompanies`.
 * @param {string} value - The current search input value.
 */
const handleSearch = debounce((value: string) => {
    searchTerm.value = value;
    fetchCompanies(value);
}, 300);

/**
 * Handles the change event of the Ant Design Vue Select component.
 * Updates the `selectedCompany` ref based on the selected value.
 * @param {SelectValue} value - The value of the selected option (company ID).
 */
const handleChange = (value: SelectValue) => {
    selectedCompany.value = find(companies.value, ['id', value]) || null;
};

/**
 * Handles the dropdown visibility change event for the Ant Design Vue Select component.
 * When the dropdown closes, it clears the search term and companies list.
 * When it opens and no companies are loaded, it triggers an initial fetch.
 * @param {boolean} open - True if the dropdown is open, false otherwise.
 */
const handleDropdownVisibleChange = (open: boolean) => {
    if (!open) {
        searchTerm.value = '';
        companies.value = [];
    } else if (companies.value.length === 0) {
        fetchCompanies();
    }
};

/**
 * Watches for changes in the `initialCompany` prop.
 * When `initialCompany` changes, it updates the `selectedCompany` ref to reflect the new initial value.
 * This ensures the component's internal state is synchronized with external prop changes.
 */
watch(
    () => props.initialCompany,
    (newCompany) => {
        selectedCompany.value = newCompany;
    },
);
</script>

<template>
    <Select
        v-model:value="selectValue"
        :options="options"
        :placeholder="placeholder"
        :filter-option="false"
        show-search
        allow-clear
        @change="handleChange"
        @search="handleSearch"
        @dropdown-visible-change="handleDropdownVisibleChange"
    />
</template>

<style lang="css" scoped>
:deep([type='search']):focus {
    box-shadow: none;
}
</style>
