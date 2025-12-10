// LinkCardAutoExtension.ts
import { Extension } from '@tiptap/core';
import { Plugin, PluginKey, TextSelection } from 'prosemirror-state';

//Need a URL regex that looks for <a href="...">, mailto:..., tel:..., http(s)://..., www....
const urlRegex =
  /((https?:\/\/|www\.|mailto:|tel:)[^\s<>"']+)/i;

export const LinkCardAutoExtension = Extension.create( {
  name: 'linkCardAuto',

  addProseMirrorPlugins() {
    const editor = this.editor;
    const type = editor.schema.nodes.linkCard;

    return [
      new Plugin( {
        key: new PluginKey( 'linkCardAuto' ),
        appendTransaction( transactions, oldState, newState ) {
          // If nothing changed, skip
          const docChanged = transactions.some( tr => tr.docChanged );
          if ( !docChanged ) return null;

          const { tr } = newState;
          let modified = false;

          newState.doc.descendants( ( node, pos ) => {
            if ( !node.isText ) return;

            const text = node.text || '';
            const match = urlRegex.exec( text );
            if ( !match ) return;

            // Simple heuristic: full node is a URL or starts at beginning
            const fullMatch = match[0];
            if ( text.trim() !== fullMatch.trim() ) return;
            // Avoid reconverting if it’s already a card
            const $pos = newState.doc.resolve( pos );
            if (
              $pos.parent &&
              $pos.parent.type.name === 'linkCard'
            ) {
              return;
            }

            // Replace the text node with a linkCard node
            const end = pos + node.nodeSize;
            tr.replaceWith( pos, end, type.create( { href: fullMatch } ) );

            modified = true;

            return false; // stop descending here
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
} );
