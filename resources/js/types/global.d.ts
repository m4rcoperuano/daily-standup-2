import type { Page } from '@inertiajs/core';
import { AxiosInstance } from 'axios';
import { Config as ZiggyConfig, route as ZiggyRoute } from 'ziggy-js';
import { RawParameterValue } from '../../../vendor/tightenco/ziggy';

declare global {
    interface Window {
        axios: AxiosInstance;
    }

    let route: typeof ZiggyRoute;

    let Ziggy: ZiggyConfig;

    let axios: AxiosInstance;

    type StringOrNumber = RawParameterValue;
}

declare module 'vue' {
    interface ComponentCustomProperties {
        route: typeof ZiggyRoute;
    }
}


declare module '@inertiajs/core' {
    interface PageProps extends Page<PageProps> {
        user: inertia.User
        jetstream: inertia.Jetstream
        errors: inertia.Errors
        errorBags: inertia.ErrorBags
        flash: inertia.Flash
    }
}

declare module '@inertiajs/vue3' {
    export function usePage<T>(): Page<T>
}
