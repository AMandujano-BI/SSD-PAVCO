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

const itemTarget = computed(() =>
  componentIs.value === "a" && props.item.target ? props.item.target : null
);

const menuClick = (event) => {
  emit("menu-click", event, props.item);

  if (hasDropdown.value) {
    isDropdownActive.value = !isDropdownActive.value;
  }
};

const styleActive = "font-bold text-white";

const styleInactive = "text-primary";
</script>

<template>
  <li>

    <div
      v-if="!itemHref"
      class="
        flex
        cursor-pointer
        hover:bg-[#e1e8f3]
        dark:hover:bg-gray-700 dark:hover:bg-opacity-50
      "
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
        class="flex-grow"
        :class="[vSlot && vSlot.isExactActive ? styleActive : styleInactive]"
        >{{ item.label }}</span
      >
      <div v-if="isDropdownActive" class="text-primary pr-5">
      ▲
      </div>
      <div v-if="!isDropdownActive" class="text-primary pr-5">
        ▼
      </div>
    </div>
    <jet-nav-link
      :is="componentIs"
      v-if="itemHref"
      :href="itemHref"
      class="
        flex
        cursor-pointer
        hover:bg-[#e1e8f3]
        w-full
        dark:hover:bg-gray-700 dark:hover:bg-opacity-50
      "
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
        class="flex-grow"
        :class="[vSlot && vSlot.isExactActive ? styleActive : styleInactive]"
        >{{ item.label }}</span
      >
      <icon
        v-if="hasDropdown"
        :path="dropdownIcon"
        class="flex-none"
        :class="[vSlot && vSlot.isExactActive ? styleActive : styleInactive]"
        w="w-12"
      />
    </jet-nav-link>
    <!-- </component> -->
    <menu-list
      v-if="hasDropdown"
      :menu="item.menu"
      :class="{
        hidden: !isDropdownActive,
        'block bg-[#edf4ff]  dark:bg-gray-800 dark:bg-opacity-50':
          isDropdownActive,
      }"
      is-submenu-list
    />
  </li>
</template>
