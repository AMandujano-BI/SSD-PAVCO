<template>
  <nav
    v-show="isNavBarVisible"
    class="
      top-0
      left-0
      right-0
      fixed
      border-b border-gray-100
      z-30
      w-screen
      h-[10vh]
      transition-position
      xl:pl-60
      lg:w-auto lg:items-stretch
      dark:bg-gray-900 dark:border-gray-800
      flex 
      justify-center
      items-center
      bg-primary 
      md:bg-white
    "
    :class="{ 'ml-60 lg:ml-0': isAsideMobileExpanded }"
  >
    <div class="flex w-full justify-between h-full mx-auto container px-5 ">
      <div class="flex">
        <!-- Logo -->
        <div class="flex-shrink-0 items-center hidden md:flex">
          <!-- <Link :href="route('dashboard')"> -->
          <h1 class="text-[#3b4559] font-normal text-[20px]">
            Salt Spray Database
          </h1>
          <!-- </Link> -->
        </div>

        <!-- Navigation Links -->
        <button
          class="
            flex
            items-center
            justify-center
            sm:ml-10
            text-gray-900
            lg:hidden
            
          "
          @click.prevent="menuToggleMobile"
        >
        <div class="hidden md:block">

          <svg
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
            aria-hidden="true"
            role="img"
            width="24"
            height="24"
            preserveAspectRatio="xMidYMid meet"
            viewBox="0 0 1024 1024"
            
          >
            <path
              d="M408 442h480c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8H408c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8zm-8 204c0 4.4 3.6 8 8 8h480c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8H408c-4.4 0-8 3.6-8 8v56zm504-486H120c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8h784c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8zm0 632H120c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8h784c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8zM142.4 642.1L298.7 519a8.84 8.84 0 0 0 0-13.9L142.4 381.9c-5.8-4.6-14.4-.5-14.4 6.9v246.3a8.9 8.9 0 0 0 14.4 7z"
              fill="currentColor"
            />
          </svg>
        </div>
         <div class=" md:hidden">

          <svg
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
            aria-hidden="true"
            role="img"
            width="24"
            height="24"
            preserveAspectRatio="xMidYMid meet"
            viewBox="0 0 1024 1024"
            
          >
            <path
              d="M408 442h480c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8H408c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8zm-8 204c0 4.4 3.6 8 8 8h480c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8H408c-4.4 0-8 3.6-8 8v56zm504-486H120c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8h784c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8zm0 632H120c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8h784c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8zM142.4 642.1L298.7 519a8.84 8.84 0 0 0 0-13.9L142.4 381.9c-5.8-4.6-14.4-.5-14.4 6.9v246.3a8.9 8.9 0 0 0 14.4 7z"
              fill="#fff"
            />
          </svg>
        </div>
        </button>
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <div>
            <button
              class="
                hidden
                text-gray-900
                h-full
                lg:flex lg:justify-center lg:items-center
                xl:hidden
              "
              @click.prevent="menuOpenLg"
            >
              <svg
                class="h-6 w-6"
                stroke="currentColor"
                fill="none"
                viewBox="0 0 24 24"
              >
                <path
                  :class="{
                    'inline-flex': !showingNavigationDropdown,
                  }"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
                <path
                  :class="{
                    hidden: !showingNavigationDropdown,
                    'inline-flex': showingNavigationDropdown,
                  }"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>
      <div class="flex justify-around md:justify-end items-center flex-1">
        <div class="flex items-center justify-center pl-8 md:pl-2 flex-1 md:flex-none">
          <div  class="pl-12"  :class="{ hidden: route().current().split('.')[0] == 'run' && route().current().split('.')[1] == 'index' }">

               <pavco-icon/>
          </div>
          
          <div
            class="relative text-gray-600 focus-within:text-gray-400"
            :class="{ hidden: route().current().split('.')[0] != 'run' ||  route().current().split('.')[1] != 'index'}"
          >
            <span class="absolute inset-y-0 left-0 flex items-center pl-2">
              <button
                type="submit"
                class="p-1 focus:outline-none focus:shadow-outline"
              >
                <svg
                  fill="none"
                  stroke="#ccdbe9"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                  class="w-6 h-6"
                >
                  <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </button>
            </span>
           
            <input
              type="text"
              class="py-2 text-sm pl-10 rounded-full border-[#ccdbe9] placeholder-[#ccdbe9] text-[#333]"
              id="filterRunInput"
              placeholder="Search Runs..."
              autocomplete="off"
            />
          </div>
        </div>

        <div class="flex items-center">
          <div class="ml-3 relative">
            <!-- Teams Dropdown -->
            <jet-dropdown
              align="right"
              width="60"
              v-if="$page.props.jetstream.hasTeamFeatures"
            >
              <template #trigger>
                <span class="inline-flex rounded-md">
                  <button
                    type="button"
                    class="
                      inline-flex
                      items-center
                      px-3
                      py-2
                      border border-transparent
                      text-sm
                      leading-4
                      font-medium
                      rounded-md
                      text-gray-500
                      hover:bg-gray-50 hover:text-gray-700
                      focus:outline-none focus:bg-gray-50
                      active:bg-gray-50
                      transition
                    "
                  >
                    <!-- {{ $page.props.user.current_team.name }} -->

                    <svg
                      class="ml-2 -mr-0.5 h-4 w-4"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                      />
                    </svg>
                  </button>
                </span>
              </template>
            </jet-dropdown>
          </div>

          <!-- Settings Dropdown -->
          <div class="ml-3 relative -mr-2 flex items-center">
            <jet-dropdown align="right" width="48">
              <template #trigger>
                <div
                  v-if="$page.props.jetstream.managesProfilePhotos"
                  class="
                    flex
                    text-sm
                    border-2 border-transparent
                    rounded-full
                    focus:outline-none focus:border-gray-300
                    transition
                  "
                >
                  <img
                    class="h-8 w-8 rounded-full object-cover"
                    :src="$page.props.user.profile_photo_url"
                    :alt="$page.props.user.name"
                  />
                </div>

                <span v-else class="inline-flex rounded-md">
                  <div
                    type="button"
                    class="
                      inline-flex
                      items-center
                      px-3
                      py-2
                      border border-transparent
                      text-sm
                      leading-4
                      font-medium
                      rounded-md
                    
                      md:hover:text-gray-700
                      text-white
                      md:text-gray-500
                      focus:outline-none
                      transition
                    "
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      xmlns:xlink="http://www.w3.org/1999/xlink"
                      aria-hidden="true"
                      role="img"
                      width="24"
                      height="24"
                      preserveAspectRatio="xMidYMid meet"
                      viewBox="0 0 24 24"
                    >
                      <path
                        d="M7.5 6.5C7.5 8.981 9.519 11 12 11s4.5-2.019 4.5-4.5S14.481 2 12 2S7.5 4.019 7.5 6.5zM20 21h1v-1c0-3.859-3.141-7-7-7h-4c-3.86 0-7 3.141-7 7v1h17z"
                        fill="currentColor"
                      />
                    </svg>
                    <!-- {{ $page.props.user.name }} -->

                    <svg
                      class="ml-2 -mr-0.5 h-4 w-4"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                      />
                    </svg>
                  </div>
                </span>
              </template>

              <template #content>
                <!-- Account Management -->
                <div class="block px-4 py-2 text-xs text-gray-400">
                  Manage Account
                </div>

                <jet-dropdown-link :href="route('profile.show')">
                  Profile
                </jet-dropdown-link>

                <jet-dropdown-link
                  :href="route('api-tokens.index')"
                  v-if="$page.props.jetstream.hasApiFeatures"
                >
                  API Tokens
                </jet-dropdown-link>

                <div class="border-t border-gray-100"></div>

                <!-- Authentication -->
                <form @submit.prevent="logout">
                  <jet-dropdown-link as="button"> Log Out </jet-dropdown-link>
                </form>
              </template>
            </jet-dropdown>
          </div>
        </div>
      </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div class="hidden sm:hidden absolute">
      <div class="pt-2 pb-3 space-y-1">
        <jet-responsive-nav-link
          :href="route('dashboard')"
          :active="route().current('dashboard')"
        >
          Dashboard
        </jet-responsive-nav-link>
      </div>

      <!-- Responsive Settings Options -->
      <div class="pt-4 pb-1 border-t border-gray-200">
        <div class="flex items-center px-4">
          <div
            v-if="$page.props.jetstream.managesProfilePhotos"
            class="flex-shrink-0 mr-3"
          >
            <img
              class="h-10 w-10 rounded-full object-cover"
              :src="$page.props.user.profile_photo_url"
              :alt="$page.props.user.name"
            />
          </div>

          <div>
            <div class="font-medium text-base text-gray-800">
              {{ $page.props.user.name }}
            </div>
            <div class="font-medium text-sm text-gray-500">
              {{ $page.props.user.email }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";
import { useStore } from "vuex";
import { computed, ref } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import JetApplicationMark from "@/Jetstream/ApplicationMark.vue";
import JetBanner from "@/Jetstream/Banner.vue";
import JetDropdown from "@/Jetstream/Dropdown.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
import JetNavLink from "@/Jetstream/NavLink.vue";
import JetResponsiveNavLink from "@/Jetstream/ResponsiveNavLink.vue";
import pavcoIconVue from '../assets/Icons/PavcoIcon/pavcoIcon.vue';
export default {
  components: {
    Head,
    JetApplicationMark,
    JetBanner,
    JetDropdown,
    JetDropdownLink,
    JetNavLink,
    JetResponsiveNavLink,
    Link,
     pavcoIcon:pavcoIconVue,
  },
  setup() {
    const store = useStore();
    const isFullScreen = computed(() => store.state.isFullScreen);
    const menuToggleMobile = () => store.dispatch("asideMobileToggle");
    const showingNavigationDropdown = ref(false);
    const menuOpenLg = () => store.dispatch("asideLgToggle", true);
    const switchToTeam = (team) => {
      Inertia.put(
        route("current-team.update"),
        {
          team_id: team.id,
        },
        {
          preserveState: false,
        }
      );
    };
    const isAsideMobileExpanded = computed(
      () => store.state.isAsideMobileExpanded
    );
    const logout = () => {
      Inertia.post(route("logout"));
    };
    const isNavBarVisible = computed(() => !store.state.isFullScreen);
    return {
      showingNavigationDropdown,
      isFullScreen,
      menuToggleMobile,
      menuOpenLg,
      isAsideMobileExpanded,
      logout,
      switchToTeam,
      isNavBarVisible,
    };
  },
};
</script>

<style>
</style>