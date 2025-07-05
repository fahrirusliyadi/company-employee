export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
};

export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface PaginatedData<T> {
    data: T[];
    links: {
        first: string;
        last: string;
        prev: string | null;
        next: string | null;
    };
    meta: {
        current_page: number;
        from: number;
        last_page: number;
        links: PaginationLink[];
        path: string;
        per_page: number;
        to: number;
        total: number;
    };
}

/**
 * Generic filter interface for listing pages with pagination, search, and sorting.
 * Can be extended with additional filter properties for specific use cases.
 */
export interface ListFilters {
    /** Current page number for pagination */
    page?: number;
    /** Number of items per page */
    per_page?: number;
    /** Search query string */
    search?: string;
    /** Field to sort by */
    sort_by?: string;
    /** Sort direction */
    sort_direction?: 'asc' | 'desc';
}

export * from './company';