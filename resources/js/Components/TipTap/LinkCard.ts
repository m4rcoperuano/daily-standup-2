// LinkCard.ts
import { Node, mergeAttributes } from '@tiptap/core';

export interface LinkCardOptions {
  HTMLAttributes: Record<string, any>;
}

declare module '@tiptap/core' {
  interface Commands<ReturnType> {
    linkCard: {
      setLinkCard: ( options: { href: string; title?: string } ) => ReturnType;
    }
  }
}

export const LinkCard = Node.create<LinkCardOptions>( {
  name: 'linkCard',
  group: 'inline',
  inline: true,
  selectable: true,
  atom: true,   // behave as a single unit
  draggable: false,
  addOptions() {
    return {
      HTMLAttributes: { class: 'inline-card' },
    };
  },

  addAttributes() {
    return {
      href: {
        default: null,
      },
      title: {
        default: null,
      },
    };
  },

  parseHTML() {
    return [
      {
        tag: 'a',
        priority: 100,
      },
    ];
  },

  renderHTML( { HTMLAttributes } ) {
    const { href, title } = HTMLAttributes;

    return [
      'a',
      mergeAttributes( this.options.HTMLAttributes, {
        'data-link-card': 'true',
        href: href,
        target: '_blank',
        rel: 'noopener noreferrer',
      } ),
      [
        'div',
        { class: 'inline-card-link-container' },
        [
          'div',
          { class: 'link-card' },
          href,
        ],
      ],
    ];
  },

  addCommands() {
    return {
      setLinkCard:
        ( { href, title } ) =>
          ( { chain } ) => {
            return chain()
              .insertContent( {
                type: this.name,
                attrs: { href, title },
              } )
              .run();
          },
    };
  },
} );
