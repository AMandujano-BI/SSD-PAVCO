<template>
  <h1 class="text-center text-2xl p-5 font-bold text-[#3b4559]">Parts Editor</h1>

  <table-part />
  <table-photos />

  <modal :show="openModal" @close="closeModal">
    <div class="p-5">
      <form-update-part />
    </div>
  </modal>
  <h1 class="text-center text-2xl p-5 font-bold text-[#3b4559]">List of Runs</h1>
  <button
    @click="openModalButton"
    class="bg-blue-600 px-4 py-1 text-white rounded-md mb-2"
  >
    +
  </button>
</template>

<script>
import { ref } from "vue";
import ModalVue from "../../Jetstream/Modal.vue";
import FormUpdatePartVue from "./components/FormUpdatePart.vue";
import TablePart from "./components/TablePart.vue";
import TablePhotosVue from "./components/TablePhotos.vue";

export default {
  components: {
    tablePart: TablePart,
    formUpdatePart: FormUpdatePartVue,
    tablePhotos: TablePhotosVue,
    modal: ModalVue,
  },
  setup() {
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
