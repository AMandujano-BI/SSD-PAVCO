<template>
  <div class="p-5 mx-auto container">
    <h1 class="text-center text-2xl font-bold">Detalis</h1>

    <div class="shadow-lg p-4 rounded-md mb-5">
      <form-update-run-part :plateMethods="plateMethods" :run="run" />
    </div>
    <div class="shadow-lg mb-5 rounded min-h-[300] p-5">
      <button
        class="bg-blue-600 rounded w-[100] py-1 text-white px-3 mt-2"
        @click="openModalPartClick"
      >
        +
      </button>
      <table-part
        :parts="parts"
        :openModal="openModal"
        :chromates="chromates"
        :plateTypes="plateTypes"
        :secondaryCoats="secondaryCoats"
        :topCoats="topCoats"
      />
    </div>
    <div class="shadow-lg rounded-md p-4 mb-5">
      <button class="bg-blue-600 rounded w-[100] py-1 text-white px-3 mt-2">
        +
      </button>
      <table-photos :photos="photos" />
    </div>
  </div>
  <modal :show="openModalPart">
    <div class="p-5">modal parts</div>
  </modal>
</template>

<script>
import { ref } from "vue";
import FormUpdateRunPartVue from "./components/FormUpdateRunPart.vue";
import TablePart from "./components/TablePart.vue";
import TablePhotosVue from "./components/TablePhotos.vue";
import ModalVue from "../../Jetstream/Modal.vue";
export default {
  props: [
    "parts",
    "query",
    "plateMethods",
    "run",
    "topCoats",
    "chromates",
    "plateTypes",
    "secondaryCoats",
  ],
  components: {
    tablePart: TablePart,
    formUpdateRunPart: FormUpdateRunPartVue,
    tablePhotos: TablePhotosVue,
    modal: ModalVue,
  },
  setup(props) {
    const { run } = props;
    console.log(props);
    console.log(run);
    const photos = ref(run.photos);
    const openModalPart = ref(false);
    const openModalPartClick = () => {
      openModalPart.value = true;
    };
    return { photos, openModalPart, openModalPartClick };
  },
};
</script>

