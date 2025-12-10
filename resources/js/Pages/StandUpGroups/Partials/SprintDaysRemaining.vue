<script setup lang="ts">
  import { Doughnut } from 'vue-chartjs';
  import { Chart as ChartJS, ArcElement } from 'chart.js';
  import { DateTime } from 'luxon';

  ChartJS.register( ArcElement );

  const props = defineProps( {
    startDate: {
      type: Date,
      required: true,
    },
    endDate: {
      type: Date,
      required: true,
    },
    goal: {
      type: String,
      required: false,
    },
  } );

  const start = DateTime.fromJSDate( props.startDate ).startOf( 'day' );
  const end = DateTime.fromJSDate( props.endDate ).startOf( 'day' );
  const now = DateTime.now().startOf( 'day' );
  const totalDays = end.diff( start, 'days' ).days - 1;
  const daysElapsed = now.diff( start, 'days' ).days;
  const daysRemaining = totalDays - daysElapsed;

  const chartData = {
    datasets: [ {
      label: 'Days Remaining',
      data: [ Math.floor( daysElapsed ), Math.floor( daysRemaining ) ],
      backgroundColor: [
        'rgb(0,202,62)',
        'rgb(179,179,179)',
      ],
    } ],
  };

  const chartOptions = {
    responsive: true,
  };
</script>

<template>
  <div class="grid grid-cols-1">
    <div
      class="relative mx-auto"
      >
      <Doughnut
        style="height:80px;"
        :options="chartOptions"
        :data="chartData"
        ></Doughnut>
      <div
        style="top:50%;margin-top:-17px;text-align: center"
        class="absolute w-full text-2xl font-extrabold"
        >
        <div v-if="daysRemaining > 0">{{ Math.floor(daysRemaining) }}</div>
      </div>
    </div>
    <div class="whitespace-pre text-center pt-4">{{ goal }}</div>
  </div>
</template>

<style scoped>

</style>
