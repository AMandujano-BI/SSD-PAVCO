<template>
  <div class="p-5 mx-auto container">
    <h1 class="text-center text-2xl font-bold">Detalis</h1>

    <div class="shadow-lg p-4  rounded-md  mb-5">
      <form-update-run-part :plateMethods="plateMethods" :run="run" />
    </div>
    <div class="shadow-sm rounded min-h-[300]">
      <table-part :parts="parts" />
    </div>
    <div class="shadow-lg rounded-md p-4 mb-5">
      <table-photos />
    </div>

    <modal :show="openModal" @close="closeModal">
      <div class="p-5">
        <form-update-part />
      </div>
    </modal>
    <button
      @click="openModalButton"
      class="bg-blue-600 px-4 py-2 text-white rounded-md mb-2"
    >
      +
    </button>
  </div>
</template>

<script>
import { ref } from "vue";
import ModalVue from "../../Jetstream/Modal.vue";
import FormUpdatePartVue from "./components/FormUpdatePart.vue";
import TablePart from "./components/TablePart.vue";
import TablePhotosVue from "./components/TablePhotos.vue";
import FormUpdateRunPartVue from "./components/FormUpdateRunPart.vue";
export default {
  props: ["parts", "query", "plateMethods","run"],
  components: {
    tablePart: TablePart,
    formUpdatePart: FormUpdatePartVue,
    tablePhotos: TablePhotosVue,
    modal: ModalVue,
    formUpdateRunPart: FormUpdateRunPartVue,
  },
  setup(props) {
    console.log(props);
    let openModal = ref(false);
    const openModalButton = () => {
      openModal.value = true;
    };
    const closeModal = () => {
      openModal.value = false;
    };

    return {
      openModal,
      openModalButton,
      closeModal,
    };
  },
};
</script>

