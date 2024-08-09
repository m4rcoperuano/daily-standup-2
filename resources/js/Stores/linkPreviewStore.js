import { defineStore } from 'pinia';
import { useApi } from '@/useApi.ts';

export const useLinkPreviewsStore = defineStore( 'linkPreviews', {
    state: () => ( {
        linkIds: [],
        onEachPreviewFn: null,
    } ),
    actions: {
        setCallBack( callback ) {
            this.onEachPreviewFn = callback;
        },
        addLinks( links ) {
            this.linkIds = links
                .filter( link => !link.attributes.disable_rich_link )
                .filter( link => link.text === link.url )
                .map( link => link.id );

            this.startQueue();
        },
        async fetchLink( linkId ) {
            const api = useApi();
            const response = await api.linkPreviews.fetch( linkId );
            return response.result.data;
        },
        async startQueue() {
            while ( this.linkIds.length > 0 ) {
                const linkId = this.linkIds.shift();
                const preview = await this.fetchLink( linkId );
                this.onEachPreviewFn( preview );
            }
        },
    },
} );
