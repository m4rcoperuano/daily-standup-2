<script setup lang="ts">
  import ActionSection from '@/Components/ActionSection.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
</script>

<template>
  <ActionSection>
    <template #title>
      Integrations
    </template>

    <template #description>
      Integrate with popular services like Github and JIRA!
    </template>

    <template #content>
      <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
        Click below to connect your JIRA Account
      </div>

      <div class="mt-3 mb-6">
        <PrimaryButton
          external
          :route="route('socialite.redirect', 'atlassian')"
          >
          Connect Jira
        </PrimaryButton>
      </div>

      <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
        Click below to connect your Github Account
      </div>

      <div class="mt-3">
        <PrimaryButton
          external
          :route="route('socialite.redirect', 'github')"
          >
          Connect Github
        </PrimaryButton>
      </div>
    </template>
  </ActionSection>
</template>

<style scoped>

</style>
