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
    List,
    TodoList,
    ListProperties,
    Code,
    CodeBlock,
    LinkUI,
    PasteFromOffice,
  } from 'ckeditor5';

  const props = defineProps( {
    placeholder: {
      type: String,
      default: 'Enter text here',
    },
  } );

  const model = defineModel();

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
      List,
      TodoList,
      ListProperties,
      Code,
      CodeBlock,
      PasteFromOffice,
    ],
    toolbar: [ 'undo', 'redo', '|', 'heading', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'todoList' ],
    link: {
      addTargetToExternalLinks: true,
      defaultProtocol: 'https://',
      decorators: [
        {
          mode: 'manual',
          label: 'Disable Rich Link',
          attributes: {
            'disable-rich-link': true,
          },
        },
      ],
    },
    placeholder: props.placeholder,
  };

  const editor = BalloonEditor;
</script>

<template>
  <ckeditor
    v-model="model"
    :editor="editor"
    :config="ckEditorConfig"
    ></ckeditor>
</template>

<style scoped>

</style>
