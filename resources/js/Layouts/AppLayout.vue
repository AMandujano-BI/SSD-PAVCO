<template>
  <div>
    <Head :title="title" />

    <jet-banner />

    <div class="min-h-screen bg-gray-100" id="app">
      <navbar-vue />
      <!-- Page Heading -->
      <header class="bg-white shadow" v-if="$slots.header">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <slot name="header"></slot>
        </div>
      </header>
      <left-bar :menu="menu"></left-bar>
      <overlay
        v-show="isAsideLgActive"
        z-index="z-30"
        @overlay-click="overlayClick"
      />
      <!-- Page Content -->
      <main
        class="px-0 md:px-6 lg:pl-24"
        :class="[
          isFullScreen ? 'flex h-screen items-center justify-center' : 'py-6',
        ]"
      >
        <slot></slot>
      </main>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from "vue";
import JetApplicationMark from "@/Jetstream/ApplicationMark.vue";
import JetBanner from "@/Jetstream/Banner.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import LeftBar from "@/Jetstream/LeftBar.vue";
import Overlay from "@/Jetstream/Overlay.vue";
import { computed } from "vue";
import { useStore } from "vuex";
import NavbarVue from "../Jetstream/Navbar.vue";
import menu from "@/menu.js";
export default defineComponent({
  props: {
    title: String,
  },

  components: {
    NavbarVue,
    Head,
    JetApplicationMark,
    JetBanner,
    Link,
    LeftBar,
    Overlay
  },
  setup() {
    const store = useStore();
    const isFullScreen = computed(() => store.state.isFullScreen);
    const menuToggleMobile = () => store.dispatch("asideMobileToggle");
    const showingNavigationDropdown = ref(false);
    const menuOpenLg = () => store.dispatch("asideLgToggle", true);
    const isAsideLgActive = computed(() => store.state.isAsideLgActive);

    const overlayClick = () => {
      store.dispatch("asideLgToggle", false);
    };
    return {
      showingNavigationDropdown,
      isFullScreen,
      menuToggleMobile,
      menuOpenLg,
      isAsideLgActive,
      overlayClick,
      menu
    };
  },
});
</script>
