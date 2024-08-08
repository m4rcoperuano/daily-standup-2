import { defineStore } from 'pinia';
import { useApi } from '@/useApi.ts';

export const useLinkPreviewsStore = defineStore( 'linkPreviews', {
    state: () => ( {
        links: [],
        onEachPreviewFn: null,
    } ),
    actions: {
        setCallBack( callback ) {
            this.onEachPreviewFn = callback;
        },
        addLinks( links ) {
            this.links = links
                .filter( link => !link.attributes.disable_rich_link )
                .filter( link => link.text === link.url )
                .map( link => link.url );

            this.startQueue();
        },
        async fetchLink( link ) {
            const api = useApi();
            const response = await api.linkPreviews.fetch( link );
            return response.result.data;
        },
        async startQueue() {
            while ( this.links.length > 0 ) {
                const link = this.links.shift();
                const preview = await this.fetchLink( link );
                this.onEachPreviewFn( link, preview );
            }
        },
    },
} );
