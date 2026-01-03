<script setup lang="ts">

</script>

<template>
  <!-- Spaceman -->
  <div class="spaceman-wrapper">
    <svg
      viewBox="0 0 64 64"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      class="pixel-spaceman h-full"
      >
      <!-- Body -->
      <rect
        x="20"
        y="24"
        width="24"
        height="24"
        fill="#E2E8F0"
        ></rect>
      <!-- Backpack -->
      <rect
        x="16"
        y="28"
        width="8"
        height="16"
        fill="#94A3B8"
        ></rect>
      <!-- Helmet -->
      <rect
        x="24"
        y="12"
        width="16"
        height="16"
        fill="#E2E8F0"
        ></rect>
      <!-- Visor -->
      <rect
        x="28"
        y="16"
        width="12"
        height="8"
        fill="#1E293B"
        ></rect>
      <!-- Arms -->
      <rect
        x="16"
        y="28"
        width="4"
        height="12"
        fill="#E2E8F0"
        ></rect>
      <rect
        x="44"
        y="28"
        width="4"
        height="12"
        fill="#E2E8F0"
        ></rect>
      <!-- Legs -->
      <rect
        x="20"
        y="48"
        width="8"
        height="8"
        fill="#E2E8F0"
        ></rect>
      <rect
        x="36"
        y="48"
        width="8"
        height="8"
        fill="#E2E8F0"
        ></rect>
      <!-- Details (Props-driven colors) -->
      <rect
        x="24"
        y="32"
        width="4"
        height="4"
        fill="#05A8F1"
        ></rect>
      <rect
        x="36"
        y="32"
        width="4"
        height="4"
        fill="#28F09E"
        ></rect>
    </svg>
  </div>
</template>

<style scoped>
@keyframes floatSpaceman {
  from { transform: translateY(-20px) rotate(-3deg); }
  to { transform: translateY(20px) rotate(3deg); }
}

.spaceman-wrapper {
  z-index: 10;
  filter: drop-shadow(0 0 20px rgba(5, 168, 241, 0.2));
  animation: floatSpaceman 4s steps(12) infinite alternate;
}
</style>
