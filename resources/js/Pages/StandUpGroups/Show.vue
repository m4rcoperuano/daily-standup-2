<script setup lang="ts">
  import AppLayout from '@/Layouts/AppLayout.vue';
  import { computed, onMounted, ref } from 'vue';
  import { DateTime } from 'luxon';
  import StandUpGroupEntrySection from '@/Pages/StandUps/Partials/StandUpGroupEntrySection.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import EditStandUpEntry from '@/Pages/StandUps/Partials/EditStandUpEntry.vue';
  import TextInput from '@/Components/TextInput.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import { StandUpEntry, useStandUpEntries } from '@/Pages/StandUps/useStandUpEntries.js';
  import { usePage } from '@inertiajs/vue3';

  const props = defineProps( {
    standUpGroup: {
      type: Object,
      required: true,
    },
  } );

  const page = usePage();

  const {
    fetchEntries,
    standUpEntriesGroupedByDate,
    createEntry,
  } = useStandUpEntries();

  onMounted( () => fetchEntries( props.standUpGroup.id ) );

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
            <TextInput
              v-model="creatingStandUpEntryDate"
              type="date"
              ></TextInput>
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
          class="mb-4"
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
