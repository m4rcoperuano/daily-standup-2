<script setup lang="ts">
  defineProps( {
    currentVersion: {
      type: String,
      required: false,
      default: null,
    },
    upgrade: {
      type: Boolean,
      required: false,
      default: false,
    },
  } );

  const emit = defineEmits( [
    'user-connected',
  ] );

  import DialogModal from '@/Components/DialogModal.vue';
  import { ref } from 'vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';

  const showAdvertisement = ref( false );
  const showPending = ref( false );

  const closeAndEmit = () => {
    showAdvertisement.value = false;
    emit( 'user-connected' );

    setTimeout( () => {
      showPending.value = false;
    }, 500 );
  };

  const showAd = () => {
    showAdvertisement.value = true;
  };
</script>

<template>
  <slot :show-ad="showAd">
    <button
      class="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150"
      type="button"
      @click="showAdvertisement = true"
      >
      <img
        src="/assets/atlassian-generic.ico"
        style="width:15px;"
        class="mr-1"
        alt="logo"
        />
      {{ upgrade ? 'Upgrade' : 'Connect to JIRA' }}
    </button>
  </slot>
  <DialogModal
    :show="showAdvertisement"
    :closeable="false"
    @close="showAdvertisement = false"
    >
    <template #title>
      <div class="text-center text-2xl">
        {{ upgrade ? 'Upgrade' : '' }} JIRA Integration
      </div>
    </template>
    <template #content>
      <div
        v-if="!showPending"
        class="text-md dark:text-white text-center"
        >
        <div v-if="currentVersion === null">
          <p class="mb-2">
            Auto-formats pasted links to JIRA issues and Confluence articles ✨
          </p>
          <img
            src="/assets/jira-autoformatting.png"
            alt="JIRA autoformatting"
            style="width:450px;"
            class="mb-4 shadow-md mx-auto"
            />
        </div>

        <div v-if="currentVersion === null || currentVersion === '1.0.0'">
          <p class="mb-2">
            View the number of days left in your sprint by connecting your Stand Up Group to your Sprint. You will then see our sprint widget when entering your Stand Up Entries 🎉
          </p>
          <img
            src="/assets/sprint-countdown-widget.png"
            alt="JIRA Sprint Countdown Widget"
            style="width:450px;"
            class="shadow-md mx-auto"
            />
        </div>
      </div>
      <div
        v-else
        class="text-center"
        >
        <p>When you're all done, just click the button below to close the dialog.</p>
        <div class="mt-4">
          <PrimaryButton
            type="button"
            @click="closeAndEmit"
            >
            Close
          </PrimaryButton>
        </div>
      </div>
    </template>
    <template #footer>
      <div
        v-if="!showPending"
        class="text-center mx-auto pb-4"
        >
        <a
          class="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150"
          type="button"
          :href="route('socialite.redirect', 'atlassian')"
          target="_blank"
          @click="showPending = true"
          >
          <img
            src="https://dac-static.atlassian.com/favicon.ico"
            style="width:15px;"
            class="mr-1"
            alt="logo"
            />
          {{ upgrade ? 'Upgrade' : 'Connect to JIRA' }}
        </a>
        <div class="pt-4">
          <button
            type="button"
            class="dark:text-gray-400 hover:opacity-50"
            @click="showAdvertisement = false"
            >
            Not now
          </button>
        </div>
      </div>
    </template>
  </DialogModal>
</template>

<style scoped>

</style>
