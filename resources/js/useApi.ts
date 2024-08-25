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
        fetch: async ( standUpGroupId: StringOrNumber, all: Boolean ): Promise<CustomResponse> => {
            return await callApi( 'get', route( 'stand-up-entries.index', standUpGroupId ) + '?all=' + all );
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

    const linkPreviews = {
        fetch: async ( id: string ): Promise<CustomResponse> => {
            return await callApi( 'get', route( 'link-preview.show', id ) );
        },
    };

    const integrations = {
        jira: {
            boards: async (): Promise<CustomResponse> => {
                return await callApi( 'get', route( 'integrations.jira.boards' ) );
            },
            sprints: async ( boardId: StringOrNumber ): Promise<CustomResponse> => {
                return await callApi( 'get', route( 'integrations.jira.sprints', boardId ) );
            },
            sprint: async ( sprintId: StringOrNumber ): Promise<CustomResponse> => {
                return await callApi( 'get', route( 'integrations.jira.sprint', sprintId ) );
            },
        },
    };

    return {
        standUpEntries,
        linkPreviews,
        integrations,
    };
}
