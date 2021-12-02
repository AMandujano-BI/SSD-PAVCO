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
      "
    >
      <button class="bg-white text-gray-900 rounded-md p-1" @click="hideMenu">hide</button>
    </div>
  </aside>
</template>

<script>
import { ref } from "vue";
export default {
  setup() {
    const isAsideMobileExpanded = ref(true);
    const isNavBarVisible = ref(true);
    const hideMenu = () => {
      console.log("hide");
      isAsideMobileExpanded.value = false;
      document
        .getElementById("app")
        .classList[isAsideMobileExpanded.value ? "add" : "remove"](
          "ml-60",
          "lg:ml-0"
        );

      document.documentElement.classList[
        isAsideMobileExpanded.value ? "add" : "remove"
      ]("m-clipped");
    };
    return {
      isAsideMobileExpanded,
      hideMenu,
      isNavBarVisible,
    };
  },
};
</script>
