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
    @apply inline-flex items-center px-4 py-2 text-quaternary bg-gradient-to-r from-[#05A8F1] to-[#28F09E] border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:opacity-50 focus:bg-primary active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2  disabled:opacity-50 transition ease-in-out duration-150
}
</style>
