<template>
  <div class="container mx-auto">
    <h1 class="text-center font-bold p-5 text-2xl">List of Runs</h1>
    <button
      @click="openModalButton"
      class="bg-blue-600 px-4 py-2 text-white rounded-md mb-2"
    >
      +
    </button>
    <modal :show="openModal" @close="closeModal">
      <div class="p-5">
        <form-run
          :plateMethods="plateMethods"
          :topCoats="topCoats"
          :chromates="chromates"
          :plates="plates"
          :secondaryCoats="secondaryCoats"
        />
      </div>
    </modal>

    <table-run />
    
  </div>
</template>


<script>
import ModalVue from "../../Jetstream/Modal.vue";
import FormRun from "./components/FormRun.vue";
import TableRun from "./components/TableRun.vue";
import axios from "axios";

export default {
  components: {
    modal: ModalVue,
    formRun: FormRun,
    TableRun: TableRun
  },
  props: ["plateMethods", "topCoats", "chromates", "plates", "secondaryCoats"],
  data() {
    return {
      openModal: false,
    };
  },
  methods: {
    openModalButton() {
      this.openModal = true;
    },
    closeModal() {
      this.openModal = false;
    },
    async getAllRuns(){
      await axios.get('/run/getAllRuns')
        .then((resp) => {
          console.log(resp)
        })
        .catch((error) => {
          console.log({error});
        })
    }
  },
  async mounted() {
    this.getAllRuns()
  }
};
</script>