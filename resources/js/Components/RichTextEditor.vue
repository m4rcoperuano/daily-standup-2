<script setup lang="ts">
  import { useEditor, EditorContent } from '@tiptap/vue-3';
  import StarterKit from '@tiptap/starter-kit';

  const model = defineModel<string>();

  const editor = useEditor( {
    extensions: [
      StarterKit,
    ],
    content: model.value,
    onUpdate: ( { editor } ) => {
      model.value = editor.getHTML();
    },
    editorProps: {
      attributes: {
        class: 'prose-styles editor-prose',
      },
    },
  } );

</script>

<template>
  <div class="editor-wrapper">
    <editor-content :editor="editor"></editor-content>
  </div>
</template>

<style scoped>
.editor-wrapper:deep(.editor-prose) {
  max-width: 100%;
  @apply focus:outline-none m-3;
}
</style>
