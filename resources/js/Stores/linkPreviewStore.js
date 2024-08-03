import { defineStore } from 'pinia';
import { useApi } from '@/useApi.ts';

export const useLinkPreviewsStore = defineStore( 'linkPreviews', {
    state: () => ( {
        links: [],
        previews: [],
        previewMap: {},
    } ),
    actions: {
        addLinks( links ) {
            this.links = [ ...links ];
        },
        async fetchLink( link ) {
            const api = useApi();
            const response = await api.linkPreviews.fetch( link );
            this.previews.push( { link, result: response.result.data } );
            this.previewMap[ link ] = response.result.data;
        },
        fetchAllLinks() {
            this.links.forEach( link => this.fetchLink( link ) );
        },
    },
} );
