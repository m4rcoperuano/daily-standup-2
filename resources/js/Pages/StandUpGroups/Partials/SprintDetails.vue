<script setup lang="ts">
  import { computed, onMounted, ref, watch } from 'vue';
  import { useApi } from '@/useApi';
  import SprintDaysRemaining from '@/Pages/StandUpGroups/Partials/SprintDaysRemaining.vue';
  import { DateTime } from 'luxon';
  import { useIntegrationsStore } from '@/Stores/integrationsStore.js';
  import ConnectToJira from '@/Components/Integrations/ConnectToJira.vue';

  const api = useApi();
  const integrations = useIntegrationsStore();

  const props = defineProps( {
    sprintId: {
      type: [ Number, String ],
      required: true,
    },
  } );

  const sprint = ref( null );

  onMounted( async () => {
    await integrations.fetchIntegrations();
    if ( integrations.hasIntegration( 'atlassian', '2.0.0' ) ) {
      sprint.value = ( await api.integrations.jira.sprint( props.sprintId ) ).result.data;
    }
  } );

  watch( () => integrations.integrations, async () => {
    if ( integrations.hasIntegration( 'atlassian', '2.0.0' ) ) {
      sprint.value = ( await api.integrations.jira.sprint( props.sprintId ) ).result.data;
    }
  } );

  const sprintStartDate = computed( () => DateTime.fromISO( sprint.value?.startDate ).startOf( 'day' ).toJSDate() );
  const sprintEndDate = computed( () => DateTime.fromISO( sprint.value?.endDate ).endOf( 'day' ).toJSDate() );
</script>

<template>
  <div>
    <SprintDaysRemaining
      v-if="sprint"
      :start-date="sprintStartDate"
      :end-date="sprintEndDate"
      ></SprintDaysRemaining>
    <div
      v-else-if="integrations.hasIntegration( 'atlassian', '1.0.0' )"
      class="relative"
      >
      <div>
        <ConnectToJira
          current-version="1.0.0"
          upgrade
          @user-connected="integrations.fetchIntegrations()"
          >
        </ConnectToJira>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
