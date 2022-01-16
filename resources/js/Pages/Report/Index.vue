<template>
  <app-layout title="Report">
    <div class="pt-14 mx-auto container">
      <h1 class="text-center text-2xl p-5 font-bold text-[#3b4559]">Report Wizard</h1>
      <form-generate-report
        :customers="customers"
        :topCoats="topCoats"
        :chromates="chromates"
        :plateTypes="plateTypes"
        :secondaryCoats="secondaryCoats"
      ></form-generate-report>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import FormGenerateReport from "./components/FormGenerateReport.vue";

export default {
  props: ["customers", "topCoats", "chromates", "plateTypes", "secondaryCoats"],
  components: {
    AppLayout: AppLayout,
    formGenerateReport: FormGenerateReport,
  },
  setup(props) {
    const {customers} = props
    customers.unshift( { "value": 0, "label": "All Customers" } )

    
    

   
  },
};
</script>

<style>
</style>