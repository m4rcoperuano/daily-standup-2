<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import { computed, onMounted, onUnmounted, ref } from 'vue';
  import { usePage } from '@inertiajs/vue3';

  const props = defineProps( {
    room: {
      type: Object,
      required: true,
    },
  } );
  const page = usePage();
  const loggedInUser = computed( () => page.props.auth.user );

  const roomUsers = ref( [] );
  const votes = ref( props.room.votes.slice() );
  const pointValues = [ 1, 2, 3, 5, 8, 13, 21 ];

  onMounted( () => {
    Echo.join( `pointing-room.${props.room.id}` )
      .here( ( users ) => {
        roomUsers.value = users;
      } )
      .joining( ( user ) => {
        roomUsers.value.push( user );
      } )
      .leaving( ( user ) => {
        roomUsers.value = roomUsers.value.filter( ( u ) => u.id !== user.id );
      } )
      .listen( 'Voted', ( event ) => {
        if ( event.vote.user_id !== loggedInUser.value.id ) {
          votes.value.push( event.vote );
        }
      } )
      .listen( 'RemovedVote', ( event ) => {
        votes.value = votes.value.filter( ( vote ) => vote.id !== event.voteId );
      } )
      .error( ( error ) => {
        console.error( error );
      } );
  } );

  onUnmounted( () => {
    Echo.leave( `pointing-room.${props.room.id}` );
  } );

  const submitVote = async ( point ) => {
    const response = await axios.post( route( 'pointing-room.vote' ), {
      vote: point,
    } );

    if ( response.status === 201 ) {
      votes.value.push( response.data );
    }
  };

  const deleteVote = async () => {
    await axios.delete( route( 'pointing-room.vote' ) );

    votes.value = votes.value.filter( ( vote ) => vote.user_id !== loggedInUser.value.id );
  };

</script>

<template>
  <AppLayout title="Pointing Room">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Pointing Room
      </h2>
    </template>

    <div class="py-6 dark:text-white">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <ul
          role="list"
          class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
          >
          <li
            v-for="user in roomUsers.sort((a, b) => a.name.localeCompare(b.name))"
            :key="user.id"
            class="col-span-1 flex flex-col overflow-hidden divide-y dark:divide-gray-700 divide-gray-200 rounded-lg bg-white dark:bg-gray-900 text-center shadow dark:shadow-gray-700"
            >
            <div class="flex flex-1 flex-col p-4">
              <img
                class="mx-auto size-32 shrink-0 rounded-full"
                :src="user.profile_photo_url"
                alt=""
                />
              <h3 class="mt-6 font-medium text-gray-900 dark:text-gray-200">
                {{ user.name }}
              </h3>
            </div>
            <template v-if="votes.filter(vote => vote.user_id === user.id).length">
              <div class="flex flex-col flex-1 p-4">
                <div class="text-sm italic">
                  Voted
                </div>
                <div class="text-2xl font-semibold">
                  {{ votes.find(vote => vote.user_id === user.id).value }}
                </div>
                <button
                  type="button"
                  class="mt-4 w-full py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700"
                  @click="deleteVote()"
                  >
                  Change Vote
                </button>
              </div>
            </template>

            <template v-else-if="user.id === loggedInUser.id">
              <div class="text-sm italic py-2">
                Set a point value!
              </div>
              <div class="flex flex-wrap">
                <div
                  v-for="point in pointValues"
                  :key="point"
                  class="flex-grow border border-gray-200 dark:border-gray-700"
                  style="min-width:50px;"
                  >
                  <button
                    type="button"
                    class="w-full py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700"
                    @click="submitVote(point)"
                    >
                    {{ point }}
                  </button>
                </div>
              </div>
            </template>
            <template v-else>
              <div
                class="text-sm italic py-2 flex justify-center items-center"
                style="min-height: 100px;"
                >
                Still pointing...
              </div>
            </template>
          </li>
        </ul>
      </div>
    </div>
  </AppLayout>
</template>
