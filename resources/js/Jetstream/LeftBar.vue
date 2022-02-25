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
        h-[10vh]
        items-center
        justify-center
      "
    >
    <pavco-icon/>
    </div>
    <div class="w-full pt-20">
      <template v-for="(menuGroup, index) in menu">
        <p
          v-if="typeof menuGroup === 'string'"
          :key="`a-${index}`"
          class="p-3 text-xs uppercase text-gray-400"
        >
          <!-- {{ menuGroup }} -->
        </p>
        <menu-list
          v-else
          :key="`b-${index}`"
          :menu="menuGroup"
          @menu-click="hideMenu"
        />
      </template>
      <img :src="IconFooterLeftBar" alt="iconLeftBar" class="w-full h-48 absolute bottom-0" />
    </div>
  </aside>
</template>

<script>
import { computed } from "vue";
import { useStore } from "vuex";
import IconFooterLeftBar from "../assets/Icons/IconFooterLeftBar/pattern.png";
import MenuList from "./MenuList";
import pavcoIconVue from '../assets/Icons/PavcoIcon/pavcoIcon.vue';
export default {
  props: ['menu'],
  components: {
    MenuList,
    pavcoIcon:pavcoIconVue
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
      IconFooterLeftBar,
    };
  },
};
</script>
