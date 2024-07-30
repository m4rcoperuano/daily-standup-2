<script setup lang="ts">
  import TextInput from '@/Components/TextInput.vue';
  import { computed, defineModel, ref } from 'vue';
  import { DateTime } from 'luxon';
  const model = defineModel( { required: false } );
  const selectedButton = ref( 'today' );

  const changeSelectedButton = ( value ) => {
    selectedButton.value = value;

    const option = dayOptions.value.find( ( option ) => option.value === value );

    //get only date in yyyy-mm-dd format taking into account timezone, which would not be using toISOString()
    model.value = DateTime.fromJSDate( option.date ).toISODate();
  };

  const dayOptions = computed( () => {
    const today = new Date();
    const yesterday = new Date();
    const tomorrow = new Date();

    yesterday.setDate( today.getDate() - 1 );
    tomorrow.setDate( today.getDate() + 1 );

    const abbreviationForYesterday = yesterday.toDateString().split( ' ' )[0];
    const abbreviationForTomorrow = tomorrow.toDateString().split( ' ' )[0];
    const abbreviationForToday = today.toDateString().split( ' ' )[0];

    return [
      { value: 'yesterday', label: `Yesterday (${abbreviationForYesterday})`, date: yesterday },
      { value: 'today', label: `Today (${abbreviationForToday})`, date: today },
      { value: 'tomorrow', label: `Tomorrow (${abbreviationForTomorrow})`, date: tomorrow },
    ];
  } );

  const dateChanged = ( date ) => {
    model.value = date;

    const matchingDayOption = dayOptions.value
      .find( ( option ) => DateTime.fromJSDate( option.date ).toISODate() === date );

    if ( !matchingDayOption ) {
      selectedButton.value = '';
    }
    else {
      selectedButton.value = matchingDayOption.value;
    }
  };

</script>

<template>
  <div
    class="inline-flex rounded-md shadow-sm mr-2 gap-2"
    role="group"
    >
    <button
      v-for="option in dayOptions"
      :key="option.value"
      type="button"
      :class="{ 'active': selectedButton === option.value }"
      @click="changeSelectedButton(option.value)"
      >
      {{ option.label }}
    </button>
  </div>
  <TextInput
    :model-value="model"
    type="date"
    @update:model-value="dateChanged"
    ></TextInput>
</template>

<style scoped>
button {
    @apply inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm bg-white
    text-gray-700  focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 hover:text-black dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white;
}

.active {
    @apply bg-teal-600 text-white hover:text-white;
}
</style>
