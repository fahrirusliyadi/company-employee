import type { Company, ListFilters } from './index';

export interface Employee {
    id: number;
    company_id: number;
    first_name: string;
    last_name: string;
    email: string | null;
    phone: string | null;
    company?: Company;
}

/**
 * Employee-specific filter interface that extends the base ListFilters.
 * Demonstrates how to extend the reusable filter type for specific use cases.
 */
export interface EmployeeFilters extends ListFilters {
    /** Field to sort by - restricted to employee-specific fields */
    sort_by?: 'first_name' | 'email' | 'phone';
    /** Filter by company ID */
    company_id?: number;
}
