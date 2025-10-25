<script setup lang="ts">
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import { computed, onMounted, Ref, ref, watch } from 'vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import DangerButton from '@/Components/DangerButton.vue';
  import { StandUpEntry } from '@/Pages/StandUps/standUpEntriesStore';
  import RichTextEditor from '@/Components/RichTextEditor.vue';
  import ConnectToJira from '@/Components/Integrations/ConnectToJira.vue';
  import ConnectToGithub from '@/Components/Integrations/ConnectToGithub.vue';
  import { useIntegrationsStore } from '@/Stores/integrationsStore.js';
  import ProTipAlert from '@/Components/ProTipAlert.vue';
  import { useApi } from '@/useApi';
  const api = useApi();
  import { DateTime } from 'luxon';
  import CopyTextButton from '@/Components/CopyTextButton.vue';

  const props = defineProps( {
    isEditing: {
      type: Boolean,
      default: false,
    },
    inProgress: {
      type: String,
      default: null,
    },
    priorities: {
      type: String,
      default: null,
    },
    blockers: {
      type: String,
      default: null,
    },
    date: {
      type: String,
      default: null,
    },
  } );

  const emits = defineEmits( [ 'save', 'cancel', 'delete' ] );
  const integrationsStore = useIntegrationsStore();
  const integrationConnectedSuccess = ref( false );

  const form : Ref<StandUpEntry> = ref( {
    in_progress: props.inProgress ?? '',
    priorities: props.priorities ?? '',
    blockers: props.blockers ?? '',
  } );

  const save = async () => {
    emits( 'save', form.value );
  };

  const cancel = async () => {
    emits( 'cancel' );
  };

  const doDelete = async () => {
    if ( confirm( 'are you sure you want to delete this entry?' ) ) {
      emits( 'delete' );
    }
  };

  const suggestAtlassianIntegration = computed( () => {
    return !integrationsStore.hasIntegration( 'atlassian' ) &&
      (
        form.value.in_progress.includes( 'atlassian.net' )
        || form.value.priorities.includes( 'atlassian.net' )
        || form.value.blockers.includes( 'atlassian.net' )
      );
  } );

  const suggestGithubIntegration = computed( () => {
    return !integrationsStore.hasIntegration( 'github' ) &&
      (
        form.value.in_progress.includes( 'github.com' )
        || form.value.priorities.includes( 'github.com' )
        || form.value.blockers.includes( 'github.com' )
      );
  } );

  const onUserConnectedIntegration = async ( provider ) => {
    integrationsStore.setIntegrationsLoading( true );
    await integrationsStore.fetchIntegrations();

    if ( integrationsStore.hasIntegration( provider ) ) {
      integrationConnectedSuccess.value = true;
    }
  };

  const recentWork = ref( [] );
  const isFetchingRecentWork = ref( false );
  const recentWorkDate = ref( '' );
  const jiraBaseUrl = ref( null );
  const fetchTimeEntries = async () => {
    recentWork.value = [];
    isFetchingRecentWork.value = true;

    const email = integrationsStore.integrations.filter( x => x.provider === 'atlassian' )[0].email;
    const response = await api.integrations.clockwork.query( email, props.date );

    recentWorkDate.value = response.result.data.date_used;
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

  onMounted( async() => {
    await integrationsStore.fetchIntegrations();
    await fetchTimeEntries();
  } );

  let timeoutId: ReturnType<typeof setTimeout> | null = null;

  watch( () => props.date, () => {
    if ( timeoutId ) {
      clearTimeout( timeoutId );
    }
    //debounce for 500ms
    timeoutId = setTimeout( async() => {
      await fetchTimeEntries();
    }, 500 );
  } );

  const formattedRecentWorkDate = computed( () => {
    if ( !recentWorkDate.value ) return '';
    // Parse as ISO date and format as 'Friday, March 3rd, 2025'
    return DateTime.fromISO( recentWorkDate.value ).toFormat( 'cccc, LLLL d\',\' yyyy' ).replace( /\b(\d{1,2})\b/, ( d ) => {
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
</script>

<template>
  <div>
    <div class="grid grid-cols-1 gap-4">
      <div class="text-gray-400 mt-4 p-3 relative bg-gray-900 rounded flex flex-col min-h-[48px]">
        <div
          v-if="!isFetchingRecentWork"
          class="uppercase text-sm font-bold mb-1"
          >
          Looking back to {{ formattedRecentWorkDate }} (clockwork)
        </div>
        <div
          v-if="isFetchingRecentWork"
          >
          <span>Loading recent work...</span>
        </div>
        <div
          v-else-if="recentWork.length === 0"
          >
          <span>No clockwork entries found.</span>
        </div>
        <div
          v-else
          >
          <div
            v-for="work in recentWork"
            :key="work.id"
            >
            <div class="mb-2">
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

          <copy-text-button
            v-if="!isFetchingRecentWork"
            class="absolute top-4 right-4"
            :text="createCopyableText(recentWork)"
            ></copy-text-button>
        </div>
      </div>
      <div class="content">
        <div class="bg-gray-950 text-white px-4 py-2 border-b">
          ✅ What did you do yesterday?
        </div>
        <RichTextEditor
          v-model="form.in_progress"
          placeholder="What are you working on?"
          ></RichTextEditor>
      </div>
      <div class="content">
        <div class="bg-gray-950 text-white px-4 py-2 border-b">
          💯 What will you do today?
        </div>
        <RichTextEditor
          v-model="form.priorities"
          placeholder="What are your priorities?"
          ></RichTextEditor>
      </div>
      <div class="content">
        <div class="bg-gray-950 text-white px-4 py-2 border-b">
          🚨 Blockers
        </div>
        <RichTextEditor
          v-model="form.blockers"
          placeholder="What are your blockers?"
          ></RichTextEditor>
      </div>
    </div>

    <ProTipAlert pro-tip-name="autoformatting-links">
      We auto-format any link for you. Just paste the link, press space or enter to active the link, and save to see the result.
    </ProTipAlert>
    <ProTipAlert pro-tip-name="markdown-support">
      Each text-field above supports markdown. You can use "- " for bullet points, "**" for bold, and more. You can also highlight text and apply
      additional formatting using the formatting toolbar.
    </ProTipAlert>

    <div class="pt-4 flex gap-4">
      <DangerButton
        v-if="isEditing"
        type="button"
        @click="doDelete"
        >
        Delete
      </DangerButton>
      <div class="flex-grow"></div>
      <SecondaryButton
        type="button"
        @click="cancel"
        >
        Cancel
      </SecondaryButton>
      <PrimaryButton
        type="button"
        @click="save"
        >
        Save
      </PrimaryButton>
    </div>

    <div
      v-if="integrationConnectedSuccess"
      class="text-green-100 bg-green-900 p-3 rounded mt-4"
      >
      Your integration connection was successful! Links will be auto-formatted upon save
    </div>
    <div
      v-if="!integrationsStore.integrationsLoading && ( suggestAtlassianIntegration || suggestGithubIntegration )"
      class="text-white p-2 rounded mt-4"
      >
      <div class="flex justify-center">
        <div class="flex gap-2  opacity-50">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="size-6"
            >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"
              ></path>
          </svg>
          <span class="text-sm">
            Integrations Available
          </span>
        </div>
      </div>
      <div
        class="justify-center flex pt-2"
        >
        <div class="flex gap-2">
          <ConnectToJira
            v-if="suggestAtlassianIntegration"
            @user-connected="onUserConnectedIntegration('atlassian')"
            ></ConnectToJira>
          <ConnectToGithub
            v-if="suggestGithubIntegration"
            @user-connected="onUserConnectedIntegration('github')"
            ></ConnectToGithub>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.content:deep(.ck-content) {
    @apply prose prose-invert prose-ul:m-0 prose-p:m-0 prose-h1:m-0 prose-h2:m-0 prose-h3:m-0 prose-h4:m-0
    prose-h5:m-0 prose-h6:m-0 prose-li:m-0 prose-h1:text-lg prose-h2:text-lg prose-h3:text-lg
    prose-li:break-words prose-ol:m-0 py-2 bg-five;

    border-radius:initial !important;
    border-bottom-left-radius: 12px !important;
    border-bottom-right-radius: 12px !important;

    height:calc(100% - 40px);
    max-width: 100%;
}

.content {
    @apply rounded-xl border border-gray-900 shadow overflow-hidden;
}
</style>
