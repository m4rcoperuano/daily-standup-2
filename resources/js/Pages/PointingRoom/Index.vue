<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import { computed, onMounted, onUnmounted, ref } from 'vue';
  import { usePage } from '@inertiajs/vue3';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import StellarLayout from '@/Layouts/StellarLayout.vue';

  const props = defineProps( {
    room: {
      type: Object,
      required: true,
    },
  } );
  const page = usePage();
  const loggedInUser = computed( () => page.props.auth.user );

  const roomUsers = ref( [] );
  const votesRevealed = ref( props.room.reveal );
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
      .listen( 'VotesRevealed', () => {
        votesRevealed.value = true;
      } )
      .listen( 'ResetVotes', () => {
        window.location.reload();
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

  const revealVotes = async () => {
    await axios.post( route( 'pointing-room.reveal' ) );

    votesRevealed.value = true;
  };

  const resetVotes = async () => {
    await axios.post( route( 'pointing-room.reset' ) );
  };

</script>

<template>
  <StellarLayout title="Pointing Room">
    <template #header>
      <div class="flex items-center justify-center gap-4 content-center mb-4">
        <h2 class="font-semibold text-xl text-primary flex-grow">
          Pointing Room
        </h2>
        <div>
          <PrimaryButton
            v-if="!votesRevealed"
            @click="revealVotes"
            >
            Reveal Votes
          </PrimaryButton>
          <SecondaryButton
            v-else
            @click="resetVotes"
            >
            Reset Votes
          </SecondaryButton>
        </div>
      </div>
    </template>

    <div class="text-white">
      <div class="max-w-7xl mx-auto px-6 pb-8">
        <div
          class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
          >
          <div
            v-for="user in roomUsers.sort((a, b) => a.name.localeCompare(b.name))"
            :key="user.id"
            class="col-span-1 flex flex-col overflow-hidden divide-y divide-quaternary  rounded-lg bg-five text-center shadow shadow-gray-700"
            >
            <div class="flex flex-1 flex-col p-4">
              <img
                class="mx-auto size-32 shrink-0 rounded-full"
                :src="user.profile_photo_url"
                alt=""
                />
              <h3 class="mt-6 font-medium text-gray-200">
                {{ user.name }}
              </h3>
            </div>
            <template v-if="votes.filter(vote => vote.user_id === user.id).length">
              <div class="flex flex-col flex-1 p-4">
                <div class="text-sm italic">
                  Voted
                </div>
                <div class="text-2xl font-semibold">
                  <span v-if="user.id === loggedInUser.id || votesRevealed">
                    {{ votes.find(vote => vote.user_id === user.id).value }}
                  </span>
                  <span
                    v-else
                    class="opacity-50"
                    >
                    Hidden!
                  </span>
                </div>
                <button
                  v-if="user.id === loggedInUser.id"
                  type="button"
                  class="mt-4 w-full py-2 text-sm font-medium text-quaternary bg-primary rounded hover:opacity-50 transition-opacity"
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
                  class="flex-grow border border-gray-700"
                  style="min-width:50px;"
                  >
                  <button
                    type="button"
                    class="w-full py-2 text-sm font-medium text-gray-200 hover:bg-primary hover:text-quaternary"
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
          </div>
        </div>
      </div>
    </div>
  </StellarLayout>
</template>
