<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import StandUpGroupCard from '@/Pages/StandUpGroups/Partials/StandUpGroupCard.vue';

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
  <AppLayout title="Stand Ups">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Stand Ups
      </h2>
    </template>

    <div class="py-6 dark:text-white">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div>
          <div class="p-4">
            <div v-if="standUpGroups.length <= 0">
              <p
                class="mb-2"
                >
                Hey! Your team currently doesn't have any stand up groups created. Start collecting
                stand ups from your team by creating a group first.
              </p>
              <PrimaryButton
                v-if="canCreateOrEdit"
                link
                :route="route('stand-up-groups.create')"
                >
                Create Stand Up Group
              </PrimaryButton>
            </div>
            <div v-else>
              <div
                v-if="canCreateOrEdit"
                class="flex gap-2 justify-center mb-4"
                >
                <PrimaryButton
                  link
                  :route="route('stand-up-groups.create')"
                  >
                  Create Stand Up Group
                </PrimaryButton>
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
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
