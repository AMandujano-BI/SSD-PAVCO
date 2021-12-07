<template>
  <div>
    <Head :title="title" />

    <jet-banner />

    <div class="min-h-screen bg-gray-100" id="app">
      <nav class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <!-- Logo -->
              <div class="flex-shrink-0 flex items-center">
                <Link :href="route('dashboard')">
                  <h1>Salt Spray Database</h1>
                </Link>
              </div>

              <!-- Navigation Links -->
              <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                <div>
                  <button
                    class="flex bg-green-300 text-white lg:hidden"
                    @click.prevent="menuToggleMobile"
                  >
                    close
                  </button>
                  <button
                    class="hidden bg-green-300 text-white lg:flex xl:hidden"
                    @click.prevent="menuOpenLg"
                  >
                    close noee
                  </button>
                </div>
                <jet-nav-link
                  :href="route('dashboard')"
                  :active="route().current('dashboard')"
                >
                  Dashboard
                </jet-nav-link>
                <jet-nav-link
                  :href="route('run.index')"
                  :active="route().current('run.index')"
                >
                  Runs
                </jet-nav-link>
              </div>
              <div class="flex items-center justify-center px-3">
                <input
                  type="text"
                  placeholder="Search Items"
                  class="rounded-full"
                />
              </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
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
                          bg-white
                          hover:bg-gray-50 hover:text-gray-700
                          focus:outline-none focus:bg-gray-50
                          active:bg-gray-50
                          transition
                        "
                      >
                        hh
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
              <div class="ml-3 relative">
                <jet-dropdown align="right" width="48">
                  <template #trigger>
                    <button
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
                    </button>

                    <span v-else class="inline-flex rounded-md">
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
                          bg-white
                          hover:text-gray-700
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
                      </button>
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
                      <jet-dropdown-link as="button">
                        Log Out
                      </jet-dropdown-link>
                    </form>
                  </template>
                </jet-dropdown>
              </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
              <button
                @click="showingNavigationDropdown = !showingNavigationDropdown"
                class="
                  inline-flex
                  items-center
                  justify-center
                  p-2
                  rounded-md
                  text-gray-400
                  hover:text-gray-500 hover:bg-gray-100
                  focus:outline-none focus:bg-gray-100 focus:text-gray-500
                  transition
                "
              >
                <svg
                  class="h-6 w-6"
                  stroke="currentColor"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <path
                    :class="{
                      hidden: showingNavigationDropdown,
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

        <!-- Responsive Navigation Menu -->
        <div
          :class="{
            block: showingNavigationDropdown,
            hidden: !showingNavigationDropdown,
          }"
          class="sm:hidden"
        >
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

      <!-- Page Heading -->
      <header class="bg-white shadow" v-if="$slots.header">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <slot name="header"></slot>
        </div>
      </header>
      <left-bar></left-bar>

      <!-- Page Content -->
      <main
        :class="[
          isFullScreen ? 'flex h-screen items-center justify-center' : 'py-6',
        ]"
      >
        <!-- <transition
          enter-active-class="transition duration-100 ease-out"
          enter-from-class="transform scale-95 opacity-0"
          enter-to-class="transform scale-100 opacity-100"
          leave-active-class="animate-fadeOut"
        > -->
        <slot></slot>
        <!-- </transition> -->
      </main>
    </div>
  </div>
</template>

<script>
const isFullScreen = computed(() => store.state.isFullScreen);
import { defineComponent, ref } from "vue";
import JetApplicationMark from "@/Jetstream/ApplicationMark.vue";
import JetBanner from "@/Jetstream/Banner.vue";
import JetDropdown from "@/Jetstream/Dropdown.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
import JetNavLink from "@/Jetstream/NavLink.vue";
import JetResponsiveNavLink from "@/Jetstream/ResponsiveNavLink.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import LeftBar from "@/Jetstream/LeftBar.vue";
import { computed } from "vue";
import { useStore } from "vuex";
import { Inertia } from "@inertiajs/inertia";

export default defineComponent({
  props: {
    title: String,
  },

  components: {
    Head,
    JetApplicationMark,
    JetBanner,
    JetDropdown,
    JetDropdownLink,
    JetNavLink,
    JetResponsiveNavLink,
    Link,
    LeftBar,
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

    const logout = () => {
      Inertia.post(route("logout"));
    };

    return {
      logout,
      switchToTeam,
      showingNavigationDropdown,
      isFullScreen,
      menuToggleMobile,
      menuOpenLg,
    };
  },
});
</script>
