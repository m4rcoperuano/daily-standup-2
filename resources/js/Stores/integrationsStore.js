import { defineStore } from 'pinia';
import { useApi } from '@/useApi.ts';

export const useIntegrationsStore = defineStore( 'integrationsStore', {
  state: () => ( {
    integrations: [],
    integrationsLoading: true,
    teamHasClockworkIntegration: false,
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
      const api = useApi();
      const response = await axios.get( route( 'socialite.index' ) );
      this.integrations = response.data;
      this.teamHasClockworkIntegration = ( await api.integrations.clockwork.has() ).result.data.has_integration;
      this.integrationsLoading = false;
    },
  },
} );
