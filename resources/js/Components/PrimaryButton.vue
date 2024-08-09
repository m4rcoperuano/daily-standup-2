<script setup>
  import { Link } from '@inertiajs/vue3';
  const props = defineProps( {
    type: {
      type: String,
      default: 'submit',
    },
    link: {
      type: Boolean,
      default: false,
    },
    route: {
      type: String,
      default: '',
    },
    external: {
      type: Boolean,
      default: false,
    },
  } );
</script>

<template>
  <a
    v-if="external"
    :href="route"
    class="button"
    >
    <slot></slot>
  </a>
  <Link
    v-else-if="link"
    :href="route"
    class="button"
    >
    <slot></slot>
  </Link>
  <button
    v-else
    :type="type"
    class="button"
    >
    <slot></slot>
  </button>
</template>

<style scoped>
.button {
    @apply inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150
}
</style>
