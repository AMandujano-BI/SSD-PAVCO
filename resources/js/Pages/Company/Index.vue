<template>
  <app-layout title="Companies">

  <div class="p-5 mx-auto container">
   <h1 class="text-center text-2xl p-5 font-bold">List Company</h1>
    <button @click="openModal"
    class="bg-primary hover:bg-primary-600 rounded w-[100] py-1 text-white px-3 mt-2"
     >+</button>
    <modal :show="openModalCompany">
        <div class="p-5">
        <form-company :countries="countries" :distributors="distributors" @closeModal="closeModal"/>
        </div>
    </modal>


    <table-company/>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import FormCompany from './components/FormCompany.vue';
import ModalVue from "@/Jetstream/Modal.vue";
import { ref } from 'vue';
import TableCompanyVue from './components/TableCompany.vue';
export default {
  props: ["countries","distributors"],
  components: {
    AppLayout: AppLayout,
    FormCompany,
    Modal:ModalVue,
    TableCompany :TableCompanyVue,
  },
  setup(props) {
    const openModalCompany =ref(false)
    const openModal=()=>{
        openModalCompany.value = true
    }

    const closeModal=()=>{
        openModalCompany.value = false
    }
    return{
        openModalCompany,
        openModal,
        closeModal
    }
  },
};
</script>
