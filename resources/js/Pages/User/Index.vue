<template>
  <app-layout title="Users">
    <div class="pt-14 mx-auto container">
        <h1 class="text-center text-2xl p-5 font-bold text-[#3b4559]">List Users</h1>
  
   
      <table-user  :companies="companies" :rols="rols"/>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import TableUserVue from "./components/TableUser.vue";

export default {
  props:['companies','rols'],
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