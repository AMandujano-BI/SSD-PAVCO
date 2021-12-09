<template>
  <app-layout title="Companies">

  <div class="p-5 mx-auto container">
    <h1>Company</h1>
    <button @click="openModal">+</button>
    <modal :show="openModalCompany" @close="closeModal">
        <div class="p-5">
        <form-company :countries="countries"/>
        </div>
    </modal>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import FormCompany from './components/FormCompany.vue';
import ModalVue from "@/Jetstream/Modal.vue";
import { ref } from 'vue';
export default {
  props: ["countries"],
  components: {
    AppLayout: AppLayout,
    FormCompany,
    Modal:ModalVue
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
