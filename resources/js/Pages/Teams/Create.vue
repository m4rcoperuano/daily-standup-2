<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import CreateTeamForm from '@/Pages/Teams/Partials/CreateTeamForm.vue';
  import StellarLayout from '@/Layouts/StellarLayout.vue';
</script>

<template>
  <StellarLayout title="Create Team">
    <template #header>
      <h2 class="font-semibold text-xl text-primary mb-4">
        Create Team
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <CreateTeamForm></CreateTeamForm>
      </div>
    </div>
  </StellarLayout>
</template>
