<script setup lang="ts">
  import 'ckeditor5/ckeditor5.css';
  import 'ckeditor5-premium-features/ckeditor5-premium-features.css';
  import {
    BalloonEditor,
    Bold,
    Essentials,
    Italic,
    Undo,
    Paragraph,
    Autoformat,
    PasteFromMarkdownExperimental,
    AutoLink,
    Heading,
    Link,
    Image,
    Table,
    List,
    TodoList,
    ListProperties,
    Code,
    CodeBlock, LinkUI,
  } from 'ckeditor5';

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

  const ckEditorConfig = {
    plugins: [
      Essentials,
      Bold,
      Italic,
      Autoformat,
      Undo,
      Paragraph,
      PasteFromMarkdownExperimental,
      AutoLink,
      Heading,
      Link,
      LinkUI,
      Image,
      Table,
      List,
      TodoList,
      ListProperties,
      Code,
      CodeBlock,
    ],
    toolbar: [ 'undo', 'redo', '|', 'heading', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'todoList', '|', 'image', 'table' ],
    link: {
      addTargetToExternalLinks: true,
      defaultProtocol: 'https://',
    },
  };

</script>

<template>
  <div class="container">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="content">
        <div class="bg-teal-800 text-white px-4 py-2 rounded-tl-lg rounded-tr-lg">
          In Progress
        </div>
        <ckeditor
          v-model="form.in_progress"
          :editor="BalloonEditor"
          :config="ckEditorConfig"
          ></ckeditor>
      </div>
      <div class="content">
        <div class="bg-blue-800 text-white px-4 py-2 rounded-tl-lg rounded-tr-lg">
          Priorities
        </div>
        <ckeditor
          v-model="form.priorities"
          :editor="BalloonEditor"
          :config="ckEditorConfig"
          ></ckeditor>
      </div>
      <div class="content">
        <div class="bg-red-800 text-white px-4 py-2 rounded-tl-lg rounded-tr-lg">
          Blockers
        </div>
        <ckeditor
          v-model="form.blockers"
          :editor="BalloonEditor"
          :config="ckEditorConfig"
          ></ckeditor>
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
}

.content {
    @apply rounded-xl border border-gray-200;
}
</style>
