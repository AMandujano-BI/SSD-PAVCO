<template>

  <app-layout title="Parts Detail">
  <div class="p-5 mx-auto container">
    <h1 class="text-center text-2xl font-bold">Detalis</h1>

    <div class="shadow-lg p-4 rounded-md mb-5">
      <form-update-run-part :plateMethods="plateMethods" :run="run" />
    </div>
    <div class="shadow-lg mb-5 rounded min-h-[300] p-5">
      <table-part
        :parts="parts"
        :run="run"
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
  </app-layout>
</template>

<script>
import { ref } from "vue";
import FormUpdateRunPartVue from "./components/FormUpdateRunPart.vue";
import TablePart from "./components/TablePart.vue";
import TablePhotosVue from "./components/TablePhotos.vue";
import ModalVue from "../../Jetstream/Modal.vue";
import FormCreatePartVue from "./components/FormCreatePart.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
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
    formCreatePart: FormCreatePartVue,
    AppLayout: AppLayout,
  },
  setup(props) {
    const { run } = props;
    const photos = ref(run.photos);

    return { photos };
  },
};
</script>

