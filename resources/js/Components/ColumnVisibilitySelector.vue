<script setup lang="ts">
import Dropdown from '@/Components/Dropdown.vue';
import { DownOutlined } from '@ant-design/icons-vue';
import { Checkbox } from 'ant-design-vue';
import type { CheckboxChangeEvent } from 'ant-design-vue/es/checkbox/interface';
import type { ColumnsType } from 'ant-design-vue/es/table';
import { computed } from 'vue';
import SecondaryButton from './SecondaryButton.vue';

/**
 * Props for the Column Visibility Selector component.
 * @interface Props
 */
interface Props {
    /** Array of table column definitions */
    columns: ColumnsType<any>;
}

/**
 * Defines the props for the component.
 * @type {Readonly<Props>}
 */
const props = defineProps<Props>();

/**
 * Defines the v-model for the component, representing the visible columns.
 * @type {Ref<string[]>}
 */
const modelVisibleColumns = defineModel<string[]>('visibleColumns', {
    default: () => []
})

/**
 * Computed property for hideable columns only.
 * Filters out columns that don't have a title (like actions) or are system columns.
 * @returns {Array} Array of hideable column configurations.
 */
const hideableColumns = computed(() =>
    props.columns.filter(
        (column) =>
            column.title &&
            column.key !== 'actions' &&
            typeof column.title === 'string',
    ),
);

/**
 * Computed property to check if a column is currently visible.
 * @param {string} columnKey - The key of the column to check.
 * @returns {boolean} True if the column is visible, false otherwise.
 */
const isColumnVisible = (columnKey: string): boolean => {
    return modelVisibleColumns.value.includes(columnKey);
};

/**
 * Handles the change event when a column's visibility is toggled.
 * Updates the visible columns array and emits the change.
 * @param {string} columnKey - The key of the column being toggled.
 * @param {CheckboxChangeEvent} e - The checkbox change event.
 */
const handleColumnToggle = (columnKey: string, e: CheckboxChangeEvent) => {
    const visible = e.target.checked;
    let newVisibleColumns: string[];

    if (visible) {
        // Add column to visible list if not already present
        newVisibleColumns = modelVisibleColumns.value.includes(columnKey)
            ? modelVisibleColumns.value
            : [...modelVisibleColumns.value, columnKey];
    } else {
        // Remove column from visible list
        newVisibleColumns = modelVisibleColumns.value.filter(
            (key) => key !== columnKey,
        );
    }

    modelVisibleColumns.value = newVisibleColumns;
};
</script>

<template>
    <Dropdown align="left" width="48" content-classes="py-2 bg-white">
        <template #trigger>
            <SecondaryButton
                class="gap-2 normal-case"
                aria-label="Column visibility settings"
            >
                Columns
                <DownOutlined class="text-xs" />
            </SecondaryButton>
        </template>

        <template #content>
            <div class="space-y-2 px-4 py-2">
                <div
                    v-for="column in hideableColumns"
                    :key="column.key"
                    class="flex items-center gap-2"
                    @click.stop
                >
                    <Checkbox
                        :id="`column-${column.key}`"
                        :checked="isColumnVisible(column.key as string)"
                        @change="
                            (e: CheckboxChangeEvent) =>
                                handleColumnToggle(column.key as string, e)
                        "
                    />
                    <label :for="`column-${column.key}`" class="grow text-sm cursor-pointer">
                        {{ column.title }}
                    </label>
                </div>
            </div>
        </template>
    </Dropdown>
</template>
