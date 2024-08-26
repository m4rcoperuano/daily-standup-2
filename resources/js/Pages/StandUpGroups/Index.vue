<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';

  defineProps( {
    standUpGroups: {
      type: Array,
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

    <div class="py-12 dark:text-white">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-4">
            <div v-if="standUpGroups.length <= 0">
              <p
                class="mb-2"
                >
                Hey! Your team currently doesn't have any stand up groups created. Start collecting
                stand ups from your team by creating a group first.
              </p>
              <PrimaryButton
                link
                :route="route('stand-up-groups.create')"
                >
                Create Stand Up Group
              </PrimaryButton>
            </div>
            <div v-else>
              <div class="flex gap-2">
                <div class="opacity-60 flex-grow">
                  Stand Up Group
                </div>

                <PrimaryButton
                  link
                  :route="route('stand-up-groups.create')"
                  >
                  Create Stand Up Group
                </PrimaryButton>
              </div>

              <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                <li
                  v-for="group in standUpGroups"
                  :key="group.id"
                  class="py-4 flex justify-between items-center"
                  >
                  <div>
                    <h3 class="font-semibold">{{ group.name }}</h3>
                  </div>
                  <SecondaryButton
                    link
                    :route="route('stand-up-groups.show', group.id)"
                    >
                    View
                  </SecondaryButton>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
