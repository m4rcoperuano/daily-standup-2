<script setup lang="ts">
  import { onMounted, ref } from 'vue';
  import { useApi } from '@/useApi';

  const api = useApi();

  const props = defineProps( {
    sprintId: {
      type: [ Number, String ],
      required: true,
    },
  } );

  const sprint = ref( null );

  onMounted( async () => {
    sprint.value = ( await api.integrations.jira.sprint( props.sprintId ) ).result;
  } );

</script>

<template>
  {{ sprint }}
</template>

<style scoped>

</style>
