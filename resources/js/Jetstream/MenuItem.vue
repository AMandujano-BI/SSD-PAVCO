<script setup>
import { ref, computed } from "vue";
// import { mdiMinus, mdiPlus } from '@mdi/js'
// import Icon from '@/components/Icon.vue'
import MenuList from "./MenuList.vue";
import JetNavLink from "@/Jetstream/NavLink.vue";
const props = defineProps({
  item: {
    type: Object,
    required: true,
  },
  isSubmenuList: Boolean,
});

const emit = defineEmits(["menu-click"]);

const isDropdownActive = ref(false);

const componentIs = computed(() => (props.item.href ? "a" : "div"));

const hasDropdown = computed(() => !!props.item.menu);

const dropdownIcon = computed(() => (isDropdownActive.value ? "" : ""));

const itemTo = computed(() => props.item.to || null);

const itemHref = computed(() => props.item.href || null);
const itemRoute= computed(() => props.item.itemRoute|| null);

const itemTarget = computed(() =>
  componentIs.value === "a" && props.item.target ? props.item.target : null
);

const menuClick = (event) => {
  console.log(event)
  if (hasDropdown.value) {
    isDropdownActive.value = !isDropdownActive.value;
    if(event ==undefined) return
    emit("menu-click", event, props.item);
    //  store.dispatch("asideLgToggle", false);
  }else{
    emit("menu-click", event, props.item);

  }
};

const styleActive = "font-bold text-white text-primary-600";

const styleInactive = "text-gray-500";
</script>

<template>
  <li>
    <div
      v-if="!itemHref"
      class="
        flex
        cursor-pointer
        text-gray-700
        hover:bg-[#e1e8f3]
        dark:hover:bg-gray-700 dark:hover:bg-opacity-50
      "
      :class="[isSubmenuList ? 'p-3 text-sm' : 'py-2']"
      @click="menuClick()"
    >
      <img
        v-if="item.icon"
        :src="item.icon"
        class="flex-none pr-3 pl-14"
        w="w-12"
      />
      <span
        class="flex-grow"
        >{{ item.label }}</span
      >
      <div v-if="isDropdownActive" class="text-primary pr-5">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink"
          aria-hidden="true"
          role="img"
          width="24"
          height="24"
          preserveAspectRatio="xMidYMid meet"
          viewBox="0 0 20 20"
        >
          <path d="M15 14l-5-5l-5 5l-2-1l7-7l7 7z" fill="currentColor" />
        </svg>
      </div>
      <div v-if="!isDropdownActive" class="text-primary pr-5">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink"
          aria-hidden="true"
          role="img"
          width="24"
          height="24"
          preserveAspectRatio="xMidYMid meet"
          viewBox="0 0 20 20"
        >
          <path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="currentColor" />
        </svg>
      </div>
    </div>
    <jet-nav-link
      :is="componentIs"
      v-if="itemHref"
      :href="route(itemRoute)"
      class="
        flex
        cursor-pointer
        hover:bg-[#e1e8f3]
        w-full
        text-gray-700
        dark:hover:bg-gray-700 dark:hover:bg-opacity-50
      "
      :active="route().current(itemRoute)"
      v-slot="vSlot"
      :class="[isSubmenuList ? 'p-3 text-sm' : 'py-2']"
      @click="menuClick"
    >
      <img
        v-if="item.icon"
        :src="item.icon"
        class="flex-none pr-3 pl-14"
        :class="[vSlot && vSlot.isExactActive ? styleActive : styleInactive]"
        w="w-12"
      />
      <span
        class="flex-grow "
        >{{ item.label }}</span
      >
      <!-- <icon
        v-if="hasDropdown"
        :path="dropdownIcon"
        class="flex-none"
        :class="[vSlot && vSlot.isExactActive ? styleActive : styleInactive]"
        w="w-12"
      /> -->
    </jet-nav-link>
    <!-- </component> -->
    <menu-list
      v-if="hasDropdown"
      :menu="item.menu"
       @menu-click="menuClick"
      :class="{
        hidden: !isDropdownActive,
        'block bg-[#edf4ff]  dark:bg-gray-800 dark:bg-opacity-50':
          isDropdownActive,
      }"
      is-submenu-list
    />
  </li>
</template>
