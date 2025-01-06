<script setup>
  import { onMounted, ref } from 'vue';

  defineProps( {
    modelValue: String,
  } );

  defineEmits( [ 'update:modelValue' ] );

  const input = ref( null );

  onMounted( () => {
    if ( input.value.hasAttribute( 'autofocus' ) ) {
      input.value.focus();
    }
  } );

  defineExpose( { focus: () => input.value.focus() } );
</script>

<template>
  <input
    ref="input"
    class="border-quaternary bg-five text-gray-300 focus:ring-primary rounded-md shadow-sm"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    />
</template>
