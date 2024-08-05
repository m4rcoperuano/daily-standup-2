import { DateTime } from 'luxon';
import { CustomResponse, useApi } from '@/useApi';
import { defineStore } from 'pinia';

export type StandUpEntry = {
    in_progress: string,
    priorities: string,
    blockers: string
}

export const useStandUpEntriesStore = defineStore( 'standUpEntries', {
    state: () => ( {
        standUpEntries: [],
    } ),
    getters: {
        groupedByDate() {
            return this.standUpEntries.reduce( ( acc, entry ) => {
                const date = DateTime.fromISO( entry.date ).toFormat( 'cccc, LLLL d' );

                if ( !acc[date] ) {
                    acc[date] = [];
                }

                acc[date].push( entry );

                return acc;
            }, {} );
        },
    },
    actions: {
        async fetch( standUpGroupId: StringOrNumber ) {
            const api = useApi();
            const { result } = await api.standUpEntries.fetchAll( standUpGroupId );
            this.standUpEntries = result.data.data;
        },
        async create( payload: StandUpEntry, dateSelected: string, standUpGroupId: StringOrNumber ) {
            const api = useApi();
            const date = DateTime.fromISO( dateSelected );

            const dateNow = DateTime.now().set( {
                day: date.day,
                month: date.month,
                year: date.year,
            } ).startOf( 'day' ).minus( { minutes: date.offset } );

            const response : CustomResponse = await api.standUpEntries.create( {
                ...payload,
                date: dateNow,
                stand_up_group_id: standUpGroupId,
            } );

            if ( response.success ) {
                this.standUpEntries = [ response.result.data.data, ...this.standUpEntries ];

                //sort
                this.standUpEntries.sort( ( a, b ) => {
                    return DateTime.fromISO( b.date ).toMillis() - DateTime.fromISO( a.date ).toMillis();
                } );
            }

            return response;
        },
        async update( id: StringOrNumber, payload: StandUpEntry ) {
            const api = useApi();
            const response = await api.standUpEntries.update( id, payload );

            if ( response.success ) {
                const index = this.standUpEntries.findIndex( entry => entry.id === id );
                console.log( response.result.data );
                this.standUpEntries[index] = response.result.data.data;
            }

            return response;
        },
        async delete( id: StringOrNumber ) {
            const api = useApi();
            const response = await api.standUpEntries.delete( id );

            if ( response.success ) {
                this.standUpEntries = this.standUpEntries.filter( entry => entry.id !== id );
            }

            return response;
        },
    },
} );
