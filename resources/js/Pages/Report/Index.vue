<template>
  <app-layout title="Report">
    <div class="pt-5 mx-auto container">
     <h1 class="text-center text-2xl p-5 font-bold">Report Wizard</h1>
      
      <form-generate-report ></form-generate-report>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import FormGenerateReport from './components/FormGenerateReport.vue';

export default {
  components: {
    AppLayout: AppLayout,
    formGenerateReport: FormGenerateReport
  },
  setup() {},
};
</script>

<style>
</style>