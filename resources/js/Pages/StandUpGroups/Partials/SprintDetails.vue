<script setup lang="ts">
  import { computed, onMounted, ref } from 'vue';
  import { useApi } from '@/useApi';
  import SprintDaysRemaining from '@/Pages/StandUpGroups/Partials/SprintDaysRemaining.vue';
  import { DateTime } from 'luxon';

  const api = useApi();

  const props = defineProps( {
    sprintId: {
      type: [ Number, String ],
      required: true,
    },
  } );

  const sprint = ref( null );

  onMounted( async () => {
    sprint.value = ( await api.integrations.jira.sprint( props.sprintId ) ).result.data;
  } );

  const sprintStartDate = computed( () => DateTime.fromISO( sprint.value?.startDate ).startOf( 'day' ).toJSDate() );
  const sprintEndDate = computed( () => DateTime.fromISO( sprint.value?.endDate ).endOf( 'day' ).toJSDate() );

</script>

<template>
  <div>
    <SprintDaysRemaining
      v-if="sprint"
      :start-date="sprintStartDate"
      :end-date="sprintEndDate"
      ></SprintDaysRemaining>
  </div>
</template>

<style scoped>

</style>
