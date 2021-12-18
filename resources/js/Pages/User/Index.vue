<template>
  <app-layout title="Users">
    <div class="p-5 mx-auto container">
      <h1 class="text-center text-2xl p-5 font-bold">List Users</h1>
  
   
      <table-user />
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import TableUserVue from "./components/TableUser.vue";

export default {
  components: {
    AppLayout: AppLayout,
    TableUser: TableUserVue,

  },
  setup() {

  },
};
</script>
<style>
</style>