<script setup>
  import { ref } from 'vue';
  import { Head, Link, router } from '@inertiajs/vue3';
  import ApplicationMark from '@/Components/ApplicationMark.vue';
  import Banner from '@/Components/Banner.vue';
  import Dropdown from '@/Components/Dropdown.vue';
  import DropdownLink from '@/Components/DropdownLink.vue';
  import NavLink from '@/Components/NavLink.vue';
  import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
  import StellarNavLink from '@/Components/StellarNavLink.vue';

  defineProps( {
    title: String,
  } );

  const showingNavigationDropdown = ref( false );

  const switchToTeam = ( team ) => {
    router.put( route( 'current-team.update' ), {
      team_id: team.id,
    }, {
      preserveState: false,
    } );
  };

  const logout = () => {
    router.post( route( 'logout' ) );
  };
</script>

<template>
  <div
    class="fixed inset-x-0 min-h-screen -z-10 blur-3xl transform-gpu"
    >
    <div
      class="left-[calc(10%+3rem)] absolute -z-10"
      style="clip-path: circle(50% at 50% 50%);width:200px;height:200px;background-color:#04A6F3"
      ></div>

    <div
      class="left-[calc(30%+3rem)] top-36 absolute"
      style="clip-path: circle(50% at 50% 50%);width:200px;height:200px;background-color:#04A6F3"
      ></div>

    <div
      class="right-[calc(30%+3rem)] top-56 absolute"
      style="clip-path: circle(50% at 50% 50%);width:150px;height:150px;background-color:#04A6F3"
      ></div>

    <div
      class="right-[calc(10%+3rem)] absolute"
      style="clip-path: circle(50% at 50% 50%);width:100px;height:100px;background-color:#04A6F3;top:50px"
      ></div>
  </div>

  <div>
    <Head :title="title"></Head>

    <Banner></Banner>
    <div
      class="min-h-screen"
      >
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex items-center justify-center text-xl gap-3 mt-6">
        <ApplicationMark class="block h-9 w-auto"></ApplicationMark>
        <span class="bg-gradient-to-r from-[#28F09E] to-[#05A8F1] inline-block text-transparent bg-clip-text">
          Stellar Standups
        </span>
      </div>

      <nav class="flex justify-center items-center gap-6 mb-6">
        <StellarNavLink
          :href="route('stand-up-groups.index')"
          :active="route().current('stand-up-groups.index') || route().current('stand-up-groups.show')"
          >
          Standups
        </StellarNavLink>

        <StellarNavLink
          :href="route('pointing-room.index')"
          :active="route().current('pointing-room.index')"
          >
          Pointing Room
        </StellarNavLink>

        <StellarNavLink
          :href="route('profile.show')"
          :active="route().current('profile.show')"
          >
          Profile
        </StellarNavLink>
      </nav>


      <!-- Page Heading -->
      <header
        v-if="$slots.header"
        >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <slot name="header"></slot>
        </div>
      </header>

      <!-- Page Content -->
      <main>
        <slot></slot>
      </main>
    </div>
  </div>
</template>
