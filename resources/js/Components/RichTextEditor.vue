<script setup lang="ts">
  import { useEditor, EditorContent } from '@tiptap/vue-3';
  import StarterKit from '@tiptap/starter-kit';
  import Link from '@tiptap/extension-link';
  import { LinkCard } from '@/Components/TipTap/LinkCard';
  import { LinkCardAutoExtension } from '@/Components/TipTap/LinkCardInputRule';

  const model = defineModel<string>();

  const editor = useEditor( {
    extensions: [
      StarterKit,
      LinkCard,
      Link.configure( {
        openOnClick: false, // optional; Jira-like behavior often customizes this
      } ),
      LinkCardAutoExtension,
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

.editor-wrapper:deep(.inline-link-card) {
  display: inline-flex;
  align-items: center;
  padding: 2px 6px;
  border-radius: 4px;
  border: 1px solid #d0d7de;
  background: #f6f8fa;
  font-size: 0.9em;
}

.editor-wrapper:deep(.inline-link-card a) {
  text-decoration: none;
  color: #0969da;
}

</style>
