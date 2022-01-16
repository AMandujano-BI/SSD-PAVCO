<template>
  <div class="h-[10vh] md:min-h[15vh] flex items-center justify-center">
    <div
      class="
        bg-[#0271c5]
        h-full
        flex
        items-center
        justify-center
        w-full
        flex-1
        md:w-[235px] md:flex-none
      "
    >
      <pavco-icon />
    </div>
    <h1
      class="
        text-center text-[#3b4559] text-[20px]
        font-semibold
        flex-1
        hidden
        md:block
      "
    >
      Salt Spray Database
    </h1>
  </div>
</template>

<script>
import pavcoIconVue from "../assets/Icons/PavcoIcon/pavcoIcon.vue";
export default {
  components: {
    pavcoIcon: pavcoIconVue,
  },
};
</script>

<style>
</style>