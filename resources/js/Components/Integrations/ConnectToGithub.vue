<script setup lang="ts">
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
</script>

<template>
  <button
    class="inline-flex items-center px-4 py-2 bg-gray-950 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150"
    type="button"
    @click="showAdvertisement = true"
    >
    <svg
      class="mr-2"
      xmlns="http://www.w3.org/2000/svg"
      width="17"
      height="17"
      viewBox="0 0 32 32"
      fill="none"
      >
      <path
        fill-rule="evenodd"
        clip-rule="evenodd"
        d="M16 0C7.16 0 0 7.16 0 16C0 23.08 4.58 29.06 10.94 31.18C11.74 31.32 12.04 30.84 12.04 30.42C12.04 30.04 12.02 28.78 12.02 27.44C8 28.18 6.96 26.46 6.64 25.56C6.46 25.1 5.68 23.68 5 23.3C4.44 23 3.64 22.26 4.98 22.24C6.24 22.22 7.14 23.4 7.44 23.88C8.88 26.3 11.18 25.62 12.1 25.2C12.24 24.16 12.66 23.46 13.12 23.06C9.56 22.66 5.84 21.28 5.84 15.16C5.84 13.42 6.46 11.98 7.48 10.86C7.32 10.46 6.76 8.82 7.64 6.62C7.64 6.62 8.98 6.2 12.04 8.26C13.32 7.9 14.68 7.72 16.04 7.72C17.4 7.72 18.76 7.9 20.04 8.26C23.1 6.18 24.44 6.62 24.44 6.62C25.32 8.82 24.76 10.46 24.6 10.86C25.62 11.98 26.24 13.4 26.24 15.16C26.24 21.3 22.5 22.66 18.94 23.06C19.52 23.56 20.02 24.52 20.02 26.02C20.02 28.16 20 29.88 20 30.42C20 30.84 20.3 31.34 21.1 31.18C27.42 29.06 32 23.06 32 16C32 7.16 24.84 0 16 0V0Z"
        fill="white"
        ></path>
    </svg>
    Connect to Github
  </button>
  <DialogModal
    :show="showAdvertisement"
    :closeable="false"
    @close="showAdvertisement = false"
    >
    <template #title>
      <div class="text-center text-2xl">
        Github Integration
      </div>
    </template>
    <template #content>
      <div
        v-if="!showPending"
        class="text-md dark:text-white text-center"
        >
        <p class="mb-2">
          Auto-formats pasted links to Github issues and Pull Requests ✨
        </p>
        <img
          src="/assets/github-autoformatting.png"
          alt="JIRA autoformatting"
          style="width:450px;"
          class="mb-4 shadow-md mx-auto"
          />
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
          class="inline-flex items-center px-4 py-2 bg-gray-950 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150"
          type="button"
          :href="route('socialite.redirect', 'github')"
          target="_blank"
          @click="showPending = true"
          >
          <svg
            class="mr-2"
            xmlns="http://www.w3.org/2000/svg"
            width="17"
            height="17"
            viewBox="0 0 32 32"
            fill="none"
            >
            <path
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M16 0C7.16 0 0 7.16 0 16C0 23.08 4.58 29.06 10.94 31.18C11.74 31.32 12.04 30.84 12.04 30.42C12.04 30.04 12.02 28.78 12.02 27.44C8 28.18 6.96 26.46 6.64 25.56C6.46 25.1 5.68 23.68 5 23.3C4.44 23 3.64 22.26 4.98 22.24C6.24 22.22 7.14 23.4 7.44 23.88C8.88 26.3 11.18 25.62 12.1 25.2C12.24 24.16 12.66 23.46 13.12 23.06C9.56 22.66 5.84 21.28 5.84 15.16C5.84 13.42 6.46 11.98 7.48 10.86C7.32 10.46 6.76 8.82 7.64 6.62C7.64 6.62 8.98 6.2 12.04 8.26C13.32 7.9 14.68 7.72 16.04 7.72C17.4 7.72 18.76 7.9 20.04 8.26C23.1 6.18 24.44 6.62 24.44 6.62C25.32 8.82 24.76 10.46 24.6 10.86C25.62 11.98 26.24 13.4 26.24 15.16C26.24 21.3 22.5 22.66 18.94 23.06C19.52 23.56 20.02 24.52 20.02 26.02C20.02 28.16 20 29.88 20 30.42C20 30.84 20.3 31.34 21.1 31.18C27.42 29.06 32 23.06 32 16C32 7.16 24.84 0 16 0V0Z"
              fill="white"
              ></path>
          </svg>
          Connect to Github
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
