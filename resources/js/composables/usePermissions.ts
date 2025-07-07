import { PageProps } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

/**
 * Composable for checking user permissions
 * Uses permissions data shared from Laravel via Inertia
 */
export function usePermissions() {
    const page = usePage<PageProps>();

    /**
     * Get all user permissions
     */
    const permissions = computed(() => {
        return (page.props.auth.permissions as string[]) || [];
    });

    /**
     * Check if user has a specific permission
     */
    const hasPermission = (permission: string): boolean => {
        return permissions.value.includes(permission);
    };

    /**
     * Check if user has any of the specified permissions
     */
    const hasAnyPermission = (permissionsList: string[]): boolean => {
        return permissionsList.some((permission) => hasPermission(permission));
    };

    return {
        permissions,
        hasPermission,
        hasAnyPermission,
    };
}
