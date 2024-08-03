<script setup lang="ts">
  import AppLayout from '@/Layouts/AppLayout.vue';
  import { computed, nextTick, onMounted, ref, watch } from 'vue';
  import { DateTime } from 'luxon';
  import StandUpGroupEntrySection from '@/Pages/StandUps/Partials/StandUpGroupEntrySection.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import EditStandUpEntry from '@/Pages/StandUps/Partials/EditStandUpEntry.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import { StandUpEntry, useStandUpEntries } from '@/Pages/StandUps/useStandUpEntries.js';
  import { usePage } from '@inertiajs/vue3';
  import DateAwareDatePicker from '@/Components/DateAwareDatePicker.vue';
  import { useLinkPreviewsStore } from '@/Stores/linkPreviewStore.js';

  const props = defineProps( {
    standUpGroup: {
      type: Object,
      required: true,
    },
  } );

  const page = usePage();
  const linkPreviewStore = useLinkPreviewsStore();

  const {
    links,
    fetchEntries,
    standUpEntriesGroupedByDate,
    createEntry,
  } = useStandUpEntries();

  onMounted( async () => {
    await fetchEntries( props.standUpGroup.id );

    linkPreviewStore.addLinks( links.value );
    linkPreviewStore.fetchAllLinks();
  } );

  watch( linkPreviewStore.previews, ( values ) => {
    const lastValue = values[values.length - 1];
    if ( lastValue ) {
      nextTick( () => {
        //find element where the <a href="..."> is equal to lastValue.url
        const element = document.querySelector( `a[href="${lastValue.link}"]` );
        if ( element ) {
          const title = lastValue.result.title;
          const image = lastValue.result.image;

          if ( image ) {
            element.innerHTML = `<div class="inline-block"><div class="bg-gray-100 text-blue-800 rounded px-2 inline-block"><img src="${image}" alt="${title}" class="inline-block mr-2" style="margin-top:0;margin-bottom:0;height:20px;" />${title}</div></div>`;
          }
          else {
            element.innerHTML = title;
          }

        }
      } );
    }
  } );

  const standUpEntryGroupByDateKeys = computed( () => Object.keys( standUpEntriesGroupedByDate.value ) );
  const isCreatingStandUpEntry = ref( false );
  const creatingStandUpEntryDate = ref( DateTime.now().toFormat( 'yyyy-MM-dd' ) );

  const cancelNew = () => {
    isCreatingStandUpEntry.value = false;
  };

  const saveNew = async ( payload: StandUpEntry ) => {
    const response = await createEntry( payload, creatingStandUpEntryDate.value, props.standUpGroup.id );

    if ( response.success ) {
      isCreatingStandUpEntry.value = false;
    }
    else {
      alert( response.message );
    }
  };
</script>

<template>
  <AppLayout title="Stand Up">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Stand Up {{ standUpGroup.name }}
      </h2>
    </template>
    <div class="py-12 dark:text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
          <PrimaryButton
            type="button"
            @click="isCreatingStandUpEntry = !isCreatingStandUpEntry"
            >
            Create Standup Entry
          </PrimaryButton>
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
            :stand-up-entries="standUpEntriesGroupedByDate[date]"
            @refresh="fetchEntries(standUpGroup.id)"
            >
          </StandUpGroupEntrySection>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
</style>
