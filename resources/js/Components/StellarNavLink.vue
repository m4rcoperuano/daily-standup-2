<script setup>
  import { computed } from 'vue';
  import { Link } from '@inertiajs/vue3';

  const props = defineProps( {
    href: String,
    active: Boolean,
  } );

  const classes = computed( () => {
    return props.active
      ? 'inline-flex items-center py-3 px-4 text-quaternary rounded-2xl font-bold bg-primary'
      : 'inline-flex items-center py-3 px-4 text-white rounded-2xl font-bold bg-quaternary hover:opacity-50 transition-opacity';
  } );
</script>

<template>
  <Link
    :href="href"
    :class="classes"
    >
    <slot></slot>
  </Link>
</template>
