<script setup>
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import StandUpGroupCard from '@/Pages/StandUpGroups/Partials/StandUpGroupCard.vue';
  import ProTipAlert from '@/Components/ProTipAlert.vue';
  import StellarLayout from '@/Layouts/StellarLayout.vue';
  import { Link } from '@inertiajs/vue3';

  defineProps( {
    standUpGroups: {
      type: Array,
      required: true,
    },
    canCreateOrEdit: {
      type: Boolean,
      required: true,
    },
  } );

</script>

<template>
  <StellarLayout title="Stand Ups">
    <div class="text-white">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-6">
        <div>
          <div>
            <div v-if="standUpGroups.length <= 0">
              <p
                class="mb-2"
                >
                Hey! Your team currently doesn't have any stand up sprints created. Start collecting
                stand ups from your team by creating a group first.
              </p>
              <PrimaryButton
                v-if="canCreateOrEdit"
                link
                :route="route('stand-up-groups.create')"
                >
                Create Stand Up Sprint
              </PrimaryButton>
            </div>
            <div v-else>
              <div
                v-if="canCreateOrEdit"
                class="flex gap-2 mb-4 justify-center items-center"
                >
                <div class="flex-grow text-xl font-bold text-primary">
                  Stand Up Sprints
                </div>
                <Link
                  type="button"
                  class="block items-center p-1 btn-shine rounded-md font-semibold hover:opacity-50 transition-opacity text-xs text-white disabled:opacity-50 transition ease-in-out duration-150"
                  :href="route('stand-up-groups.create')"
                  >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="3"
                    stroke="currentColor"
                    class="size-12"
                    >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 4.5v15m7.5-7.5h-15"
                      ></path>
                  </svg>
                </Link>
              </div>

              <div class="justify-center">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                  <StandUpGroupCard
                    v-for="group in standUpGroups"
                    :key="group.id"
                    :title="group.name"
                    :group-id="group.id"
                    :can-edit="canCreateOrEdit"
                    >
                  </StandUpGroupCard>
                </div>
              </div>
            </div>

            <ProTipAlert
              v-if="canCreateOrEdit"
              pro-tip-name="stand-up-groups-info"
              >
              Your stand up sprints are containers for each of your Sprints. Any time you start a new sprint, create a new group!
              Your team members will automatically get access to it.

            </ProTipAlert>
          </div>
        </div>
      </div>
    </div>
  </StellarLayout>
</template>
