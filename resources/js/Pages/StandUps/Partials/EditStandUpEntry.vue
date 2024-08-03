<script setup lang="ts">
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import { Ref, ref } from 'vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import DangerButton from '@/Components/DangerButton.vue';
  import { StandUpEntry } from '@/Pages/StandUps/useStandUpEntries';
  import RichTextEditor from '@/Components/RichTextEditor.vue';

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
    in_progress: props.inProgress ?? '',
    priorities: props.priorities ?? '',
    blockers: props.blockers ?? '',
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
      <div class="content">
        <div class="bg-teal-800 text-white px-4 py-2 rounded-tl-lg rounded-tr-lg">
          In Progress
        </div>
        <RichTextEditor
          v-model="form.in_progress"
          placeholder="What are you working on?"
          ></RichTextEditor>
      </div>
      <div class="content">
        <div class="bg-blue-800 text-white px-4 py-2 rounded-tl-lg rounded-tr-lg">
          Priorities
        </div>
        <RichTextEditor
          v-model="form.priorities"
          placeholder="What are your priorities?"
          ></RichTextEditor>
      </div>
      <div class="content">
        <div class="bg-red-800 text-white px-4 py-2 rounded-tl-lg rounded-tr-lg">
          Blockers
        </div>
        <RichTextEditor
          v-model="form.blockers"
          placeholder="What are your blockers?"
          ></RichTextEditor>
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
.content:deep(.ck-content) {
    @apply prose dark:prose-invert prose-ul:m-0 prose-p:m-0 prose-h1:m-0 prose-h2:m-0 prose-h3:m-0 prose-h4:m-0
    prose-h5:m-0 prose-h6:m-0 prose-li:m-0 prose-h1:text-lg prose-h2:text-lg prose-h3:text-lg
    prose-li:break-words prose-ol:m-0 py-2 dark:bg-gray-800 bg-white;

    border-radius:initial !important;
    border-bottom-left-radius: 12px !important;
    border-bottom-right-radius: 12px !important;

    height:calc(100% - 40px);
    max-width: 100%;
}

.content {
    @apply rounded-xl border border-gray-200 dark:border-gray-900;
}
</style>
