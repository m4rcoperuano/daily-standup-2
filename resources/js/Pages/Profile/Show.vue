<script setup>
  import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
  import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
  import SectionBorder from '@/Components/SectionBorder.vue';
  import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
  import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
  import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
  import SocialiteIntegrations from '@/Pages/Profile/Partials/SocialiteIntegrations.vue';
  import StellarLayout from '@/Layouts/StellarLayout.vue';
  import DropdownLink from '@/Components/DropdownLink.vue';
  import Dropdown from '@/Components/Dropdown.vue';
  import { router } from '@inertiajs/vue3';

  defineProps( {
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
  } );

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
  <StellarLayout title="Profile">
    <template #header>
      <div class="flex items-center mb-4">
        <h2 class="font-semibold text-xl text-primary flex-grow">
          Profile
        </h2>
        <div>
          <Dropdown
            v-if="$page.props.jetstream.hasTeamFeatures"
            align="right"
            width="60"
            >
            <template #trigger>
              <span class="inline-flex rounded-md">
                <button
                  type="button"
                  class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md btn-shine text-quaternary transition ease-in-out duration-150"
                  >
                  {{ $page.props.auth.user.current_team.name }}

                  <svg
                    class="ms-2 -me-0.5 h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                      ></path>
                  </svg>
                </button>
              </span>
            </template>

            <template #content>
              <div class="w-60">
                <!-- Team Management -->
                <div class="block px-4 py-2 text-xs text-gray-400">
                  Manage Team
                </div>

                <!-- Team Settings -->
                <DropdownLink :href="route('teams.show', $page.props.auth.user.current_team)">
                  Team Settings
                </DropdownLink>

                <DropdownLink
                  v-if="$page.props.jetstream.canCreateTeams"
                  :href="route('teams.create')"
                  >
                  Create New Team
                </DropdownLink>

                <!-- Authentication -->
                <form @submit.prevent="logout">
                  <DropdownLink as="button">
                    Log Out
                  </DropdownLink>
                </form>

                <!-- Team Switcher -->
                <template v-if="$page.props.auth.user.all_teams.length > 1">
                  <div class="border-t border-gray-600"></div>

                  <div class="block px-4 py-2 text-xs text-gray-400">
                    Switch Teams
                  </div>

                  <template
                    v-for="team in $page.props.auth.user.all_teams"
                    :key="team.id"
                    >
                    <form @submit.prevent="switchToTeam(team)">
                      <DropdownLink as="button">
                        <div class="flex items-center">
                          <svg
                            v-if="team.id == $page.props.auth.user.current_team_id"
                            class="me-2 h-5 w-5 text-green-400"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                              ></path>
                          </svg>

                          <div>{{ team.name }}</div>
                        </div>
                      </DropdownLink>
                    </form>
                  </template>
                </template>
              </div>
            </template>
          </Dropdown>
        </div>
      </div>
    </template>

    <div>
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
          v-if="$page.props.jetstream.canUpdateProfileInformation"
          class="mb-4"
          >
          <UpdateProfileInformationForm :user="$page.props.auth.user"></UpdateProfileInformationForm>

          <SectionBorder></SectionBorder>
        </div>

        <div>
          <SocialiteIntegrations :user="$page.props.auth.user"></SocialiteIntegrations>
          <SectionBorder></SectionBorder>
        </div>

        <div v-if="$page.props.jetstream.canUpdatePassword">
          <UpdatePasswordForm class="mt-10 sm:mt-0"></UpdatePasswordForm>

          <SectionBorder></SectionBorder>
        </div>

        <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
          <TwoFactorAuthenticationForm
            :requires-confirmation="confirmsTwoFactorAuthentication"
            class="mt-10 sm:mt-0"
            ></TwoFactorAuthenticationForm>

          <SectionBorder></SectionBorder>
        </div>

        <LogoutOtherBrowserSessionsForm
          :sessions="sessions"
          class="mt-10 sm:mt-0"
          ></LogoutOtherBrowserSessionsForm>

        <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
          <SectionBorder></SectionBorder>

          <DeleteUserForm class="mt-10 sm:mt-0"></DeleteUserForm>
        </template>
      </div>
    </div>
  </StellarLayout>
</template>
