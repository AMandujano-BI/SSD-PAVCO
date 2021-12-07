<template>
  <aside
    v-show="!isFullScreen"
    class="
      w-60
      fixed
      top-0
      z-40
      h-screen
      bg-gray-800
      transition-position
      lg:left-0
      scrollbar-thin
      scrollbar-thumb-rounded
      scrollbar-thumb-gray-600
      scrollbar-track-gray-900
      dark:border-r
      dark:border-gray-800
      dark:scrollbar-track-gray-800
      dark:bg-gray-900
      overflow-auto
    "
    :class="[
      isAsideMobileExpanded ? 'left-0' : '-left-60',
      isAsideLgActive ? 'block' : 'lg:hidden xl:block',
    ]"
  >
    <div
      class="
        flex flex-row
        w-full
        bg-gray-900
        text-white
        flex-1
        h-14
        items-center
        justify-center
      "
    >
      <button
        class="hidden bg-white text-gray-900 rounded-md p-1 xl:hidden lg:flex"
        @click="hideMenu"
      >
        hide
      </button>
      <img :src="IconPavco" alt="pavcoIcon" />
    </div>
    <div class="w-full">
      <img :src="IconFooterLeftBar" alt="iconLeftBar" class="w-48 h-48" />
      <template v-for="(menuGroup, index) in menu">
        <p
          v-if="typeof menuGroup === 'string'"
          :key="`a-${index}`"
          class="p-3 text-xs uppercase text-gray-400"
        >
          {{ menuGroup }}
        </p>
        <menu-list
          v-else
          :key="`b-${index}`"
          :menu="menuGroup"
          @menu-click="menuClick"
        />
      </template>
    </div>
  </aside>
</template>

<script>
import { computed } from "vue";
import { useStore } from "vuex";
import IconPavco from "../assets/Icons/PavcoIcon/pavcoIcon.png";
import IconFooterLeftBar from "../assets/Icons/IconFooterLeftBar/pattern.png";
import MenuList from "./MenuList";
export default {
  props: ['menu'],
  components: {
    MenuList,
  },
  setup() {
    const store = useStore();
    const isFullScreen = computed(() => store.state.isFullScreen);

    const isAsideMobileExpanded = computed(
      () => store.state.isAsideMobileExpanded
    );

    const isAsideLgActive = computed(() => store.state.isAsideLgActive);

    const hideMenu = () => {
      store.dispatch("asideLgToggle", false);
    };
    return {
      isAsideMobileExpanded,
      hideMenu,
      isFullScreen,
      isAsideLgActive,
      IconPavco,
      IconFooterLeftBar,
    };
  },
};
</script>
