<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import SectionBorder from '@/Components/SectionBorder.vue';
  import SocialiteIntegrations from '@/Pages/Profile/Partials/SocialiteIntegrations.vue';

  defineProps( {
    team: {
      type: Object,
      required: true,
    },
  } );
</script>

<template>
  <AppLayout title="Profile">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Profile
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div>
          <SocialiteIntegrations
            :user="$page.props.auth.user"
            :team="team"
            ></SocialiteIntegrations>
          <SectionBorder></SectionBorder>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
