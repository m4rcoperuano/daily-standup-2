<script setup>
  import { Head, Link } from '@inertiajs/vue3';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import ApplicationMark from '@/Components/ApplicationMark.vue';
  import Starfield from '@/Components/Starfield.vue';

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

  <div class="max-w-7xl mx-auto py-12 sm:py-24 text-white px-8">
    <div class="relative mx-auto flex flex-col sm:flex-row gap-4 sm:gap-8 items-center justify-center">
      <div class="text-center flex flex-col sm:hidden">
        <ApplicationMark class="mx-auto block h-9 w-auto"></ApplicationMark>
        <div class="flex sm:hidden max-w-7xl mx-auto mt-4 items-center justify-center text-2xl">
          <span class="bg-gradient-to-r from-[#28F09E] to-[#05A8F1] inline-block text-transparent bg-clip-text">
            Stellar Standups
          </span>
        </div>
      </div>

      <div style="max-width:800px;">
        <img
          src="/assets/landing.png"
          alt="Landing page image"
          />
      </div>
      <div class="flex flex-col">
        <div class="text-center hidden sm:flex">
          <ApplicationMark class="mx-auto block h-9 w-auto"></ApplicationMark>
        </div>
        <div
          class="landing-content justify-center text-center"
          style="min-width:300px;"
          >
          <div class="hidden sm:flex max-w-7xl mx-auto mb-1 px-4 sm:px-6 lg:px-8 items-center justify-center text-2xl gap-3 mt-2">
            <span class="bg-gradient-to-r from-[#28F09E] to-[#05A8F1] inline-block text-transparent bg-clip-text">
              Stellar Standups
            </span>
          </div>
          <h2 class="mb-1">
            Where Teamwork Meets Simplicity!
          </h2>
        </div>
        <div>
          <header
            class="items-center gap-2 py-2 lg:grid-cols-3"
            >
            <nav
              v-if="canLogin"
              class="flex flex-1 justify-center"
              >
              <PrimaryButton
                v-if="$page.props.auth.user"
                :route="route('stand-up-groups.index')"
                link
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
                      d="M156.6 384.9L125.7 354c-8.5-8.5-11.5-20.8-7.7-32.2c3-8.9 7-20.5 11.8-33.8L24 288c-8.6 0-16.6-4.6-20.9-12.1s-4.2-16.7 .2-24.1l52.5-88.5c13-21.9 36.5-35.3 61.9-35.3l82.3 0c2.4-4 4.8-7.7 7.2-11.3C289.1-4.1 411.1-8.1 483.9 5.3c11.6 2.1 20.6 11.2 22.8 22.8c13.4 72.9 9.3 194.8-111.4 276.7c-3.5 2.4-7.3 4.8-11.3 7.2l0 82.3c0 25.4-13.4 49-35.3 61.9l-88.5 52.5c-7.4 4.4-16.6 4.5-24.1 .2s-12.1-12.2-12.1-20.9l0-107.2c-14.1 4.9-26.4 8.9-35.7 11.9c-11.2 3.6-23.4 .5-31.8-7.8zM384 168a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"
                      ></path></svg>
                </span>
                <span>
                  Launch into your standups
                </span>
              </PrimaryButton>
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
                  class="rounded-md px-3 py-2  ring-1 ring-transparent transition
                  focus:outline-none text-white hover:text-white/80
                  focus-visible:ring-white"
                  >
                  Register
                </Link>
              </template>
            </nav>
          </header>
        </div>
      </div>
    </div>
    <div class="py-12">
      <div class="mx-auto max-w-2xl px-6 lg:max-w-7xl lg:px-8">
        <h2 class="text-base/7 font-semibold text-primary">Stay in sync</h2>
        <p class="mt-2 max-w-7xl text-pretty text-4xl font-semibold tracking-tight text-white sm:text-5xl">
          Keep your team in the loop
        </p>
        <div class="mt-10 grid grid-cols-1 gap-4 sm:mt-16 lg:grid-cols-6 lg:grid-rows-2">
          <div class="flex p-px lg:col-span-4">
            <div
              class="overflow-hidden rounded-lg bg-quaternary ring-1 ring-white/15 max-lg:rounded-t-[2rem] lg:rounded-tl-[2rem]"
              >
              <img
                class="w-100 object-cover object-left"
                src="/assets/standup-entry.png"
                alt=""
                />
              <div class="p-10">
                <h3 class="text-sm/4 font-semibold text-gray-400">Stand Ups</h3>
                <p class="mt-2 text-lg font-medium tracking-tight text-white">Log your work</p>
                <p class="mt-2 max-w-lg text-sm/6 text-gray-400">
                  Keep track of what you're working on today, yesterday, and any blockers. Share with your team to keep everyone in the loop with our easy to use interface!
                </p>
              </div>
            </div>
          </div>
          <div class="flex p-px lg:col-span-2">
            <div class="overflow-hidden rounded-lg bg-quaternary ring-1 ring-white/15 lg:rounded-tr-[2rem]">
              <img
                class="w-100 object-cover"
                src="/assets/jira-github-integrations.png"
                alt=""
                />
              <div class="p-10">
                <h3 class="text-sm/4 font-semibold text-gray-400">Integrations</h3>
                <p class="mt-2 text-lg font-medium tracking-tight text-white">Connect your favorite tools</p>
                <p class="mt-2 max-w-lg text-sm/6 text-gray-400">When you connect JIRA or Github, any links you paste into your stand up are automatically formatted like so:
                </p>
                <div class="inline not-prose">
                  <div
                    class="bg-teal-950 text-blue-200 mb-2 hover:bg-teal-900 rounded inline break-all py-1 pr-1"
                    style="line-height:34px;"
                    >
                    <img
                      src="/assets/atlassian-generic.ico"
                      class="inline-block p-1 rounded mr-1 bg-white"
                      style="height:25px;position:relative;top:-2px;"
                      />
                    SO-123 - Fixing the bug
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="flex p-px lg:col-span-2">
            <div class="overflow-hidden rounded-lg bg-quaternary ring-1 ring-white/15 lg:rounded-bl-[2rem]">
              <img
                class="w-100 object-cover"
                src="/assets/pointing.png"
                alt=""
                />
              <div class="p-10">
                <h3 class="text-sm/4 font-semibold text-gray-400">Poker Planning</h3>
                <p class="mt-2 text-lg font-medium tracking-tight text-white">Story pointing made easy</p>
                <p class="mt-2 max-w-lg text-sm/6 text-gray-400">You and your team can use the Pointing Room to estimate work in a fun and interactive way. No more endless meetings, just point and go!
                </p></div>
            </div>
          </div>
          <div class="flex p-px lg:col-span-4">
            <div
              class="overflow-hidden rounded-lg bg-quaternary ring-1 ring-white/15 max-lg:rounded-b-[2rem] lg:rounded-br-[2rem]"
              >
              <img
                class="w-full object-cover object-left"
                src="/assets/collaboration.png"
                alt=""
                />
              <div class="p-10">
                <h3 class="text-sm/4 font-semibold text-gray-400">Collaboration</h3>
                <p class="mt-2 text-lg font-medium tracking-tight text-white">All your stand up entries in one place</p>
                <p class="mt-2 max-w-lg text-sm/6 text-gray-400">As you being new sprints, you'll be able to view everyone's stand up entries in an easy to use interface.
                  All work belonging to your team is tracked under one roof, making it easy to see what everyone is working on.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="py-12">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-7xl lg:mx-0">
          <p class="text-base/7 font-semibold text-primary">Stellar Standups</p>
          <h2 class="mt-2 text-5xl font-semibold tracking-tight text-white">About</h2>
          <p class="mt-8 text-pretty text-lg font-medium text-white sm:text-xl/8">
            As a Software Developer, I found that using a long-running document to keep the team in sync was a bit cumbersome. I wanted a build an app that could take everyone's inputs into one place, and make it easy to see what everyone was working on.
            I also thought it would be fun to incorporate some of the tools we use every day, like JIRA and Github, to make it easier to share links and keep everyone in the loop. Eventually, I'd love to add some more interact-ability so that stand ups
            can be more engaging. I hope you enjoy using Stellar Standups as much as I enjoyed building it!
          </p>
        </div>
      </div>
    </div>

    <footer>
      <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex flex-col items-center gap-4">
          <div class="flex gap-4">
            <a
              href="/privacy"
              >
              Privacy Policy
            </a>
            <a
              href="/terms"
              >
              Terms of Service
            </a>
          </div>
          <div class="text-sm/6 text-gray-400">
            &copy; 2025 Sunny Orlando Devs, LLC. All rights reserved.
          </div>
        </div>
      </div></footer>

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
