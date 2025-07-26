import type { ListFilters } from './index';

export interface Company {
    id: number;
    name: string | null;
    email: string | null;
    website: string | null;
    logo: {
        url: string;
        '50x50': string;
        autox140: string;
    } | null;
}

/**
 * Company-specific filter interface that extends the base ListFilters.
 * Demonstrates how to extend the reusable filter type for specific use cases.
 */
export interface CompanyFilters extends ListFilters {
    /** Field to sort by - restricted to company-specific fields */
    sort_by?: 'name' | 'email' | 'website';
}
