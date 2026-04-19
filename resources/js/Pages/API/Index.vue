<script setup>
  import ApiTokenManager from '@/Pages/API/Partials/ApiTokenManager.vue';
  import StellarLayout from '@/Layouts/StellarLayout.vue';

  defineProps( {
    tokens: Array,
    availablePermissions: Array,
    defaultPermissions: Array,
  } );
</script>

<template>
  <StellarLayout title="API Tokens">
    <template #header>
      <h2 class="font-semibold text-xl text-primary mb-4">
        API Tokens
      </h2>
    </template>

    <div class="text-white">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <ApiTokenManager
          :tokens="tokens"
          :available-permissions="availablePermissions"
          :default-permissions="defaultPermissions"
          ></ApiTokenManager>
      </div>
    </div>
  </StellarLayout>
</template>
