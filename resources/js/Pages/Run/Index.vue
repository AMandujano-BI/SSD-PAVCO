<template>
  <app-layout title="Runs">
    <!-- <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Runs</h2>
    </template> -->
    <div class="container mx-auto pt-5">
      <h1 class="text-center font-bold p-5 text-2xl">List of Runs</h1>
      <div class="pl-9">
        <button
          @click="openModalButton"
          class="
            bg-primary
            hover:bg-primary-600
            px-4
            py-2
            text-white
            rounded-md
            mb-2
          "
        >
          +
        </button>
      </div>
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

      <table-run />
    </div>
  </app-layout>
</template>


<script>
import ModalVue from "../../Jetstream/Modal.vue";
import FormRun from "./components/FormRun.vue";
import TableRun from "./components/TableRun.vue";
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";

export default {
  components: {
    modal: ModalVue,
    formRun: FormRun,
    TableRun: TableRun,
    AppLayout: AppLayout,
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