import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { ConfigProvider, message } from 'ant-design-vue';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { configProviderProps } from './config/antd';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

/**
 * Configure Ant Design message globally.
 * Sets the top position and duration for message display.
 */
message.config({
    top: '20px',
    duration: 5,
});

/**
 * Creates an Inertia.js app.
 */
createInertiaApp({
    /**
     * Function to generate the document title.
     * @param {string} title - The current page title.
     * @returns {string} The formatted document title.
     */
    title: (title) => `${title} - ${appName}`,
    /**
     * Function to resolve page components.
     * @param {string} name - The name of the page component to resolve.
     * @returns {Promise<DefineComponent>} A promise that resolves to the page component.
     */
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
        ),
    /**
     * Function to set up the Vue app.
     * @param {object} setupOptions - Options containing the root element, App component, props, and plugin.
     * @param {HTMLElement} setupOptions.el - The root HTML element to mount the app to.
     * @param {import('vue').Component} setupOptions.App - The root Inertia App component.
     * @param {object} setupOptions.props - The initial page props.
     * @param {object} setupOptions.plugin - The Inertia Vue plugin.
     */
    setup({ el, App, props, plugin }) {
        createApp({
            render: () =>
                h(ConfigProvider, configProviderProps, () => h(App, props)),
        })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    /**
     * Configuration for the progress bar.
     * @property {string} color - The color of the progress bar.
     */
    progress: {
        color: '#4B5563',
    },
});
