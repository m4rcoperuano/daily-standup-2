<script setup lang="ts">

  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import { Ref, ref } from 'vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import DangerButton from '@/Components/DangerButton.vue';
  import { StandUpEntry } from '@/Pages/StandUps/useStandUpEntries';

  const props = defineProps( {
    isEditing: {
      type: Boolean,
      default: false,
    },
    inProgress: {
      type: String,
      default: null,
    },
    priorities: {
      type: String,
      default: null,
    },
    blockers: {
      type: String,
      default: null,
    },
  } );

  const emits = defineEmits( [ 'save', 'cancel', 'delete' ] );

  const form : Ref<StandUpEntry> = ref( {
    in_progress: props.inProgress,
    priorities: props.priorities,
    blockers: props.blockers,
  } );

  const save = async () => {
    emits( 'save', form.value );
  };

  const cancel = async () => {
    emits( 'cancel' );
  };

  const doDelete = async () => {
    if ( confirm( 'are you sure you want to delete this entry?' ) ) {
      emits( 'delete' );
    }
  };

</script>

<template>
  <div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <div class="bg-teal-800 text-white px-4 py-2 rounded-tl-lg rounded-tr-lg">
          In Progress
        </div>
        <textarea
          v-model="form.in_progress"
          placeholder="- Completed task A"
          class="border-0 border-collapse"
          rows="5"
          ></textarea>
      </div>
      <div>
        <div class="bg-blue-800 text-white px-4 py-2 rounded-tl-lg rounded-tr-lg">
          Priorities
        </div>
        <textarea
          v-model="form.priorities"
          placeholder="- Will complete task B"
          class="border-0 border-collapse"
          rows="5"
          ></textarea>
      </div>
      <div>
        <div class="bg-red-800 text-white px-4 py-2 rounded-tl-lg rounded-tr-lg">
          Blockers
        </div>
        <textarea
          v-model="form.blockers"
          placeholder="- Waiting on task C"
          rows="5"
          class="border-0 border-collapse"
          ></textarea>
      </div>
    </div>

    <div class="pt-4 flex gap-4">
      <DangerButton
        v-if="isEditing"
        type="button"
        @click="doDelete"
        >
        Delete
      </DangerButton>
      <div class="flex-grow"></div>
      <SecondaryButton
        type="button"
        @click="cancel"
        >
        Cancel
      </SecondaryButton>
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
    @apply block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-bl rounded-br shadow-sm;
}
</style>
