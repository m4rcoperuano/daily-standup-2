<script setup>
  import { Head, Link } from '@inertiajs/vue3';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import ApplicationMark from '@/Components/ApplicationMark.vue';

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
</script>

<template>
  <Head title="Welcome"></Head>
  <div
    class="fixed inset-x-0 min-h-screen -z-10 blur-3xl transform-gpu"
    >
    <div
      class="left-[calc(10%+3rem)] absolute -z-10 hidden lg:block"
      style="clip-path: circle(50% at 50% 50%);width:200px;height:200px;background-color:#04A6F3"
      ></div>

    <div
      class="left-[calc(30%+3rem)] top-36 absolute"
      style="clip-path: circle(50% at 50% 50%);width:200px;height:200px;background-color:#04A6F3"
      ></div>

    <div
      class="right-[calc(30%+3rem)] top-56 absolute hidden xl:block"
      style="clip-path: circle(50% at 50% 50%);width:150px;height:150px;background-color:#04A6F3"
      ></div>

    <div
      class="right-[calc(10%+3rem)] absolute hidden md:block"
      style="clip-path: circle(50% at 50% 50%);width:100px;height:100px;background-color:#04A6F3;top:50px"
      ></div>
  </div>

  <div class="min-h-screen min-w-screen flex flex-col justify-center text-white px-8">
    <div class="relative mx-auto flex flex-col sm:flex-row gap-8 items-center justify-start bg-cover bg-center">
      <div
        class="landing-image"
        >
        <img
          src="../../../public/assets/landing.png"
          height="800"
          width="900"
          alt="space"
          />
      </div>
      <div class="flex flex-col">
        <div class="text-center">
          <ApplicationMark class="mx-auto block h-9 w-auto"></ApplicationMark>
        </div>
        <div
          class="landing-content justify-center text-center"
          style="min-width:300px;"
          >
          <div class="max-w-7xl mx-auto mb-1 px-4 sm:px-6 lg:px-8 flex items-center justify-center text-2xl gap-3 mt-2">
            <span class="bg-gradient-to-r from-[#28F09E] to-[#05A8F1] inline-block text-transparent bg-clip-text">
              Stellar Standups
            </span>
          </div>
          <h2 class="mb-1">
            Where Teamwork Meets Simplicity!
          </h2>
        </div>
        <div class="">
          <header
            class="items-center gap-2 py-2 lg:grid-cols-3"
            >
            <nav
              v-if="canLogin"
              class="flex flex-1 justify-center"
              >
              <Link
                v-if="$page.props.auth.user"
                :href="route('stand-up-groups.index')"
                class="font-bold outline-1 text-gray-600 ring-1 ring-transparent transition
                hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]
                 border rounded-lg py-2 px-4 hover:opacity-50
                dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white flex mx-auto"
                >
                <span
                  style="position: relative;top:2px;"
                  class="mr-2"
                  >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"
                    width="20"
                    height="20"
                    ><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path
                      class="icon-path"
                      d="M156.6 384.9L125.7 354c-8.5-8.5-11.5-20.8-7.7-32.2c3-8.9 7-20.5 11.8-33.8L24 288c-8.6 0-16.6-4.6-20.9-12.1s-4.2-16.7 .2-24.1l52.5-88.5c13-21.9 36.5-35.3 61.9-35.3l82.3 0c2.4-4 4.8-7.7 7.2-11.3C289.1-4.1 411.1-8.1 483.9 5.3c11.6 2.1 20.6 11.2 22.8 22.8c13.4 72.9 9.3 194.8-111.4 276.7c-3.5 2.4-7.3 4.8-11.3 7.2l0 82.3c0 25.4-13.4 49-35.3 61.9l-88.5 52.5c-7.4 4.4-16.6 4.5-24.1 .2s-12.1-12.2-12.1-20.9l0-107.2c-14.1 4.9-26.4 8.9-35.7 11.9c-11.2 3.6-23.4 .5-31.8-7.8zM384 168a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"
                      ></path></svg>
                </span>
                <span>
                  Launch into your standups
                </span>
              </Link>
              <template v-else>
                <PrimaryButton
                  link
                  :route="route('login')"
                  >
                  Log in
                </PrimaryButton>

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
    <div class="selection:bg-[#FF2D20] selection:text-white mx-auto">
      <div class="relative w-full max-w-2xl lg:max-w-7xl">
        <footer class="pb-4 text-center text-md text-white opacity-50">
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
