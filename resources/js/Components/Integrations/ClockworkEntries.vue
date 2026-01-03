<script setup lang="ts">
  import CopyTextButton from '@/Components/CopyTextButton.vue';
  import { computed, onMounted, ref, watch } from 'vue';
  import { DateTime } from 'luxon';
  import DateAwareDatePicker from '@/Components/DateAwareDatePicker.vue';
  import { useApi } from '@/useApi';
  import { useIntegrationsStore } from '@/Stores/integrationsStore';
  import Starfield from '@/Components/Starfield.vue';
  const api = useApi();
  const integrationsStore = useIntegrationsStore();

  const recentWork = ref( [] );
  const isFetchingRecentWork = ref( false );
  const jiraBaseUrl = ref( null );

  const initialDateCalc =  () => {
    const now = DateTime.now().setZone( Intl.DateTimeFormat().resolvedOptions().timeZone );
    let targetDate = now.minus( { days: 1 } );

    // If the target date is Saturday (6) or Sunday (7), adjust to previous Friday
    if ( targetDate.weekday === 6 ) {
      targetDate = targetDate.minus( { days: 1 } ); // Saturday to Friday
    }
    else if ( targetDate.weekday === 7 ) {
      targetDate = targetDate.minus( { days: 2 } ); // Sunday to Friday
    }

    return targetDate.toFormat( 'yyyy-MM-dd' );
  };

  const date = ref( initialDateCalc() );

  const formattedDate = computed( () => {
    // Parse as ISO date and format as 'Monday, March 3rd, 2025'
    return DateTime.fromISO( date.value ).toFormat( 'cccc, LLLL d\',\' yyyy' ).replace( /\b(\d{1,2})\b/, ( d ) => {
      // Add ordinal suffix
      const n = parseInt( d );
      if ( n > 3 && n < 21 ) return n + 'th';
      switch ( n % 10 ) {
        case 1: return n + 'st';
        case 2: return n + 'nd';
        case 3: return n + 'rd';
        default: return n + 'th';
      }
    } );
  } );

  const createCopyableText = ( recentWork ) => {
    if ( !Array.isArray( recentWork ) ) return '';
    return recentWork.map( work => {
      const issueUrl = `${jiraBaseUrl.value}/browse/${work.issueKey}`;
      return `[${issueUrl}](${issueUrl})\n${work.comment}`;
    } ).join( '\n\n' );
  };

  let timeoutId: ReturnType<typeof setTimeout> | null = null;

  watch( () => date.value, () => {
    if ( date.value === null ) return;

    isFetchingRecentWork.value = true;
    if ( timeoutId ) {
      clearTimeout( timeoutId );
    }
    //debounce for 500ms
    timeoutId = setTimeout( async() => {
      await fetchTimeEntries();
    }, 500 );
  } );

  const fetchTimeEntries = async () => {
    recentWork.value = [];
    isFetchingRecentWork.value = true;

    const email = integrationsStore.integrations.filter( x => x.provider === 'atlassian' )[0].email;
    const response = await api.integrations.clockwork.query( email, date.value );

    jiraBaseUrl.value = response.result.data.base_url;
    recentWork.value = response.result.data.data.filter( x => !!x.comment )
      .map( x => ( {
        id: x.id,
        comment: x.comment,
        issueType: x.issue.fields.issuetype,
        issueKey: x.issue.key,
        issueSummary: x.issue.fields.summary,
      } ) );

    isFetchingRecentWork.value = false;
  };

  onMounted( async () => {
    if ( integrationsStore.hasIntegration( 'atlassian' ) ) {
      await fetchTimeEntries();
    }
  } );
</script>

<template>
  <div class="text-teal-100 mt-4 p-3 relative bg-primary/10 backdrop-blur-md rounded-lg flex flex-col min-h-[48px]">
    <h3 class="text-xl font-bold pb-2">Clockwork</h3>
    <div class="border-teal-600 border-b"></div>
    <div class="py-4">
      <div class="uppercase text-sm font-bold mb-1">
        SELECT A DATE
      </div>
      <DateAwareDatePicker
        v-model="date"
        ></DateAwareDatePicker>
    </div>
    <div class="border-teal-600 border-b mb-4"></div>

    <div
      class="uppercase text-sm font-bold mb-1"
      >
      {{ formattedDate }}
    </div>
    <div
      v-if="isFetchingRecentWork"
      style="height:150px;overflow: hidden;"
      >
      <Starfield></Starfield>
    </div>
    <div
      v-else-if="recentWork.length === 0"
      style="height:150px;"
      class="bg-teal-900 flex items-center justify-center rounded rounded-lg"
      >
      <span>No clockwork entries found for this selected date</span>
    </div>
    <div
      v-else
      class="relative"
      >
      <copy-text-button
        v-if="!isFetchingRecentWork"
        class="absolute right-4"
        style="top:-1.6rem"
        :text="createCopyableText(recentWork)"
        ></copy-text-button>
      <div
        v-for="(work, index) in recentWork"
        :key="work.id"
        >
        <div :class="{ 'mb-3': index < recentWork.length -1 }">
          <div>
            <img
              :src="work.issueType.iconUrl"
              :alt="work.issueType.name"
              class="inline-block size-5 mr-2 align-middle "
              />
            <strong class="text-white">{{ work.issueKey }}: {{ work.issueSummary }}</strong>:
          </div>
          <div>
            {{ work.comment }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
