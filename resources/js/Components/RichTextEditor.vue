<script setup lang="ts">
  import { useEditor, EditorContent } from '@tiptap/vue-3';
  import StarterKit from '@tiptap/starter-kit';
  import { LinkCard } from '@/Components/TipTap/LinkCard';
  import { Plugin, PluginKey, TextSelection } from 'prosemirror-state';

  const model = defineModel<string>();

  const editor = useEditor( {
    extensions: [
      StarterKit.configure( {
        link: {
          openOnClick: false,
        },
      } )
        .extend( {
          addProseMirrorPlugins() {
            const editor = this.editor;
            const type = editor.schema.nodes.linkCard;

            return [
              new Plugin( {
                key : new PluginKey( 'inlineLinkCardPlugin' ),
                appendTransaction( transactions, oldState, newState ) {
                  const docChanged = transactions.some( tr => tr.docChanged );
                  if ( !docChanged ) return null;

                  const { tr } = newState;
                  let modified = false;

                  newState.doc.descendants( ( node, pos ) => {
                    if ( !node.isText ) return;

                    //check if node's content contains a mark of type link
                    const linkMark = newState.schema.marks.link;
                    const marks = node.marks.filter( mark => mark.type === linkMark );
                    if ( marks.length === 0 ) return;

                    let text = node.text;
                    const $pos = newState.doc.resolve( pos );
                    if (
                      $pos.parent &&
                      $pos.parent.type.name === 'linkCard'
                    ) {
                      return;
                    }

                    // Replace the text node with a linkCard node
                    const end = pos + node.nodeSize;
                    tr.replaceWith( pos, end, type.create( { href: text } ) );

                    modified = true;
                  } );

                  if ( !modified ) return null;

                  // Preserve selection reasonably
                  const selection = tr.selection;
                  const lastPos = selection.from;
                  tr.setSelection( TextSelection.near( tr.doc.resolve( lastPos ) ) );

                  return tr;
                },
              } ),
            ];
          },
        } ),
      LinkCard,
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
