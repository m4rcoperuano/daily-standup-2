<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import { computed, onMounted, ref } from 'vue';
  import { DateTime } from 'luxon';
  import StandUpGroupEntrySection from '@/Pages/StandUps/Partials/StandUpGroupEntrySection.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import EditStandUpEntry from '@/Pages/StandUps/Partials/EditStandUpEntry.vue';
  import TextInput from '@/Components/TextInput.vue';
  import InputLabel from '@/Components/InputLabel.vue';

  const props = defineProps( {
    standUpGroup: {
      type: Object,
      required: true,
    },
  } );

  const standUpEntries = ref( [] );

  const fetchEntries = async () => {
    const response = await axios.get( route( 'stand-up-entries.index', props.standUpGroup.id ) );
    standUpEntries.value = response.data.data;
  };

  const standUpEntriesGroupedByDate = computed( () => {
    return standUpEntries.value.reduce( ( acc, entry ) => {
      const date = DateTime.fromISO( entry.date ).toFormat( 'cccc, LLLL d' );

      if ( !acc[date] ) {
        acc[date] = [];
      }

      acc[date].push( entry );

      return acc;
    }, {} );
  } );

  const standUpEntryGroupByDates = computed( () => {
    return Object.keys( standUpEntriesGroupedByDate.value );
  } );

  onMounted( () => {
    fetchEntries();
  } );

  const isCreatingStandUpEntry = ref( false );
  const creatingStandUpEntryDate = ref( DateTime.now().toFormat( 'yyyy-MM-dd' ) );

  const saveNew = async ( payload ) => {
    const date = DateTime.fromISO( creatingStandUpEntryDate.value );

    const dateNow = DateTime.now().set( {
      day: date.day,
      month: date.month,
      year: date.year,
    } ).startOf( 'day' ).minus( { minutes: date.offset } );

    const response = await axios.post( route( 'stand-up-entries.store' ), {
      ...payload,
      date: dateNow,
      stand_up_group_id: props.standUpGroup.id,
    } );

    if ( response.status === 201 ) {
      fetchEntries();
      isCreatingStandUpEntry.value = false;
    }
  };

  const cancelNew = () => {
    isCreatingStandUpEntry.value = false;
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
            v-if="standUpEntryGroupByDates.length <= 0"
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
          v-for="date in standUpEntryGroupByDates"
          :key="date"
          class="mb-4"
          >
          <StandUpGroupEntrySection
            :title="date"
            :stand-up-entries="standUpEntriesGroupedByDate[date]"
            :stand-up-group-id="standUpEntriesGroupedByDate[date].stand_up_group_id"
            @refresh="fetchEntries"
            >
          </StandUpGroupEntrySection>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
</style>
