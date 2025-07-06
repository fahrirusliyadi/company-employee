import type { ConfigProviderProps } from 'ant-design-vue/es/config-provider/context';

/**
 * Configuration properties for Ant Design Vue's ConfigProvider.
 * This object defines global theme settings, such as token values.
 *
 * @type {ConfigProviderProps}
 */
export const configProviderProps: ConfigProviderProps = {
    theme: {
        token: {
            controlHeight: 42,
            colorPrimary: '#6366f1',
            controlOutline: '#6366f1',
            controlOutlineWidth: 1,
        },
    },
};
