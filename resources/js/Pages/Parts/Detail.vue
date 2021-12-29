<template>
  <app-layout title="Parts Detail">
    <div class="pt-12 mx-auto container">

      <div class=" p-6  mb-6 border-[1px] border-[#979797] bg-[#f8fafc]">
        <form-update-run-part :plateMethods="plateMethods" :run="run"  :customers="customers"/>
      </div>
      
      <div class=" p-6  mb-6 border-[1px] border-[#979797] bg-[#f8fafc]">
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
    
      <div class=" p-6  mb-2 border-[1px] border-[#979797] bg-[#f8fafc]">
        <table-photos :photos="photos" :run="run" />
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
    "customers",
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

