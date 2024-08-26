<script setup lang="ts">
  defineProps( {
    options: {
      type: Array,
      required: true,
    },
  } );

  const model = defineModel();
</script>

<template>
  <select
    v-model="model"
    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
    >
    <option value="">Select One</option>
    <option
      v-for="board in options"
      :key="board.id"
      :value="board.id"
      >
      {{ board.name }}
    </option>
  </select>
</template>

<style scoped>

</style>
