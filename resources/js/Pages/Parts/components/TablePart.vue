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
        id="filterPartInputBot1"
        placeholder="Search Part..."
        autocomplete="off"
      />
    </div>
  </div>
  <div class="rounded-lg bg-white p-5 mt-2">
    <table id="partsTable1" class="display" style="width: 100%"   data-ordering="false">
      <thead>
        <tr>
          <th  >Part Description</th>
          <th  >Plate Type</th>
          <th >Chromate</th>
          <th >Topcoat</th>
          <th >Secondary TopCoat</th>
          <th >Edit</th>
          <th >Delete</th>
          <!-- <th class="no-sort">Notes</th> -->
        </tr>
      </thead>
      <tbody></tbody>
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
import { ref, nextTick,onUnmounted } from "vue";
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
    let table
    const partUpdate = ref({
      id: 0,
      plateThick: 0,
      primaryPer: 0,
    });

     onUnmounted(()=>{
    table?.clear().destroy();
    })

    const gettinDataParts = async () => {
      table?.clear().destroy();
      generateDataTable();
    };

    $(document).ready(function () {
      $("#filterPartInputBot1")
        .off()
        .keyup(function () {
          table.search(this.value).draw();
        });
    });
    const generateDataTable = () => {
      table?.clear().destroy();
      nextTick(() => {
        table =$("#partsTable1").DataTable({
          // ordering: true,
          bLengthChange: false,
          pageLength: 10,
          stateSave: true,
          columnDefs: [
            {
              defaultContent: "-",
              targets: "_all",
            },
          ],
          // order: [[0, "desc"]],
          responsive: true,
          language: {
            paginate: {
              next: `<svg class="arrow_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="15" height="14" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><g transform="rotate(270 10 10)"><path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="white"/></g></svg>`, // or '→'
              previous: `<svg class="arrow_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="15" height="14" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><g transform="rotate(90 10 10)"><path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="white"/></g></svg>`, // or '←'
            },
            info: "Showing results _START_ to _END_ from _TOTAL_",
          },
          ajax: {
            url: `/part/getPartsByRun/${run.id}`,
          },
          stateSaveCallback: function (settings, data) {
            const state = settings.aoData;
            let arr = [];
            state.forEach((element) => {
              arr.push(element._aData);
            });
            partsTable.value = arr;
          },
          columns: [
            {
              name: "part.description",
              searchable: true,
              // orderable: false,
              render: function (data, type, row, meta) {
                if (row.description != null) {
                  return `<td>${row.description}</td>`;
                }
                return "<td></td>";
              },
            },
            {
              name: "part.plate_type.name",
              searchable: true,
               orderable: false,
              render: function (data, type, row, meta) {
                if (row.plate_type?.name != undefined) {
                  return "<td>" + row.plate_type?.name + "</td>";
                }
                return "<td></td>";
              },
            },
            {
              name: "part.chromate.name",
              searchable: true,
               orderable: false,
              render: function (data, type, row, meta) {
                if (row.chromate?.name != undefined) {
                  return `<td> ${row.chromate?.name}  ${
                    row.primaryPer != null ? "/ " + row.primaryPer + " %" : ""
                  } ${
                    row.primaryTemp != null
                      ? "/ " + row.primaryTemp + " °F"
                      : ""
                  } ${
                    row.primaryPH != null ? " /" + row.primaryPH + " pH" : ""
                  } ${
                    row.primaryDiptime != null
                      ? " / " + row.primaryDiptime + " sec"
                      : ""
                  } </td>`;
                }
                return "<td></td>";
              },
            },
            {
              name: "part.top_coat.name",
              searchable: true,
               orderable: false,
              render: function (data, type, row, meta) {
                if (row.top_coat?.name != undefined) {
                  return `<td> ${row.top_coat?.name}  ${
                    row.topCoatPer != null ? "/ " + row.topCoatPer + " %" : ""
                  } ${
                    row.topCoatTemp != null
                      ? "/ " + row.topCoatTemp + " °F"
                      : ""
                  } ${
                    row.topCoatPH != null ? " /" + row.topCoatPH + " pH" : ""
                  } ${
                    row.topCoatDiptime != null
                      ? " / " + row.topCoatDiptime + " sec"
                      : ""
                  } </td>`;
                }
                return "<td></td>";
              },
            },
            {
              name: "part.coat.name",
              searchable: true,
               orderable: false,
              render: function (data, type, row, meta) {
                if (row.coat?.name != undefined) {
                  return `<td> ${row.coat?.name}  ${
                    row.coatPer != null ? "/ " + row.coatPer + " %" : ""
                  } ${
                    row.coatTemp != null ? "/ " + row.coatTemp + " °F" : ""
                  } ${row.coatPH != null ? " /" + row.coatPH + " pH" : ""} ${
                    row.coatDiptime != null
                      ? " / " + row.coatDiptime + " sec"
                      : ""
                  } </td>`;
                }
                return "<td></td>";
              },
            },
            {
              name: "editPart",
              searchable: false,
              render: function (data, type, row, meta) {
                return (
                  '<button class="editpart" itemId=' +
                  row.id +
                  '>  <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">    <g fill="none" fill-rule="evenodd">        <path fill="#227D9E" d="M0 0h25v25H0z"/>        <g fill="#FFF" fill-rule="nonzero" stroke="#FFF" stroke-width=".5">            <path d="M18.218 13.773a.353.353 0 0 0-.357.35v3.103a1.06 1.06 0 0 1-1.07 1.048H6.783c-.59 0-1.069-.47-1.07-1.048V8.117c.001-.579.48-1.048 1.07-1.049H9.95a.353.353 0 0 0 .357-.35c0-.192-.16-.349-.357-.349H6.783C5.8 6.37 5.001 7.152 5 8.117v9.109c.001.965.799 1.746 1.783 1.748h10.008c.985-.002 1.783-.783 1.784-1.748v-3.104c0-.193-.16-.35-.357-.35z"/>            <path d="M18.434 5.47a1.628 1.628 0 0 0-2.27 0L9.8 11.706a.348.348 0 0 0-.091.154l-.837 2.96a.345.345 0 0 0 .091.34.362.362 0 0 0 .348.09l3.02-.82a.358.358 0 0 0 .158-.09l6.363-6.235a1.552 1.552 0 0 0 0-2.225l-.418-.41zm-7.857 6.463 5.208-5.104 1.68 1.646-5.208 5.104-1.68-1.646zm-.335.66 1.342 1.315-1.856.504.514-1.82zm8.106-4.983-.379.371-1.68-1.646.38-.37a.905.905 0 0 1 1.26 0l.419.41a.863.863 0 0 1 0 1.235z"/>        </g>    </g></svg>  </button>'
                );
              },
            },
            {
              name: "deletePart",
              searchable: false,
              render: function (data, type, row, meta) {
                return (
                  '<button class="showdelete" itemId=' +
                  row.id +
                  '>  <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"> <g fill="none" fill-rule="evenodd"> <path fill="#0271C5" d="M0 0h25v25H0z"/> <g fill="#FFF" fill-rule="nonzero"> <path d="m18.495 8.13-.312-.927a.869.869 0 0 0-.826-.59h-2.62v-.846A.77.77 0 0 0 13.966 5h-2.4a.77.77 0 0 0-.772.767v.846h-2.62a.869.869 0 0 0-.826.59l-.311.926a.69.69 0 0 0 .096.625c.13.18.343.288.567.288h.326l.717 8.794A1.279 1.279 0 0 0 10.014 19h5.648c.66 0 1.218-.511 1.272-1.164l.717-8.794h.18a.703.703 0 0 0 .568-.288.69.69 0 0 0 .096-.625zM11.62 5.82h2.29v.793h-2.29V5.82zm4.49 11.95a.45.45 0 0 1-.448.41h-5.648a.45.45 0 0 1-.447-.41l-.712-8.728h7.966l-.712 8.728zM7.876 8.222l.255-.76a.043.043 0 0 1 .041-.029h9.184c.019 0 .035.012.041.03l.256.76H7.877z"/> <path d="M14.79 17.741h.022c.218 0 .401-.17.412-.388l.389-7.394a.412.412 0 0 0-.392-.43.412.412 0 0 0-.434.387l-.389 7.394a.411.411 0 0 0 .392.431zM10.324 17.354a.412.412 0 0 0 .436.387.411.411 0 0 0 .39-.432l-.406-7.394a.412.412 0 0 0-.436-.387.411.411 0 0 0-.39.432l.406 7.394zM12.77 17.742a.412.412 0 0 0 .413-.41V9.937a.412.412 0 0 0-.413-.41.412.412 0 0 0-.414.41v7.393c0 .227.185.41.414.41z"/> </g> </g> </svg>  </button>'
                );
              },
            },
          ],
          drawCallback: function () {
            $("#partsTable1").on("click", "[class*=editpart]", function (e) {
              const part = partsTable.value.find((part) => {
                return part.id === Number(e.currentTarget.attributes[1].value);
              });
              editPart(part);
            });
            $("#partsTable1").on("click", "[class*=showdelete]", function (e) {
              openModalDelete(e.currentTarget.attributes[1].value);
            });
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
