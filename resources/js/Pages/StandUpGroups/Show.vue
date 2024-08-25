<script setup lang="ts">
  import AppLayout from '@/Layouts/AppLayout.vue';
  import { computed, onMounted, ref, watch } from 'vue';
  import { DateTime } from 'luxon';
  import StandUpGroupEntrySection from '@/Pages/StandUps/Partials/StandUpGroupEntrySection.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import EditStandUpEntry from '@/Pages/StandUps/Partials/EditStandUpEntry.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import { StandUpEntry, useStandUpEntriesStore } from '@/Pages/StandUps/useStandUpEntriesStore';
  import { usePage } from '@inertiajs/vue3';
  import DateAwareDatePicker from '@/Components/DateAwareDatePicker.vue';
  import { useLinkPreviewsStore } from '@/Stores/linkPreviewStore';
  import SprintDetails from '@/Pages/StandUpGroups/Partials/SprintDetails.vue';

  const props = defineProps( {
    standUpGroup: {
      type: Object,
      required: true,
    },
  } );

  const page = usePage();
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
            <div class="bg-teal-50 dark:bg-teal-950 dark:text-blue-200 mb-2 hover:bg-teal-100 dark:hover:bg-teal-900 text-blue-800 rounded inline break-all py-1 pr-1" style="line-height:34px;">
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

</script>

<template>
  <AppLayout :title="standUpGroup.name">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight ">
        Stand Up {{ standUpGroup.name }}
      </h2>
    </template>
    <div class="py-12 dark:text-white">
      <div v-if="hasSprintIntegration">
        <SprintDetails :sprint-id="standUpGroup.atlassian_sprint_id"></SprintDetails>
      </div>
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div
          v-if="!isCreatingStandUpEntry"
          class="mb-4"
          >
          <p
            v-if="standUpEntryGroupByDateKeys.length <= 0"
            class="mb-2"
            >
            Hey! Your team currently doesn't posted any stand up entries to this group. Do you want to start it off?
          </p>

          <div class="flex">
            <PrimaryButton
              type="button"
              @click="isCreatingStandUpEntry = !isCreatingStandUpEntry"
              >
              Create Standup Entry
            </PrimaryButton>
          </div>

          <div
            class="gap-3 flex pt-4"
            >
            <div class="flex items-center">
              <input
                id="show-mine"
                name="show_filter"
                type="radio"
                value="show-mine"
                :checked="showFilter === 'show-mine'"
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                @input="changeFilter('show-mine')"
                />
              <label
                for="show-mine"
                class="ms-2 cursor-pointer font-medium text-gray-900 dark:text-gray-300"
                >Show My Entries</label>
            </div>
            <div class="flex items-center">
              <input
                id="show-all"
                type="radio"
                value="show-all"
                name="show_filter"
                :checked="showFilter === 'show-all'"
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                @input="changeFilter('show-all')"
                />
              <label
                for="show-all"
                class="ms-2 cursor-pointer font-medium text-gray-900 dark:text-gray-300"
                >Show Everyone</label>
            </div>
          </div>
        </div>
        <div v-else-if="isCreatingStandUpEntry">
          <div class="mb-2">
            <InputLabel
              value="Date"
              class="uppercase"
              ></InputLabel>
            <DateAwareDatePicker
              v-model="creatingStandUpEntryDate"
              ></DateAwareDatePicker>
          </div>
          <EditStandUpEntry
            @save="saveNew"
            @cancel="cancelNew"
            ></EditStandUpEntry>
          <div class="mb-4 border-b pb-8 border-gray-700"></div>
        </div>


        <div
          v-for="date in standUpEntryGroupByDateKeys"
          :key="date"
          class="mb-4 stand-up-group-entry-section"
          >
          <StandUpGroupEntrySection
            :title="date"
            :current-user-id="page.props.auth.user.id"
            :stand-up-entries="standUpEntriesStore.groupedByDate[date]"
            >
          </StandUpGroupEntrySection>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.stand-up-group-entry-section:deep(.link-preview) {
    text-decoration: none;
}
</style>
