<template>
  <div
    class="star-field-wrapper"
    :style="{ height: containerHeight }"
    >
    <!-- Pixelated Background Stars -->
    <div
      v-for="star in stars"
      :key="star.id"
      class="pixel-star"
      :style="{
        left: star.left,
        '--star-color': star.color,
        '--duration': star.duration,
        animationDelay: star.delay,
      }"
      ></div>

    <!-- Thematic Text Overlay -->
    <div class="text-overlay">
      <h2
        class="thematic-text"
        v-html="formattedTitle"
        ></h2>
      <div class="loader-dots">
        <div
          class="dot"
          style="--d: 0s"
          ></div>
        <div
          class="dot"
          style="--d: 0.2s"
          ></div>
        <div
          class="dot"
          style="--d: 0.4s"
          ></div>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref, computed, onMounted } from 'vue';

  const props = defineProps( {
    title: {
      type: String,
      default: 'Looking through\nthe stars...',
    },
    starCount: {
      type: Number,
      default: 40,
    },
    containerHeight: {
      type: String,
      default: '100%',
    },
  } );

  const stars = ref( [] );

  // Handle line breaks for the pixel font look
  const formattedTitle = computed( () => {
    return props.title.replace( /\n/g, '<br>' );
  } );

  onMounted( () => {
    const colors = [ '#05A8F1', '#28F09E', '#ffffff' ];
    const generatedStars = [];

    for ( let i = 0; i < props.starCount; i++ ) {
      generatedStars.push( {
        id: i,
        left: `${Math.random() * 100}%`,
        color: colors[Math.floor( Math.random() * colors.length )],
        duration: `${1.5 + Math.random() * 2}s`,
        // Negative delay ensures stars are already falling on mount
        delay: `${Math.random() * -5}s`,
      } );
    }

    stars.value = generatedStars;
  } );
</script>

<style scoped>
.star-field-wrapper {
  position: relative;
  width: 100%;
  background: transparent;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Press Start 2P', cursive;
  image-rendering: pixelated;
}

.pixel-star {
  position: absolute;
  top: -100px;
  width: 4px;
  height: 4px;
  background: var(--star-color);
  /* Blocky motion trail */
  box-shadow:
    0 4px 0 var(--star-color),
    0 8px 0 rgba(255,255,255,0.3),
    0 12px 0 rgba(255,255,255,0.1);
  opacity: 0;
  animation: fall var(--duration) linear infinite;
}

.text-overlay {
  z-index: 10;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 2rem;
  pointer-events: none;
  padding: 20px;
}

.thematic-text {
  color: #05A8F1;
  font-size: 14px;
  line-height: 1.8;
  text-align: center;
  text-shadow: 4px 4px 0px #000;
  animation: pixelPulse 1.5s steps(2) infinite;
}

.loader-dots {
  display: flex;
  gap: 12px;
}

.dot {
  width: 8px;
  height: 8px;
  background-color: #28F09E;
  box-shadow: 2px 2px 0px #000;
  animation: jump 0.6s steps(2) infinite alternate;
  animation-delay: var(--d);
}

@keyframes fall {
  0% { transform: translateY(0); opacity: 0; }
  10% { opacity: 1; }
  90% { opacity: 1; }
  100% { transform: translateY(calc(100vh + 100px)); opacity: 0; }
}

@keyframes pixelPulse {
  from { color: #05A8F1; }
  to { color: #28F09E; }
}

@keyframes jump {
  from { transform: translateY(0); }
  to { transform: translateY(-8px); }
}
</style>
