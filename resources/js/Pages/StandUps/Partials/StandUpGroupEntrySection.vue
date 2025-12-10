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

  import { useStandUpEntriesStore } from '@/Pages/StandUps/standUpEntriesStore';
  import CopyTextButton from '@/Components/CopyTextButton.vue';

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
  <div class="w-full  gap-6 flex flex-col">
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
              :date="entry.date"
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
            <div class="align-top px-4 py-2 flex bg-gray-950">
              <div class="items-center flex flex-grow gap-2">
                <img
                  class="h-8 w-8 rounded-full object-cover"
                  :src="entry.user.profile_photo_url"
                  :alt="entry.user.name"
                  />
                <span class="font-normal text-gray-300">
                  {{ entry.user.name }}
                </span>
              </div>
              <button
                v-if="entry.user.id === currentUserId"
                class="edit text-primary border-primary border px-2 rounded-lg hover:opacity-50"
                type="button"
                @click="editRow(entry.id)"
                >
                Edit
              </button>
            </div>
            <div
              class="align-top p-4  col-span-2 stand-up-content"
              >
              <div class="uppercase font-bold text-xs text-gray-500">
                <span>What did you do yesterday?</span>
                <copy-text-button
                  v-if="entry.in_progress"
                  class="copy-text-button"
                  :text="entry.in_progress"
                  ></copy-text-button>
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
            <div class="align-top  p-4 col-span-2 stand-up-content">
              <div class="uppercase font-bold text-xs text-gray-500">
                <span>What will you do today?</span>
                <copy-text-button
                  v-if="entry.priorities"
                  class="copy-text-button"
                  :text="entry.priorities"
                  ></copy-text-button>
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
            <div
              v-if="entry.blockers"
              class="align-top p-4  col-span-2 stand-up-content"
              >
              <div class="uppercase font-bold text-xs text-gray-500">
                <span>Blockers</span>
                <copy-text-button
                  v-if="entry.blockers"
                  class="copy-text-button"
                  :text="entry.blockers"
                  ></copy-text-button>
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
    @apply z-20 sticky top-0 font-semibold uppercase text-sm bg-gray-100 border-x dark:bg-gray-950 text-gray-400 border-gray-700 py-2 px-4 border-b;
}

.row {
    @apply bg-five rounded-lg overflow-hidden divide-y divide-quaternary;
}

.copy-text-button {
    @apply float-right text-gray-400 hover:dark:text-teal-200 hover:text-teal-600 hidden;
}

.stand-up-content:hover .copy-text-button {
    @apply block;
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
