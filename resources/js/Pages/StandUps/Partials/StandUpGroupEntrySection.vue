<script setup>
  import VueMarkdown from 'vue-markdown-render';
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
  } );

  import { useStandUpEntries } from '@/Pages/StandUps/useStandUpEntries.ts';

  const { deleteEntry, updateEntry } = useStandUpEntries();
  const emits = defineEmits( [ 'refresh' ] );
  const editingId = ref( null );

  const editRow = ( rowId ) => {
    editingId.value = rowId;
  };

  const onUpdate = async ( payload ) => {
    await updateEntry( editingId.value, payload );
    editingId.value = null;
    emits( 'refresh' );
  };

  const onCancel = () => {
    editingId.value = null;
  };

  const onDelete = async () => {
    await deleteEntry( editingId.value );
    editingId.value = null;
    emits( 'refresh' );
  };

</script>

<template>
  <div class="font-bold py-2 capitalize text-xl">
    {{ title }}
  </div>
  <div class="wrapper w-full border dark:border-gray-950">
    <div class="grid grid-cols-4">
      <div class="header">
        Team Member
      </div>
      <div class="header">
        In Progress
      </div>
      <div class="header">
        Priorities
      </div>
      <div class="header">
        Blockers
      </div>
    </div>
    <div
      v-for="entry in standUpEntries"
      :key="entry.id"
      class="row"
      >
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
        class="readonly"
        >
        <div class="content">
          <div>
            {{ entry.user.name }}
          </div>
          <button
            class="dark:text-teal-500 dark:border-teal-500 text-teal-600 border-teal-600 border px-2 rounded hover:opacity-50"
            type="button"
            @click="editRow(entry.id)"
            >
            Edit
          </button>
        </div>
        <div class="content">
          <VueMarkdown
            v-if="entry.in_progress"
            :source="entry.in_progress"
            ></VueMarkdown>
        </div>
        <div class="content">
          <VueMarkdown
            v-if="entry.priorities"
            :source="entry.priorities"
            ></VueMarkdown>
        </div>
        <div class="content">
          <VueMarkdown
            v-if="entry.blockers"
            :source="entry.blockers"
            ></VueMarkdown>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

.wrapper {
    @apply bg-white dark:bg-gray-900;
}

.wrapper .header {
    @apply z-20 sticky top-0 font-semibold uppercase text-sm text-gray-700 bg-gray-100 border-x dark:bg-gray-950 dark:text-gray-400 dark:border-gray-700;
    @apply py-2 px-4 border-b border-gray-300 dark:border-gray-700;
}

.wrapper .content {
    @apply align-top border px-4 py-2 dark:border-gray-700 dark:prose-invert prose prose-ul:m-0 prose-p:m-0 prose-h1:m-0 prose-h2:m-0 prose-h3:m-0 prose-h4:m-0 prose-h5:m-0 prose-h6:m-0 prose-li:m-0 prose-h1:text-lg prose-h2:text-lg prose-h3:text-lg;
    @apply prose-ol:m-0;
}

.wrapper .row .readonly {
    @apply grid grid-cols-4;
}

.wrapper .row:nth-child(even) {
    @apply bg-gray-50 dark:bg-gray-800;
}
</style>
