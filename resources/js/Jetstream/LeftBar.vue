<template>
  <aside
    v-show="!isFullScreen"
    class="
      w-60
      fixed
      top-0
      z-40
      min-h-screen h-full

      bg-[#f4f7fc]
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
        bg-[#0271c5]
        text-white
        flex-1
        h-32
        items-center
        justify-center
      "
    >
      <button
        class="hidden bg-white text-gray-900 rounded-md p-1 xl:hidden lg:flex"
        @click="hideMenu"
      >
      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="24" height="24" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"><path d="M408 442h480c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8H408c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8zm-8 204c0 4.4 3.6 8 8 8h480c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8H408c-4.4 0-8 3.6-8 8v56zm504-486H120c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8h784c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8zm0 632H120c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8h784c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8zM142.4 642.1L298.7 519a8.84 8.84 0 0 0 0-13.9L142.4 381.9c-5.8-4.6-14.4-.5-14.4 6.9v246.3a8.9 8.9 0 0 0 14.4 7z" fill="currentColor"/></svg>
      </button>
      <img :src="IconPavco" alt="pavcoIcon" />
    </div>
    <div class="w-full pt-20">
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
      <img :src="IconFooterLeftBar" alt="iconLeftBar" class="w-full h-48 absolute bottom-0" />
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
