import { AxiosResponse } from 'axios';

export type CustomResponse = {
    success: boolean,
    result: AxiosResponse<any, any>,
    message?: string
}

export function useApi( ) {
    const callApi = async ( method: string, url: string, data = null ) : Promise<CustomResponse> => {
        try {
            const response = await axios( {
                method,
                url,
                data,
            } );

            return {
                success: true,
                result: response,
            };
        }
        catch ( error ) {
            return {
                success: false,
                result: error.response,
                message: error?.response?.data?.message ?? 'Sorry! We ran into an issue. Please try again later.',
            };
        }
    };

    const standUpEntries = {
        fetchAll: async ( standUpGroupId: StringOrNumber ): Promise<CustomResponse> => {
            return await callApi( 'get', route( 'stand-up-entries.index', standUpGroupId ) );
        },
        create: async ( payload ): Promise<CustomResponse> => {
            return await callApi( 'post', route( 'stand-up-entries.store' ), payload );
        },
        update: async ( id: StringOrNumber, payload ): Promise<CustomResponse> => {
            return await callApi( 'patch', route( 'stand-up-entries.update', id ), payload );
        },
        delete: async ( id: StringOrNumber ): Promise<CustomResponse> => {
            return await callApi( 'delete', route( 'stand-up-entries.destroy', id ) );
        },
    };

    return {
        standUpEntries,
    };
}
