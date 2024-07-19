import { computed, Ref, ref } from 'vue';
import { DateTime } from 'luxon';
import { CustomResponse, useApi } from '@/useApi';

type StandUpEntryComposable = {
    standUpEntries: Ref<any[]>,
    standUpEntriesGroupedByDate: Ref<any>,
    fetchEntries: ( standUpGroupId: StringOrNumber ) => Promise<void>,
    createEntry: ( payload: StandUpEntry, dateSelected: string, standUpGroupId: StringOrNumber ) => Promise<CustomResponse>,
    updateEntry: ( id: StringOrNumber, payload: StandUpEntry ) => Promise<CustomResponse>,
    deleteEntry: ( id: StringOrNumber ) => Promise<CustomResponse>,
};

export type StandUpEntry = {
    in_progress: string,
    priorities: string,
    blockers: string
}

export function useStandUpEntries(): StandUpEntryComposable {
    const standUpEntries = ref( [] );
    const api = useApi();

    const standUpEntriesGroupedByDate = computed( () => {
        return standUpEntries.value.reduce( ( acc, entry ) => {
            const date = DateTime.fromISO( entry.date ).toFormat( 'cccc, LLLL d' );

            if ( !acc[date] ) {
                acc[date] = [];
            }

            acc[date].push( entry );

            return acc;
        }, {} );
    } );

    const fetchEntries = async ( standUpGroupId: StringOrNumber ) : Promise<void> => {
        const { result } = await api.standUpEntries.fetchAll( standUpGroupId );
        standUpEntries.value = result.data.data;
    };

    const createEntry = async (
        payload : StandUpEntry,
        dateSelected: string,
        standUpGroupId: StringOrNumber,
    ) : Promise<CustomResponse> => {
        const date = DateTime.fromISO( dateSelected );

        const dateNow = DateTime.now().set( {
            day: date.day,
            month: date.month,
            year: date.year,
        } ).startOf( 'day' ).minus( { minutes: date.offset } );

        const response = await api.standUpEntries.create( {
            ...payload,
            date: dateNow,
            stand_up_group_id: standUpGroupId,
        } );

        if ( response.success ) {
            fetchEntries( standUpGroupId );
            return response;
        }

        return response;
    };

    const updateEntry = async ( id: StringOrNumber, payload: StandUpEntry ): Promise<CustomResponse> => {
        return await api.standUpEntries.update( id, payload );
    };

    const deleteEntry = async ( id: StringOrNumber ): Promise<CustomResponse> => {
        return await api.standUpEntries.delete( id );
    };

    return {
        standUpEntries,
        standUpEntriesGroupedByDate,
        fetchEntries,
        createEntry,
        updateEntry,
        deleteEntry,
    };
}
