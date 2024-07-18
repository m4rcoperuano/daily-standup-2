<script setup>

  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import { ref } from 'vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';

  const props = defineProps( {
    date: {
      type: Date,
      required: true,
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

  const emits = defineEmits( [ 'save', 'cancel' ] );

  const form = ref( {
    date: props.date,
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
      <PrimaryButton
        type="button"
        @click="save"
        >
        Save
      </PrimaryButton>
      <SecondaryButton
        type="button"
        @click="cancel"
        >
        Cancel
      </SecondaryButton>
    </div>
  </div>
</template>

<style scoped>
textarea {
    @apply block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-bl rounded-br shadow-sm;
}
</style>
