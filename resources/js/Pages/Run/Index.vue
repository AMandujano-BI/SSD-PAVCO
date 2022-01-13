<template>
  <app-layout title="Runs">
    <div class="container mx-auto pt-14">
      <modal :show="openModal">
        <div class="p-5">
          <form-run
            :plateMethods="plateMethods"
            :topCoats="topCoats"
            :chromates="chromates"
            :plates="plates"
            :secondaryCoats="secondaryCoats"
            :plateTypes="plateTypes"
            :customers="customers"
            @closeModal="closeModal"
          />
        </div>
      </modal>

      <table-run  @openModalButton="openModalButton"/>
    </div>
  </app-layout>
</template>


<script>
import ModalVue from "../../Jetstream/Modal.vue";
import FormRun from "./components/FormRun.vue";
import TableRun from "./components/TableRun.vue";
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import IconPlusVue from "@/assets/Icons/iconPlus.vue";
export default {
  components: {
    modal: ModalVue,
    formRun: FormRun,
    TableRun: TableRun,
    AppLayout: AppLayout,
    IconPlus:IconPlusVue
  },
  props: [
    "plateMethods",
    "topCoats",
    "chromates",
    "plates",
    "secondaryCoats",
    "plateTypes",
    "customers",
  ],
  setup() {
    const openModal = ref(false);
    const openModalButton = () => (openModal.value = true);
    const closeModal = () => (openModal.value = false);

    return {
      openModal,
      openModalButton,
      closeModal,
    };
  },
};
</script>