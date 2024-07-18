<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import { computed, onMounted, ref } from 'vue';
  import { DateTime } from 'luxon';
  import StandUpGroup from '@/Pages/StandUpGroups/Partials/StandUpGroup.vue';

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
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div
          v-for="date in Object.keys(standUpEntriesGroupedByDate)"
          :key="date"
          >
          <stand-up-group
            :title="date"
            :stand-up-entries="standUpEntriesGroupedByDate[date]"
            :stand-up-group-id="standUpEntriesGroupedByDate[date].stand_up_group_id"
            @refresh="fetchEntries"
            >
          </stand-up-group>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
</style>
