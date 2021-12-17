<template>
  <app-layout title="Companies">
    <div class="p-5 mx-auto container">
      <h1 class="text-center text-2xl p-5 font-bold">List Users</h1>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";

import ModalVue from "@/Jetstream/Modal.vue";
export default {
  components: {
    AppLayout: AppLayout,
    Modal: ModalVue,
  },
};
</script>
<style>
</style>