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
    currentUserId: {
      type: Number,
      required: true,
    },
  } );

  import { useStandUpEntriesStore } from '@/Pages/StandUps/useStandUpEntriesStore';

  const standUpEntriesStore = useStandUpEntriesStore();

  const emits = defineEmits( [ 'refresh' ] );
  const editingId = ref( null );

  const editRow = ( rowId ) => {
    editingId.value = rowId;
  };

  const onUpdate = async ( payload ) => {
    const response = await standUpEntriesStore.update( editingId.value, payload );
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
    const response = await standUpEntriesStore.delete( editingId.value );
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
  <div class="w-full dark:border-gray-950 gap-6 flex flex-col">
    <transition-group name="fade">
      <div
        v-for="entry in standUpEntries"
        :key="entry.id"
        >
        <div>
          <div
            v-if="editingId === entry.id"
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
            v-show="editingId !== entry.id"
            class="row shadow"
            >
            <div class="align-top border-b px-4 py-2 dark:border-gray-700 flex dark:bg-gray-950 bg-gray-50">
              <div class="items-center flex flex-grow gap-2">
                <img
                  class="h-8 w-8 rounded-full object-cover"
                  :src="entry.user.profile_photo_url"
                  :alt="entry.user.name"
                  />
                <span class="font-normal dark:text-gray-300">
                  {{ entry.user.name }}
                </span>
              </div>
              <button
                v-if="entry.user.id === currentUserId"
                class="edit dark:text-teal-500 dark:border-teal-500 text-teal-600 border-teal-600 border px-2 rounded hover:opacity-50"
                type="button"
                @click="editRow(entry.id)"
                >
                Edit
              </button>
            </div>
            <div
              class="align-top border-b p-4 dark:border-gray-700 col-span-2"
              >
              <div class="uppercase font-bold text-xs text-gray-500">
                In Progress
              </div>
              <span
                v-if="entry.in_progress"
                class=" prose-styles"
                v-html="entry.in_progress"
                ></span>
              <span
                v-else
                class="text-xs"
                >
                (N/A)
              </span>
            </div>
            <div class="align-top border-b p-4 dark:border-gray-700 col-span-2">
              <div class="uppercase font-bold text-xs text-gray-500">
                Priorities
              </div>
              <span
                v-if="entry.priorities"
                class=" prose-styles"
                v-html="entry.priorities"
                ></span>
              <span
                v-else
                class="text-xs"
                >
                (N/A)
              </span>
            </div>
            <div class="align-top p-4 dark:border-gray-700 col-span-2">
              <div class="uppercase font-bold text-xs text-gray-500">
                Blockers
              </div>
              <span
                v-if="entry.blockers"
                class=" prose-styles"
                v-html="entry.blockers"
                ></span>
              <span
                v-else
                class="text-xs"
                >
                (N/A)
              </span>
            </div>
          </div>
        </div>
      </div>
    </transition-group>
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

.prose-styles:deep( input[type="checkbox"] ) {
    @apply mr-2 bg-gray-200 border-gray-200;
}

.prose-styles:deep( input[type="checkbox"]:checked ) {
    @apply bg-teal-500 border-teal-500;
}

.prose-styles:deep( ul.todo-list ) {
    @apply list-none;
}

.prose-styles:deep( ul.todo-list:first-child ) {
    @apply pl-0;
}

.row {
    @apply bg-white dark:bg-gray-800 rounded-lg overflow-hidden;
}



.fade-enter-active,
.fade-leave-active {
    @apply transition-opacity;
}

.fade-enter-from,
.fade-leave-to {
    @apply opacity-0;
}

</style>
