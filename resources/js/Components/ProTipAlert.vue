<script setup lang="ts">
  const props = defineProps( {
    proTipName: {
      type: String,
      required: true,
    },
  } );

  import { onMounted, ref } from 'vue';

  const proTipsAcknowledged = ref( [] );
  const initialized = ref( false );
  const acknowledge = ( ) => {
    proTipsAcknowledged.value.push( props.proTipName );
    localStorage.setItem( 'proTipsAcknowledged', JSON.stringify( proTipsAcknowledged.value ) );
  };

  onMounted( () => {
    if ( localStorage.getItem( 'proTipsAcknowledged' ) ) {
      proTipsAcknowledged.value = JSON.parse( localStorage.getItem( 'proTipsAcknowledged' ) );
    }

    initialized.value = true;
  } );
</script>

<template>
  <div
    v-if="initialized && !proTipsAcknowledged.includes(proTipName)"
    class="mt-4 bg-gray-200 dark:bg-gray-800 p-4 rounded-lg text-sm dark:text-gray-300 text-gray-800"
    >
    <span class="font-bold">Pro tip!</span>
    <slot></slot>
    <br />
    <button
      type="button"
      class="bg-gray-300 dark:bg-gray-700 mt-1 px-2 rounded shadow hover:bg-gray-500"
      @click="acknowledge"
      >Dismiss</button>
  </div>
</template>

<style scoped>

</style>
