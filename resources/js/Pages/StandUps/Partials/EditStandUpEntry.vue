<script setup>

  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import { ref } from 'vue';
  import { DateTime } from 'luxon';

  const props = defineProps( {
    standUpGroupId: Number,
  } );

  const emits = defineEmits( [ 'saved' ] );

  const form = ref( {
    date: new Date(),
    in_progress: '',
    priorities: '',
    blockers: '',
    stand_up_group_id: props.standUpGroupId,
  } );

  const save = async () => {
    await axios.post( route( 'stand-up-entries.store' ), form.value );
    emits( 'saved' );
  };

</script>

<template>
  <div class="px-4 md:px-2 mb-4">
    <h1 class="text-lg font-bold">
      New Stand Up Entry for {{ DateTime.fromJSDate(form.date).toFormat('cccc, LLLL d') }}
    </h1>
  </div>
  <div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="bg-teal-100 dark:bg-teal-950 px-4 py-2 sm:rounded-lg">
        <div class="mb-2">
          In Progress
        </div>
        <textarea
          v-model="form.in_progress"
          placeholder="- Completed task A"
          rows="5"
          ></textarea>
      </div>
      <div class="bg-blue-100 dark:bg-blue-950 px-4 py-2 sm:rounded-lg">
        <div class="mb-2">
          Priorities
        </div>
        <textarea
          v-model="form.priorities"
          placeholder="- Will complete task B"
          rows="5"
          ></textarea>
      </div>
      <div class="bg-red-100 dark:bg-red-950 px-4 py-2 sm:rounded-lg">
        <div class="mb-2">
          Blockers
        </div>
        <textarea
          v-model="form.blockers"
          placeholder="- Waiting on task C"
          rows="5"
          ></textarea>
      </div>
    </div>
    <div class="pt-4 block w-full">
      <PrimaryButton
        type="button"
        @click="save"
        >
        Save
      </PrimaryButton>
    </div>
  </div>
</template>

<style scoped>
textarea {
    @apply block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm;
}
</style>
