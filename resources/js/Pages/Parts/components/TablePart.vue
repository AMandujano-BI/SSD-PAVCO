<template>
  <h1 class="text-center text-2xl p-5 font-bold text-[#3b4559]">Table Part</h1>
  <button
    class="
      bg-primary
      hover:bg-primary-600
      rounded
      w-[100]
      py-1
      text-white
      px-3
      mt-2
    "
    @click="openModalPartClick"
  >
    +
  </button>
  <table id="partsTable" class="display" style="width: 100%">
    <thead>
      <tr>
        <th>Part Description</th>
        <th>Plate Type</th>
        <th>Chromate</th>
        <th>Topcoat</th>
        <th>Secondary TopCoat</th>
        <th class="no-sort">Edit</th>
        <th class="no-sort">Delete</th>
        <!-- <th class="no-sort">Notes</th> -->
      </tr>
    </thead>
    <tbody>
      <tr v-for="part in partsTable" :key="part.id">
        <td>{{ part.description }}</td>
        <td>{{ part.plate_type.name }}</td>
        <td>
          {{
            part.chromate.name +
            " / " +
            part.primaryPer +
            " / " +
            part.primaryTemp +
            " / " +
            part.primaryPH +
            " / " +
            part.primaryDiptime
          }}
        </td>
        <td>
          {{
            part.top_coat.name +
            " / " +
            part.topCoatPer +
            " / " +
            part.topCoatTemp +
            " / " +
            part.topCoatPH +
            " / " +
            part.topCoatDiptime
          }}
        </td>
        <td>
          {{
            part.coat.name +
            " / " +
            part.coatPer +
            " / " +
            part.coatTemp +
            " / " +
            part.coatPH +
            " / " +
            part.coatDiptime
          }}
        </td>
        <td class="text-center">
          <button @click="editPart(part)">
            <icon-edit />
          </button>
        </td>
        <td class="text-center">
          <button @click="openModalDelete(part.id)">
            <icon-delete />
          </button>
        </td>
      </tr>
    </tbody>
  </table>

  <!-- MODALS -->
  <modal :show="openModal">
    <div class="p-5">
      <form-update-part
        :partUpdate="partUpdate"
        :chromates="chromates"
        :secondaryCoats="secondaryCoats"
        :topCoats="topCoats"
        :plateTypes="plateTypes"
        @closeModal="closeModal"
        @generateDataTable="generateDataTable"
        :partsTable="partsTable"
      />
    </div>
  </modal>
  <modal :show="openModalNotes">
    <div class="container mx-auto p-5 relative">
      <button
        @click="closeModalNotes"
        class="absolute right-10 hover:text-red-400"
      >
        X
      </button>
      <div class="p-5">modal notes</div>
    </div>
  </modal>
  <confirmation-modal :show="showModalDelete">
    <template v-slot:title>
      <h1>Are you sure that delete this part?</h1>
    </template>
    <template v-slot:content>
      <div class="flex justify-center">
        <button
          class="bg-red-500 p-4 text-white rounded-md mr-4"
          @click="closeModalDelete"
        >
          Cancel
        </button>
        <button
          class="bg-green-500 p-4 text-white rounded-md"
          @click="deletePart()"
        >
          Acept
        </button>
      </div>
    </template>
  </confirmation-modal>

  <modal :show="openModalPartCreate">
    <div class="p-5">
      <form-create-part
        :run_id="run.id"
        :partsTable="parts"
        :openModal="openModal"
        :chromates="chromates"
        :plateTypes="plateTypes"
        :secondaryCoats="secondaryCoats"
        :topCoats="topCoats"
        @closeModalNewPart="closeModalNewPart"
        @generateDataTable="generateDataTable"
      />
    </div>
  </modal>
</template>

<script>
const $ = require("jquery");
import dt from "datatables.net";
import ModalVue from "@/Jetstream/Modal.vue";
import { ref, nextTick } from "vue";
import ConfirmationModal from "@/Jetstream/ConfirmationModal.vue";
import FormUpdatePartVue from "./FormUpdatePart.vue";
import axios from "axios";
import useHelper from "@/composables/useHelper";
import FormCreatePartVue from "./FormCreatePart.vue";
import IconEdit from "@/assets/Icons/iconEdit.vue";
import IconDelete from "@/assets/Icons/iconDelete.vue";
export default {
  props: [
    "parts",
    "topCoats",
    "chromates",
    "plateTypes",
    "secondaryCoats",
    "run",
  ],
  components: {
    modal: ModalVue,
    formUpdatePart: FormUpdatePartVue,
    confirmationModal: ConfirmationModal,
    formCreatePart: FormCreatePartVue,
    IconEdit,
    IconDelete,
  },
  setup(props) {
    let { parts } = props;
    const openModal = ref(false);
    const partsTable = ref(parts);
    const openModalNotes = ref(false);
    const openModalPartCreate = ref(false);
    const { makeToast } = useHelper();
    const idPart = ref(0);
    const showModalDelete = ref(false);
    const partUpdate = ref({
      id: 0,
      plateThick: 0,
      primaryPer: 0,
    });
    const generateDataTable = () => {
      $("#partsTable").DataTable().destroy();
      nextTick(() => {
        $("#partsTable").DataTable({
          scrollY: 300,
          ordering: true,
          bLengthChange: false,
          bInfo: false,
          pageLength: 5,
          // paging: false,
        });
      });
    };

    const editPart = async (part) => {
      partUpdate.value = part;
      openModal.value = true;
    };
    const closeModal = () => {
      openModal.value = false;
    };

    const openModalDelete = (id) => {
      showModalDelete.value = true;
      idPart.value = id;
    };

    const openModalPartClick = () => {
      openModalPartCreate.value = true;
    };
    const closeModalNewPart = () => {
      openModalPartCreate.value = false;
    };
    const openModalNotesClick = (notes) => {
      openModalNotes.value = true;
    };
    const closeModalNotes = () => {
      openModalNotes.value = false;
    };
    const closeModalDelete = () => (showModalDelete.value = false);
    const deletePart = async () => {
      showModalDelete.value = false;
      let res;
      const id = idPart.value;
      res = await axios.delete(`/part/${id}`);
      const { ok, value, message } = res.data;
      if (ok) {
        partsTable.value = partsTable.value.filter((item) => item.id != id);
        makeToast(message);
        generateDataTable();
      } else {
        makeToast(message, "error");
      }
    };

    generateDataTable();
    return {
      generateDataTable,
      $,
      dt,
      editPart,
      openModal,
      closeModal,
      partUpdate,
      showModalDelete,
      closeModalDelete,
      openModalDelete,
      deletePart,
      openModalNotes,
      openModalNotesClick,
      partsTable,
      closeModalNotes,
      openModalPartCreate,
      openModalPartClick,
      closeModalNewPart,
    };
  },
};
</script>

<style scoped>
.no-sort::after {
  display: none !important;
}
.no-sort {
  pointer-events: none !important;
  cursor: default !important;
  background-image: none !important;
}
</style>
