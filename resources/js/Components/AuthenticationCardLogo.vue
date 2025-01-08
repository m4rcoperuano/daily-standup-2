<script setup>
  import { Link } from '@inertiajs/vue3';
  import ApplicationMark from '@/Components/ApplicationMark.vue';
</script>

<template>
  <Link :href="'/'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-center text-2xl gap-3">
      <ApplicationMark class="block h-9 w-auto"></ApplicationMark>
      <span class="bg-gradient-to-r from-[#28F09E] to-[#05A8F1] inline-block text-transparent bg-clip-text">
        Stellar Standups
      </span>
    </div>
  </Link>
</template>
