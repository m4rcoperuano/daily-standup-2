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
  } );

  const daysRemaining  = DateTime.fromJSDate( props.endDate ).diff( DateTime.now(), 'days' ).days;
  const totalDays = DateTime.fromJSDate( props.endDate ).diff( DateTime.fromJSDate( props.startDate ), 'days' ).days;

  const chartData = {
    datasets: [ {
      label: 'Days Remaining',
      data: [ Math.ceil( totalDays - daysRemaining ), Math.ceil( daysRemaining ) ],
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
        <div class="">{{ Math.ceil(daysRemaining) }}</div>
      </div>
    </div>
    <div class="text-sm italic text-center mt-1">
      Days Remaining in Sprint
    </div>
  </div>
</template>

<style scoped>

</style>
