<template>
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
        <th class="no-sort">Notes</th>
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
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
              />
            </svg>
          </button>
        </td>
        <td class="text-center">
          <button @click="openModalDelete(part.id)">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="#E71F2A"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
              />
            </svg>
          </button>
        </td>
        <td>
          <button @click="openModalNotesClick(part.notes)">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path
                fill-rule="evenodd"
                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                clip-rule="evenodd"
              />
            </svg>
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
    <div class="p-5">modal notes</div>
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
</template>

<script>
const $ = require("jquery");
import dt from "datatables.net";
import ModalVue from "@/Jetstream/Modal.vue";
import { reactive, ref, nextTick } from "vue";
import ConfirmationModal from "@/Jetstream/ConfirmationModal.vue";
import FormUpdatePartVue from "./FormUpdatePart.vue";
import axios from "axios";
import useHelper from "@/composables/useHelper";
export default {
  props: ["parts", "topCoats", "chromates", "plateTypes", "secondaryCoats"],
  components: {
    modal: ModalVue,
    formUpdatePart: FormUpdatePartVue,
    confirmationModal: ConfirmationModal,
  },
  setup(props) {
    let { parts } = props;
    const openModal = ref(false);
    const partsTable = ref(parts);
    const openModalNotes = ref(false);
    const { makeToast } = useHelper();
    const idPart = ref(0);
    const showModalDelete = ref(false);
    const partUpdate = ref({
      id: 0,
      plateThick: 0,
      primaryPer: 0,
    });
    const generateDataTable = () => {
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
    const openModalNotesClick = (notes) => {
      openModalNotes.value = true;
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
    };
  },
};
</script>

<style scoped>
.no-sort::after { display: none!important; }
.no-sort { pointer-events: none!important; cursor: default!important; background-image: none !important }
</style>
