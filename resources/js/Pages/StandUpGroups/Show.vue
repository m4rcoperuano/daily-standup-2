<script setup lang="ts">
  import AppLayout from '@/Layouts/AppLayout.vue';
  import { computed, onMounted, ref } from 'vue';
  import { DateTime } from 'luxon';
  import StandUpGroupEntrySection from '@/Pages/StandUps/Partials/StandUpGroupEntrySection.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import EditStandUpEntry from '@/Pages/StandUps/Partials/EditStandUpEntry.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import { StandUpEntry, useStandUpEntriesStore } from '@/Pages/StandUps/useStandUpEntriesStore';
  import { usePage } from '@inertiajs/vue3';
  import DateAwareDatePicker from '@/Components/DateAwareDatePicker.vue';
  import { useLinkPreviewsStore } from '@/Stores/linkPreviewStore';

  const props = defineProps( {
    standUpGroup: {
      type: Object,
      required: true,
    },
  } );

  const page = usePage();
  const standUpEntriesStore = useStandUpEntriesStore();
  const linkPreviewsStore = useLinkPreviewsStore();

  linkPreviewsStore.setCallBack( ( preview ) => {
    const elements = document.querySelectorAll( `a[href="${preview.url}"]` );
    elements.forEach( function( element ) {
      const title = preview.title;
      const image = preview.image;

      if ( image ) {
        element.innerHTML = `
          <div class="inline not-prose">
            <div class="bg-teal-50 dark:bg-teal-950 dark:text-blue-200 mb-2 hover:bg-gray-200 text-blue-800 rounded inline break-all py-1 pr-1" style="line-height:34px;">
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

  onMounted(  () => standUpEntriesStore.fetch( props.standUpGroup.id ) );

  const standUpEntryGroupByDateKeys = computed( () => Object.keys( standUpEntriesStore.groupedByDate ) );
  const isCreatingStandUpEntry = ref( false );
  const creatingStandUpEntryDate = ref( DateTime.now().toFormat( 'yyyy-MM-dd' ) );

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
</script>

<template>
  <AppLayout title="Stand Up">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight ">
        Stand Up {{ standUpGroup.name }}
      </h2>
    </template>
    <div class="py-12 dark:text-white">
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
