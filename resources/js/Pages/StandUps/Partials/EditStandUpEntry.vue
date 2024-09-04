<script setup lang="ts">
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import { computed, Ref, ref } from 'vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import DangerButton from '@/Components/DangerButton.vue';
  import { StandUpEntry } from '@/Pages/StandUps/useStandUpEntriesStore';
  import RichTextEditor from '@/Components/RichTextEditor.vue';

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
    userIntegrations: {
      type: Array,
      default: () => [],
    },
  } );

  const emits = defineEmits( [ 'save', 'cancel', 'delete' ] );

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

  const hasIntegration = ( provider: string ) => {
    return props.userIntegrations.some( integration => integration.provider === provider );
  };

  const suggestAtlassianIntegration = computed( () => {
    return !hasIntegration( 'atlassian' ) &&
      (
        form.value.in_progress.includes( 'atlassian' )
        || form.value.priorities.includes( 'atlassian' )
        || form.value.blockers.includes( 'atlassian' )
      );
  } );

  const suggestGithubIntegration = computed( () => {
    return !hasIntegration( 'github' ) &&
      (
        form.value.in_progress.includes( 'github' )
        || form.value.priorities.includes( 'github' )
        || form.value.blockers.includes( 'github' )
      );
  } );
</script>

<template>
  <div>
    <div class="grid grid-cols-1 gap-4">
      <div class="content">
        <div class="dark:bg-gray-950 dark:text-white px-4 py-2 bg-gray-100 border-b">
          ✅ What did you do yesterday?
        </div>
        <RichTextEditor
          v-model="form.in_progress"
          placeholder="What are you working on?"
          ></RichTextEditor>
      </div>
      <div class="content">
        <div class="dark:bg-gray-950 dark:text-white px-4 py-2 bg-gray-100 border-b">
          💯 What will you do today?
        </div>
        <RichTextEditor
          v-model="form.priorities"
          placeholder="What are your priorities?"
          ></RichTextEditor>
      </div>
      <div class="content">
        <div class="dark:bg-gray-950 dark:text-white px-4 py-2 bg-gray-100 border-b">
          🚨 Blockers
        </div>
        <RichTextEditor
          v-model="form.blockers"
          placeholder="What are your blockers?"
          ></RichTextEditor>
      </div>
    </div>

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
      v-if="suggestAtlassianIntegration || suggestGithubIntegration"
      class="dark:text-white p-2 rounded mt-4"
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
      <div class="justify-center flex pt-2">
        <div class="flex gap-2">
          <a
            v-if="suggestAtlassianIntegration"
            :href="route('socialite.redirect', 'atlassian')"
            target="_blank"
            class="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-900 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150"
            type="button"
            @click="save"
            >
            <img
              src="https://dac-static.atlassian.com/favicon.ico"
              style="width:15px;"
              class="mr-1"
              alt="logo"
              />
            Connect to JIRA
          </a>
          <a
            v-if="suggestGithubIntegration"
            :href="route('socialite.redirect', 'github')"
            target="_blank"
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-900 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150"
            type="button"
            @click="save"
            >
            <svg
              class="mr-2"
              xmlns="http://www.w3.org/2000/svg"
              width="17"
              height="17"
              viewBox="0 0 32 32"
              fill="none"
              >
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M16 0C7.16 0 0 7.16 0 16C0 23.08 4.58 29.06 10.94 31.18C11.74 31.32 12.04 30.84 12.04 30.42C12.04 30.04 12.02 28.78 12.02 27.44C8 28.18 6.96 26.46 6.64 25.56C6.46 25.1 5.68 23.68 5 23.3C4.44 23 3.64 22.26 4.98 22.24C6.24 22.22 7.14 23.4 7.44 23.88C8.88 26.3 11.18 25.62 12.1 25.2C12.24 24.16 12.66 23.46 13.12 23.06C9.56 22.66 5.84 21.28 5.84 15.16C5.84 13.42 6.46 11.98 7.48 10.86C7.32 10.46 6.76 8.82 7.64 6.62C7.64 6.62 8.98 6.2 12.04 8.26C13.32 7.9 14.68 7.72 16.04 7.72C17.4 7.72 18.76 7.9 20.04 8.26C23.1 6.18 24.44 6.62 24.44 6.62C25.32 8.82 24.76 10.46 24.6 10.86C25.62 11.98 26.24 13.4 26.24 15.16C26.24 21.3 22.5 22.66 18.94 23.06C19.52 23.56 20.02 24.52 20.02 26.02C20.02 28.16 20 29.88 20 30.42C20 30.84 20.3 31.34 21.1 31.18C27.42 29.06 32 23.06 32 16C32 7.16 24.84 0 16 0V0Z"
                fill="white"
                ></path>
            </svg>
            Connect to Github
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.content:deep(.ck-content) {
    @apply prose dark:prose-invert prose-ul:m-0 prose-p:m-0 prose-h1:m-0 prose-h2:m-0 prose-h3:m-0 prose-h4:m-0
    prose-h5:m-0 prose-h6:m-0 prose-li:m-0 prose-h1:text-lg prose-h2:text-lg prose-h3:text-lg
    prose-li:break-words prose-ol:m-0 py-2 dark:bg-gray-800 bg-white;

    border-radius:initial !important;
    border-bottom-left-radius: 12px !important;
    border-bottom-right-radius: 12px !important;

    height:calc(100% - 40px);
    max-width: 100%;
}

.content {
    @apply rounded-xl border border-gray-200 dark:border-gray-900 shadow overflow-hidden;
}
</style>
