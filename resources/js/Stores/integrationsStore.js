import { defineStore } from 'pinia';


export const useIntegrationsStore = defineStore( 'integrationsStore', {
    state: () => ( {
        integrations: [],
        integrationsLoading: true,
    } ),
    getters: {
        hasIntegration: ( state ) => ( provider, version ) => {
            const integrations = state.integrations
                .filter( integration => integration.provider === provider );

            if ( version ) {
                return integrations.some( integration => integration.version === version );
            }

            return integrations.length > 0;
        },
    },
    actions: {
        setIntegrationsLoading( value ) {
            this.integrationsLoading = value;
        },
        async fetchIntegrations() {
            const response = await axios.get( route( 'socialite.index' ) );
            this.integrations = response.data;
            this.integrationsLoading = false;
        },
    },
} );
