<template>
<div class="p-5">

  <form-run :plateMethods="plateMethods" :topCoats="topCoats" :chromates="chromates" :plates="plates" :secondaryCoats="secondaryCoats" />
</div>
</template>


<script>
import ModalVue from "../../Jetstream/Modal.vue";
import FormRun from "./components/FormRun.vue";
export default {
  components: {
    modal: ModalVue,
    formRun: FormRun,
  },
  props: ["plateMethods",'topCoats','chromates','plates','secondaryCoats'],
  data() {
    return {
      openModal: false,
    };
  },
  methods: {
    openModalButton() {
      this.openModal = true;
    },
  },
};
</script>