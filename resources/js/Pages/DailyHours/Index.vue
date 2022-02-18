<template>
  <app-layout title="Daily Hours">
    <div class="pt-14 mx-auto container">
      <h1 class="text-center text-2xl p-5 font-bold text-[#3b4559]">
        Daily Hours
      </h1>
      <table-daily-hours></table-daily-hours>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import TableDailyHoursVue from './components/TableDailyHours.vue';
export default {
  components: {
    AppLayout: AppLayout,
    TableDailyHours:TableDailyHoursVue
  },
  setup() {},
};
</script>

<style>
</style>