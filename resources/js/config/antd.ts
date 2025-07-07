import type { ConfigProviderProps } from 'ant-design-vue/es/config-provider/context';
import { colors } from './theme';

/**
 * Configuration properties for Ant Design Vue's ConfigProvider.
 * This object defines global theme settings, such as token values.
 *
 * @type {ConfigProviderProps}
 */
export const configProviderProps: ConfigProviderProps = {
    theme: {
        token: {
            colorTextBase: colors.gray[700],
            colorPrimary: colors.primary[500],
            colorTextPlaceholder: colors.gray[500],
            controlOutline: colors.primary[500],
            controlOutlineWidth: 1,
        },
    },
};
