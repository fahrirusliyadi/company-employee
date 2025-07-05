import { usePage } from '@inertiajs/vue3';
import { message } from 'ant-design-vue';
import { onMounted, watch } from 'vue';

export interface FlashMessages {
    success?: string;
    error?: string;
    info?: string;
    warning?: string;
}

/**
 * Composable for handling flash messages from Laravel session
 * Automatically displays flash messages using Ant Design components
 */
export function useFlashMessages() {
    const page = usePage();

    /**
     * Handle flash messages from Laravel session
     */
    const handleFlashMessages = () => {
        const flash = page.props.flash as FlashMessages;

        if (flash?.success) {
            message.success(flash.success);
        }

        if (flash?.error) {
            message.error(flash.error);
        }

        if (flash?.info) {
            message.info(flash.info);
        }

        if (flash?.warning) {
            message.warning(flash.warning);
        }
    };

    /**
     * Watch for changes in flash messages (for SPA navigation)
     */
    watch(
        () => page.props.flash,
        () => {
            handleFlashMessages();
        }
    );

    /**
     * Handle initial flash messages on component mount
     */
    onMounted(() => {
        handleFlashMessages();
    });

    return {
        handleFlashMessages,
    };
}
