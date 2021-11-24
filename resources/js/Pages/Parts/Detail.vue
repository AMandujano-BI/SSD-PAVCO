<template>
  <div class="p-5 mx-auto container">
    <h1 class="text-center text-2xl font-bold">Detalis</h1>

    <div class="shadow-lg p-4  rounded-md  mb-5">
      <form-update-run-part :plateMethods="plateMethods" :run="run" />
    </div>
    <div class="shadow-lg mb-5 rounded min-h-[300] p-5">
      <table-part :parts="parts"  :openModal="openModal"/>
    </div>
    <div class="shadow-lg rounded-md p-4 mb-5">
      <table-photos />
    </div>

  
  
  </div>
</template>

<script>
import FormUpdateRunPartVue from './components/FormUpdateRunPart.vue';
import TablePart from "./components/TablePart.vue";
import TablePhotosVue from "./components/TablePhotos.vue";
export default {
  props: ["parts", "query", "plateMethods","run"],
  components: {
    tablePart: TablePart,
    formUpdateRunPart: FormUpdateRunPartVue,
    tablePhotos: TablePhotosVue,
  },
  setup() {
    return {
    };
  },
};
</script>

