<script setup>
  import { Head, Link } from '@inertiajs/vue3';

  defineProps( {
    canLogin: {
      type: Boolean,
    },
    canRegister: {
      type: Boolean,
    },
    laravelVersion: {
      type: String,
      required: true,
    },
    phpVersion: {
      type: String,
      required: true,
    },
  } );

  function handleImageError() {
    document.getElementById( 'screenshot-container' )?.classList.add( '!hidden' );
    document.getElementById( 'docs-card' )?.classList.add( '!row-span-1' );
    document.getElementById( 'docs-card-content' )?.classList.add( '!flex-row' );
    document.getElementById( 'background' )?.classList.add( '!hidden' );
  }
</script>

<template>
  <Head title="Welcome"></Head>
  <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">

    <div class="relative min-h-screen min-w-screen flex items-center justify-start bg-cover bg-center ml-6">
      <div
        class="landing-image"
        >
        <img
          src="../../../public/assets/landing.png"
          height="800"
          width="900"
          alt="landing"
          />
      </div>
      <div class="flex flex-col ml-12">
        <div class="landing-content justify-center">
          <h1 class="text-gray-900 dark:text-gray-300 font-bold text-xl">
            Stellar Standups: Where Teamwork Meets Simplicity!
          </h1>
        </div>
        <div class="">
          <header
            class="items-center gap-2 py-10 lg:grid-cols-3"
            >
            <nav
              v-if="canLogin"
              class="flex flex-1 justify-left"
              >

              <Link
                v-if="$page.props.auth.user"
                :href="route('stand-up-groups.index')"
                class="font-bold outline-1 text-gray-600 ring-1 ring-transparent transition
                hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]
                dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white flex"
                >
                <span>
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"
                    width="30"
                    height="24"
                    ><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path class="icon-path" d="M156.6 384.9L125.7 354c-8.5-8.5-11.5-20.8-7.7-32.2c3-8.9 7-20.5 11.8-33.8L24 288c-8.6 0-16.6-4.6-20.9-12.1s-4.2-16.7 .2-24.1l52.5-88.5c13-21.9 36.5-35.3 61.9-35.3l82.3 0c2.4-4 4.8-7.7 7.2-11.3C289.1-4.1 411.1-8.1 483.9 5.3c11.6 2.1 20.6 11.2 22.8 22.8c13.4 72.9 9.3 194.8-111.4 276.7c-3.5 2.4-7.3 4.8-11.3 7.2l0 82.3c0 25.4-13.4 49-35.3 61.9l-88.5 52.5c-7.4 4.4-16.6 4.5-24.1 .2s-12.1-12.2-12.1-20.9l0-107.2c-14.1 4.9-26.4 8.9-35.7 11.9c-11.2 3.6-23.4 .5-31.8-7.8zM384 168a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"></path></svg>
                </span>
                <span>
                  Launch into your standups
                </span>
              </Link>
              <template v-else>
                <Link
                  :href="route('login')"
                  class="custom-color rounded-md px-3 py-2 text-black ring-1 ring-transparent transition
                  hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-black
                  dark:focus-visible:ring-white"
                  >
                  Log in
                </Link>

                <Link
                  v-if="canRegister"
                  :href="route('register')"
                  class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70
                  focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80
                  dark:focus-visible:ring-white"
                  >
                  Register
                </Link>
              </template>
            </nav>
          </header>
        </div>
      </div>
    </div>
    <div class="selection:bg-[#FF2D20] selection:text-white">
      <div class="relative w-full max-w-2xl lg:max-w-7xl">
        <footer class="pb-4 text-center text-sm text-black dark:text-white/70">
          Innovation starts here. Have a stellar standup!
        </footer>
      </div>
    </div>
  </div>
</template>

<style scoped>
.custom-color {
  background-color: #5BE4A7;

  &:hover {
    background-color: #d9f8e7;

  }
}

.icon-path {
  fill: black;

  @media (prefers-color-scheme: dark) {
    fill: white;
  }
}
</style>