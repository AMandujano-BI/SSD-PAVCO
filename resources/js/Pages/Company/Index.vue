<template>
  <app-layout title="Companies">
    <div class="p-5 mx-auto container">
      <h1 class="text-center text-2xl p-5 font-bold">List Company</h1>

{{
$page.props.user
}}
      <table-company  :countries="countries" :distributors="distributors"/>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import TableCompanyVue from "./components/TableCompany.vue";
export default {
  props: ["countries", "distributors"],
  components: {
    AppLayout: AppLayout,
    TableCompany: TableCompanyVue,
  },
  setup() {

    return {
 
    };
  },
};
</script>
