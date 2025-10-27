<script setup lang="ts">
  import { computed, onMounted, ref } from 'vue';
  import { DateTime } from 'luxon';
  import StandUpGroupEntrySection from '@/Pages/StandUps/Partials/StandUpGroupEntrySection.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import EditStandUpEntry from '@/Pages/StandUps/Partials/EditStandUpEntry.vue';
  import { StandUpEntry, useStandUpEntriesStore } from '@/Pages/StandUps/standUpEntriesStore';
  import { usePage } from '@inertiajs/vue3';
  import DateAwareDatePicker from '@/Components/DateAwareDatePicker.vue';
  import { useLinkPreviewsStore } from '@/Stores/linkPreviewStore';
  import StellarLayout from '@/Layouts/StellarLayout.vue';
  import CopyTextButton from '@/Components/CopyTextButton.vue';
  import RichTextEditor from '@/Components/RichTextEditor.vue';

  const props = defineProps( {
    standUpGroup: {
      type: Object,
      required: true,
    },
  } );

  const page = usePage();
  const user = computed( () => page.props.auth.user );
  const standUpEntriesStore = useStandUpEntriesStore();
  const linkPreviewsStore = useLinkPreviewsStore();
  const standUpEntryGroupByDateKeys = computed( () => Object.keys( standUpEntriesStore.groupedByDate ) );
  const isCreatingStandUpEntry = ref( false );
  const showFilter = ref( 'show-mine' );
  const creatingStandUpEntryDate = ref( DateTime.now().toFormat( 'yyyy-MM-dd' ) );

  linkPreviewsStore.setCallBack( ( preview ) => {
    const elements = document.querySelectorAll( `a[href="${preview.url}"]` );
    elements.forEach( function( element ) {
      const title = preview.title;
      const image = preview.image;

      if ( image ) {
        element.innerHTML = `
          <div class="inline not-prose">
            <div class="bg-teal-950 text-blue-200 mb-2 hover:bg-teal-900 rounded inline break-all py-1 pr-1" style="line-height:34px;">
              <img src="${image}" class="inline-block p-1 rounded mr-1 bg-white" style="height:25px;position:relative;top:-2px;" />
              ${title}
            </div>
          </div>`;
        element.classList.add( 'link-preview' );
      }
      else {
        element.innerHTML = title;
      }
    } );
  } );

  const hasSprintIntegration = !!props.standUpGroup.atlassian_sprint_id;

  onMounted(  () =>  {
    standUpEntriesStore.fetch( props.standUpGroup.id );
  } );

  const cancelNew = () => {
    isCreatingStandUpEntry.value = false;
  };

  const saveNew = async ( payload: StandUpEntry ) => {
    const response = await standUpEntriesStore.create(
      payload,
      creatingStandUpEntryDate.value,
      props.standUpGroup.id,
    );

    if ( response.success ) {
      isCreatingStandUpEntry.value = false;
    }
    else {
      alert( response.message );
    }
  };

  const changeFilter = ( value: string ) => {
    showFilter.value = value;
    standUpEntriesStore.fetch( props.standUpGroup.id, showFilter.value === 'show-all' );
  };

  Echo.private( `stand-up.${props.standUpGroup.id}` )
    .listen( 'StandUpUpdated', ( e ) => {
      if ( e.entry.user_id === user.value.id ) {
        return;
      }

      standUpEntriesStore.replace( e.entry.id, e.entry );
    } );

  const summaryData = ref( null );
  const isFetchingSummary = ref( false );
  const initiateSummary = async () => {
    isFetchingSummary.value = true;
    const response = await axios.get( route( 'stand-up-entries.export', { standUpGroup: props.standUpGroup.id } ) );
    summaryData.value = response.data;
    isFetchingSummary.value = false;
  };
</script>

<template>
  <StellarLayout :title="standUpGroup.name">
    <div class="pb-4 text-gray-200">
      <div class="max-w-4xl mx-auto px-6 sm:px-6 lg:px-8">
        <div
          v-if="!isCreatingStandUpEntry"
          class="mb-4"
          >
          <p
            v-if="standUpEntryGroupByDateKeys.length <= 0"
            class="mb-2"
            >
            No stand up entries yet! Click the button below to create one!
          </p>

          <div class="flex items-center">
            <div
              class="gap-3 flex flex-grow flex-col"
              >
              <h2 class="font-semibold text-xl bg-gradient-to-r text-primary">
                {{ standUpGroup.name }}
              </h2>
              <div class="flex gap-3 flex-col sm:flex-row">
                <div class="flex items-center">
                  <input
                    id="show-mine"
                    name="show_filter"
                    type="radio"
                    value="show-mine"
                    :checked="showFilter === 'show-mine'"
                    class="w-4 h-4 text-primary bg-gray-700 border-gray-900 focus:ring-primary ring-offset-gray-800 focus:ring-2"
                    @input="changeFilter('show-mine')"
                    />
                  <label
                    for="show-mine"
                    class="ms-2 cursor-pointer font-medium "
                    >Show My Entries</label>
                </div>
                <div class="flex items-center">
                  <input
                    id="show-all"
                    type="radio"
                    value="show-all"
                    name="show_filter"
                    :checked="showFilter === 'show-all'"
                    class="w-4 h-4 text-primary bg-gray-700 border-gray-900 focus:ring-primary ring-offset-gray-800 focus:ring-2"
                    @input="changeFilter('show-all')"
                    />
                  <label
                    for="show-all"
                    class="ms-2 cursor-pointer font-medium "
                    >Show Everyone</label>
                </div>
              </div>
            </div>

            <div
              v-if="!isCreatingStandUpEntry"
              >
              <button
                type="button"
                class="block items-center p-1 bg-gradient-to-r from-[#05A8F1] to-[#28F09E] rounded-md font-semibold hover:opacity-50 transition-opacity text-xs text-white disabled:opacity-50 transition ease-in-out duration-150"
                @click="isCreatingStandUpEntry = !isCreatingStandUpEntry"
                >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="3"
                  stroke="currentColor"
                  class="size-12"
                  >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 4.5v15m7.5-7.5h-15"
                    ></path>
                </svg>
              </button>
            </div>
          </div>
        </div>
        <div v-else>
          <h3 class="text-xl text-primary font-bold mb-4">New Stand Up Entry</h3>
          <div class="mb-2">
            <label class="block font-medium text-sm uppercase">
              <span>Date</span>
            </label>
            <DateAwareDatePicker
              v-model="creatingStandUpEntryDate"
              ></DateAwareDatePicker>
          </div>
          <EditStandUpEntry
            :date="creatingStandUpEntryDate"
            @save="saveNew"
            @cancel="cancelNew"
            ></EditStandUpEntry>
          <div class="mb-4 border-b pb-8 border-gray-200  dark:border-gray-700"></div>
        </div>

        <div class="text-right">
          <primary-button @click="initiateSummary">Create Summary (BETA)</primary-button>
        </div>

        <div
          v-if="isFetchingSummary"
          >
          Loading summary..
        </div>
        <RichTextEditor
          v-else-if="summaryData"
          :model-value="summaryData"
          ></RichTextEditor>
        <div
          v-for="date in standUpEntryGroupByDateKeys"
          :key="date"
          class="mb-4 stand-up-group-entry-section"
          >
          <StandUpGroupEntrySection
            :title="date"
            :current-user-id="user?.id"
            :stand-up-entries="standUpEntriesStore.groupedByDate[date]"
            >
          </StandUpGroupEntrySection>
        </div>
      </div>
    </div>
  </StellarLayout>
</template>

<style scoped>
.stand-up-group-entry-section:deep(.link-preview) {
    text-decoration: none;
}


.prose-styles {
  @apply prose-invert prose prose-ul:m-0 prose-p:m-0 prose-h1:m-0 prose-h2:m-0 prose-h3:m-0 prose-h4:m-0 prose-h5:m-0 prose-h6:m-0 prose-li:m-0 prose-h1:text-lg prose-h2:text-lg prose-h3:text-lg
  prose-li:break-words;
  @apply prose-ol:m-0 prose-p:break-words;
}

.prose-styles:deep( input[type="checkbox"] ) {
  @apply mr-2 bg-gray-200 border-gray-200;
}

.prose-styles:deep( input[type="checkbox"]:checked ) {
  @apply bg-teal-500 border-teal-500;
}

.prose-styles:deep( ul.todo-list ) {
  @apply list-none;
}

.prose-styles:deep( ul.todo-list:first-child ) {
  @apply pl-0;
}

</style>
