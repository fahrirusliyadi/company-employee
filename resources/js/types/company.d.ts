import type { ListFilters } from './index';

export interface Company {
    id: number;
    name: string | null;
    email: string | null;
    website: string | null;
    logo: {
        url: string;
        '50x50': string;
    } | null;
    created_at: string | null;
    updated_at: string | null;
}

/**
 * Company-specific filter interface that extends the base ListFilters.
 * Demonstrates how to extend the reusable filter type for specific use cases.
 */
export interface CompanyFilters extends ListFilters {
    /** Field to sort by - restricted to company-specific fields */
    sort_by?: 'name' | 'email' | 'website' | 'created_at' | 'updated_at';
}
