<template>
  <div class="p-5">
    <h1 class="text-center text-2xl font-bold">Detalis</h1>

    <div class="shadow-sm rounded min-h-[300]">
      <table-part :parts="parts" />
    </div>
    <div class="shadow-sm rounded">
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
      Edit
    </button>
  </div>
</template>

<script>
import { ref } from "vue";
import ModalVue from "../../Jetstream/Modal.vue";
import FormUpdatePartVue from "./components/FormUpdatePart.vue";
import TablePart from "./components/TablePart.vue";
import TablePhotosVue from "./components/TablePhotos.vue";
export default {
  props: ["parts", "query"],
  components: {
    tablePart: TablePart,
    formUpdatePart: FormUpdatePartVue,
    tablePhotos: TablePhotosVue,
    modal: ModalVue,
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

