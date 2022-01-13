<template>
  <h1 class="text-center text-2xl p-5 font-bold text-[#3b4559]">Table Part</h1>
  <div class="flex gap-5">
    <button @click="openModalPartClick">
      <icon-plus />
    </button>
    <div
      class="relative text-gray-600 focus-within:text-gray-400 flex-1 w-full"
    >
      <span class="absolute inset-y-0 left-0 flex items-center pl-2">
        <button
          type="submit"
          class="p-1 focus:outline-none focus:shadow-outline"
        >
          <svg
            fill="none"
            stroke="#a2a2a2"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            viewBox="0 0 24 24"
            class="w-6 h-6"
          >
            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </button>
      </span>

      <input
        type="text"
        class="
          py-[14px]
          text-sm
          w-full
          pl-10
          rounded-sm
          border-[#a2a2a2]
          placeholder-[#a2a2a2]
          text-[#333]
        "
        id="filterPartInputBot"
        placeholder="Search Part..."
        autocomplete="off"
      />
    </div>
  </div>
  <div class="rounded-lg bg-white p-5 mt-2">
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
  </div>
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
        @gettinDataParts="gettinDataParts"
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
        :partsTable="partsTable"
        :chromates="chromates"
        :plateTypes="plateTypes"
        :secondaryCoats="secondaryCoats"
        :topCoats="topCoats"
        @closeModalNewPart="closeModalNewPart"
        @generateDataTable="generateDataTable"
        @gettinDataParts="gettinDataParts"
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
import IconPlus from "@/assets/Icons/iconPlus.vue";
export default {
  props: [
    // "parts",
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
    IconPlus,
  },
  setup(props) {
    const { run } = props;
    const openModal = ref(false);
    const partsTable = ref([]);
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
    const gettinDataParts = async () => {
      const res = await axios.get(`/part/getPartsByRun/${run.id}`);
      partsTable.value = res.data;
      generateDataTable();
    };
    gettinDataParts();
    $(document).ready(function () {
      $("#filterPartInputBot")
        .off()
        .keyup(function () {
          $("#partsTable").DataTable().search(this.value).draw();
        });
    });
    const generateDataTable = () => {
      $("#partsTable").DataTable().destroy();
      nextTick(() => {
        $("#partsTable").DataTable({
          scrollY: 300,
          ordering: true,
          bLengthChange: false,
          pageLength: 10,
          language: {
            paginate: {
              next: `<svg class="arrow_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="15" height="14" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><g transform="rotate(270 10 10)"><path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="white"/></g></svg>`, // or '→'
              previous: `<svg class="arrow_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="15" height="14" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><g transform="rotate(90 10 10)"><path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="white"/></g></svg>`, // or '←'
            },
            info: "Showing results _START_ to _END_ from _TOTAL_",
          },
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
      gettinDataParts,
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
