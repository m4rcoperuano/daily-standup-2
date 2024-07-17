<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import EditStandUpEntry from '@/Pages/StandUps/Partials/EditStandUpEntry.vue';
  import { computed, onMounted, ref } from 'vue';
  import { DateTime } from 'luxon';
  import StandUpGroup from '@/Pages/StandUpGroups/Partials/StandUpGroup.vue';

  const props = defineProps( {
    standUpGroup: Object,
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

  onMounted( () => {
    fetchEntries();
  } );

</script>

<template>
  <AppLayout title="Stand Up">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Stand Up {{ standUpGroup.name }}
      </h2>
    </template>
    <div class="py-12 dark:text-white">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-4">
          <EditStandUpEntry
            :stand-up-group-id="standUpGroup.id"
            @saved="fetchEntries"
            ></EditStandUpEntry>
        </div>

        <div
          v-for="date in Object.keys(standUpEntriesGroupedByDate)"
          :key="date"
          class="p-4 bg-white dark:bg-gray-800 rounded-lg"
          >
          <stand-up-group
            :title="date"
            :stand-up-entries="standUpEntriesGroupedByDate[date]"
            ></stand-up-group>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
</style>
