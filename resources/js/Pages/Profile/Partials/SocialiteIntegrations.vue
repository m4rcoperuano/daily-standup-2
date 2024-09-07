<script setup lang="ts">
  import ActionSection from '@/Components/ActionSection.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import { computed, onMounted, ref } from 'vue';
  import DangerButton from '@/Components/DangerButton.vue';
  import ConnectToGithub from '@/Components/Integrations/ConnectToGithub.vue';
  import ConnectToJira from '@/Components/Integrations/ConnectToJira.vue';

  const loading = ref( true );
  const integrations = ref( [] );

  const fetchIntegrations = async () => {
    loading.value = true;
    const response = await axios.get( route( 'socialite.index' ) );
    integrations.value = response.data;
    loading.value = false;
  };

  onMounted( () => {
    fetchIntegrations();
  } );

  const atlassianIntegration = computed(
    () => integrations.value.find( i => i.provider === 'atlassian' ),
  );

  const githubIntegration = computed(
    () => integrations.value.find( i => i.provider === 'github' ),
  );

  const deleteIntegration = async ( id: number ) => {
    await axios.delete( route( 'socialite.destroy', id ) );
    integrations.value = integrations.value.filter( i => i.id !== id );
  };
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
      <div class="flex flex-col">
        <div
          v-if="atlassianIntegration"
          class="flex items-center gap-3 mt-2"
          >
          <div class="flex-grow">
            <div class="text-gray-600 dark:text-gray-400">
              JIRA is <span class="text-green-500 font-bold">connected</span>
            </div>
            <div class="text-sm dark:text-white">
              This JIRA connection will allow us to show linked tickets and confluence articles in the app.
            </div>
          </div>
          <div>
            <DangerButton
              type="button"
              @click="deleteIntegration(atlassianIntegration.id)"
              >
              Delete
            </DangerButton>
          </div>
        </div>
        <div
          v-else
          >
          <div>
            <ConnectToJira @user-connected="fetchIntegrations"></ConnectToJira>
            <div class="dark:text-white pt-4">
              Connecting to JIRA will automatically format pasted links to JIRA tickets and Confluence articles.
            </div>
          </div>
        </div>

        <div class="py-6">
          <div class="border-t border-gray-200 dark:border-gray-700"></div>
        </div>

        <div
          v-if="githubIntegration"
          class="flex items-center gap-3 mb-3"
          >
          <div class="flex-grow">
            <div class="text-gray-600 dark:text-gray-400">
              Github is <span class="text-green-500 font-bold">connected</span>
            </div>
            <div class="text-sm dark:text-white">
              This Github connection will allow us to show linked pull requests and issues in the app.
            </div>
          </div>
          <div>
            <DangerButton
              type="button"
              @click="deleteIntegration(githubIntegration.id)"
              >
              Delete
            </DangerButton>
          </div>
        </div>
        <div
          v-else
          >
          <div>
            <ConnectToGithub></ConnectToGithub>
            <div class="dark:text-white pt-4">
              Connecting to Github will automatically format pasted links to issues and pull requests.
            </div>
          </div>
        </div>
      </div>
    </template>
  </ActionSection>
</template>

<style scoped>

</style>
