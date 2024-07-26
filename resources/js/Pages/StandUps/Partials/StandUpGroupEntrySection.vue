<script setup lang="ts">
  import { ref } from 'vue';
  import EditStandUpEntry from '@/Pages/StandUps/Partials/EditStandUpEntry.vue';

  defineProps( {
    title: {
      type: String,
      required: true,
    },
    standUpEntries: {
      type: Array,
      required: true,
    },
    standUpGroupId: {
      type: Number,
      required: true,
    },
    currentUserId: {
      type: Number,
      required: true,
    },
  } );

  import { useStandUpEntries } from '@/Pages/StandUps/useStandUpEntries';

  const { deleteEntry, updateEntry } = useStandUpEntries();
  const emits = defineEmits( [ 'refresh' ] );
  const editingId = ref( null );

  const editRow = ( rowId ) => {
    editingId.value = rowId;
  };

  const onUpdate = async ( payload ) => {
    const response = await updateEntry( editingId.value, payload );
    if ( response.success ) {
      editingId.value = null;
      emits( 'refresh' );
    }
    else {
      alert( response.message );
    }
  };

  const onCancel = () => {
    editingId.value = null;
  };

  const onDelete = async () => {
    const response = await deleteEntry( editingId.value );
    if ( response.success ) {
      editingId.value = null;
      emits( 'refresh' );
    }
    else {
      alert( response.message );
    }
  };

</script>

<template>
  <div class="font-bold py-2 capitalize text-xl">
    {{ title }}
  </div>
  <div class="w-full md:border dark:border-gray-950">
    <div class="hidden md:grid grid-cols-7">
      <div class="header">
        Team Member
      </div>
      <div class="header col-span-2">
        In Progress
      </div>
      <div class="header col-span-2">
        Priorities
      </div>
      <div class="header col-span-2">
        Blockers
      </div>
    </div>
    <div
      v-for="entry in standUpEntries"
      :key="entry.id"
      class="row mb-6 md:mb-0"
      >
      <div class="md:mb-0">
        <div
          v-if="editingId === entry.id"
          class="p-4 dark:bg-gray-900 bg-gray-50"
          >
          <EditStandUpEntry
            :in-progress="entry.in_progress"
            :priorities="entry.priorities"
            :blockers="entry.blockers"
            :is-editing="true"
            @save="onUpdate"
            @cancel="onCancel"
            @delete="onDelete"
            >
          </EditStandUpEntry>
        </div>
        <div
          v-else
          class="md:grid md:grid-cols-7"
          >
          <div class="align-top border px-4 py-2 dark:border-gray-700 flex md:block dark:bg-gray-700 bg-gray-100 md:bg-inherit dark:md:bg-inherit">
            <div class="inline-block md:block mr-2 md:mr-0 flex-grow font-bold md:font-normal">
              {{ entry.user.name }}
            </div>
            <button
              v-if="entry.user.id === currentUserId"
              class="dark:text-teal-500 dark:border-teal-500 text-teal-600 border-teal-600 border px-2 rounded hover:opacity-50"
              type="button"
              @click="editRow(entry.id)"
              >
              Edit
            </button>
          </div>
          <div
            class="align-top border px-4 py-2 dark:border-gray-700 prose-styles col-span-2"
            >
            <div class="md:hidden uppercase font-bold text-xs text-gray-500">
              In Progress
            </div>
            <span
              v-if="entry.in_progress"
              v-html="entry.in_progress"
              ></span>
            <span
              v-else
              class="text-xs md:hidden"
              >
              (N/A)
            </span>
          </div>
          <div class="align-top border px-4 py-2 dark:border-gray-700 prose-styles col-span-2">
            <div class="md:hidden uppercase font-bold text-xs text-gray-500">
              Priorities
            </div>
            <span
              v-if="entry.priorities"
              v-html="entry.priorities"
              ></span>
            <span
              v-else
              class="text-xs md:hidden"
              >
              (N/A)
            </span>
          </div>
          <div class="align-top border px-4 py-2 dark:border-gray-700 prose-styles col-span-2">
            <div class="md:hidden uppercase font-bold text-xs text-gray-500">
              Blockers
            </div>
            <span
              v-if="entry.blockers"
              v-html="entry.blockers"
              ></span>
            <span
              v-else
              class="text-xs md:hidden"
              >
              (N/A)
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.header {
    @apply z-20 sticky top-0 font-semibold uppercase text-sm text-gray-700 bg-gray-100 border-x dark:bg-gray-950 dark:text-gray-400 dark:border-gray-700 py-2 px-4 border-b border-gray-300 dark:border-gray-700;
}

.prose-styles {
    @apply dark:prose-invert prose prose-ul:m-0 prose-p:m-0 prose-h1:m-0 prose-h2:m-0 prose-h3:m-0 prose-h4:m-0 prose-h5:m-0 prose-h6:m-0 prose-li:m-0 prose-h1:text-lg prose-h2:text-lg prose-h3:text-lg
        prose-li:break-words;
    @apply prose-ol:m-0 prose-p:break-words;
}

.row {
    @apply bg-white dark:bg-gray-800;
}

.row:nth-child(odd) {
    @apply md:bg-gray-100 md:dark:bg-gray-900;
}
</style>
