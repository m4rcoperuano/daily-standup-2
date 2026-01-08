<script setup lang="ts">
  import { computed, onMounted, ref, watch } from 'vue';
  import { useApi } from '@/useApi';
  import SprintDaysRemaining from '@/Pages/StandUpGroups/Partials/SprintDaysRemaining.vue';
  import { DateTime } from 'luxon';
  import { useIntegrationsStore } from '@/Stores/integrationsStore.js';
  import ConnectToJira from '@/Components/Integrations/ConnectToJira.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import RichTextEditor from '@/Components/RichTextEditor.vue';
  import Starfield from '@/Components/Starfield.vue';

  const api = useApi();
  const integrations = useIntegrationsStore();

  const props = defineProps( {
    sprintId: {
      type: [ Number, String ],
      required: true,
    },
    sprintName: {
      type: String,
      required: true,
    },
    standUpGroupId: {
      type: [ Number, String ],
      required: true,
    },
  } );

  const sprint = ref( null );
  const summaryData = ref( null );
  const isFetchingSummary = ref( false );
  const initiateSummary = async () => {
    isFetchingSummary.value = true;
    const response = await axios.get( route( 'stand-up-entries.export', { standUpGroup: props.standUpGroupId } ) );
    summaryData.value = response.data;
    isFetchingSummary.value = false;
  };

  const isFetchingSprint = ref( false );
  onMounted( async () => {
    isFetchingSprint.value = true;
    await integrations.fetchIntegrations();
    if ( integrations.hasIntegration( 'atlassian', '2.0.0' ) ) {
      sprint.value = ( await api.integrations.jira.sprint( props.sprintId ) ).result.data;
    }
    isFetchingSprint.value = false;
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
  <div class="backdrop-blur-md p-5 rounded-lg bg-secondary/20">
    <h2 class="font-semibold text-xl bg-gradient-to-r text-primary text-center mb-2">
      {{ sprintName }}
    </h2>

    <div
      v-if="isFetchingSprint"
      style="height:150px;"
      >
      <Starfield title="Let's go JIRA!"></Starfield>
    </div>

    <SprintDaysRemaining
      v-else-if="sprint"
      :start-date="sprintStartDate"
      :end-date="sprintEndDate"
      :goal="sprint.goal"
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
    <div class="mt-4 text-center">
      <Starfield
        v-if="isFetchingSummary"
        title="Going supernova..."
        ></Starfield>
      <primary-button
        v-else
        @click="initiateSummary"
        >
        {{ isFetchingSummary ? 'Generating Summary...' : 'Generate Summary' }}
      </primary-button>
    </div>
    <div
      v-if="summaryData"
      class="content"
      >
      <RichTextEditor
        :model-value="summaryData"
        ></RichTextEditor>
    </div>
  </div>
</template>

<style scoped>

</style>
