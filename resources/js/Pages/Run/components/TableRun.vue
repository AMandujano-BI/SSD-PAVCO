<template>
  <div class="container p-9">
    <div class="flex gap-8 items-center mb-5">
      <button @click="openModalButton"><icon-plus /></button>
      <select class="w-full" @change="changeFilter" v-model="filterOption">
        <option value="3">Show All</option>
        <option value="0">Active</option>
        <option value="1">Complete</option>
      </select>
    </div>
    <div class="rounded-lg shadow-lg p-5">
      <table
        id="activeRuns"
        class="display nowrap"
        style="width: 100%; height: 100%"
      >
        <thead>
          <tr>
            <th>StartDate</th>
            <th>Customer</th>
            <th>Run #</th>
            <th>Method</th>
            <th>Status</th>
            <th>Hrs</th>
            <th class="no-sort">Photos</th>
            <th class="no-sort">Results</th>
            <th class="no-sort">Edit</th>
            <th class="no-sort">Delete</th>
            <th class="no-sort">Close</th>
            <th class="no-sort">Re-Open</th>
            <th class="no-sort">Reports</th>
            <th class="no-sort">Email</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

    <modal
      :show="isModalResults"
      :maxWidth="modalWidthDetail"
      @close="closeResultsModal"
    >
      <table-part-detail :id="idGlobal" />
    </modal>

    <!-- <photos-run
      v-if="run"
      :isModalPhotos="isModalPhotos"
      :photos="run.photos"
      @closeModal="closePhotosModal"
      @photoEdited="photoAdded"
    /> -->

    <modal :show="isModalPhotos" @close="closePhotosModal">
      <div class="container mx-auto p-5 relative">
        <button @click="closePhotosModal" class="absolute right-5">X</button>
        <photos-run :id="idGlobal" />
      </div>
    </modal>

    <modal :show="modalEmail" @close="closeModalEmail">
      <div class="p-5">
        <form-email
          :emailSend="emailSend"
          :id="idGlobal"
          @closeModal="closeModalEmail"
        />
      </div>
    </modal>

    <notes-run
      v-if="run"
      :isModalNotes="isModalNotes"
      :run="run"
      @closeModal="closeNotesModal"
      @noteAdded="noteAdded"
    />

    <!-- CONFIRMATIONS MODAL -->

    <confirmation-modal :show="isModalDelete">
      <template v-slot:title>
        <h1>Are you sure that you want to delete this run #{{ idGlobal }}?</h1>
      </template>
      <template v-slot:content>
        <div class="flex justify-center gap-4 items-center">
          <button
            class="bg-red-500 p-4 text-white rounded-md mr-4"
            @click="closeDeleteModal"
          >
            Cancel
          </button>
          <button
            class="bg-green-500 p-4 text-white rounded-md"
            @click="deleteRun"
          >
            Acept
          </button>
        </div>
      </template>
    </confirmation-modal>

    <confirmation-modal :show="isModalClose">
      <template v-slot:title>
        <h1>Are you sure that you want to close this run #{{ idGlobal }}?</h1>
      </template>
      <template v-slot:content>
        <div class="flex justify-center gap-4 items-center">
          <button
            class="bg-red-500 p-4 text-white rounded-md mr-4 min-w-[400]"
            @click="closeCloseModal"
          >
            Cancel
          </button>
          <button
            class="bg-green-500 p-4 text-white rounded-md"
            @click="closeRun"
          >
            Acept
          </button>
        </div>
      </template>
    </confirmation-modal>

    <confirmation-modal :show="isModalReOpen">
      <template v-slot:title>
        <h1>Are you sure that you want to re open this run #{{ idGlobal }}</h1>
      </template>
      <template v-slot:content>
        <div class="flex justify-center gap-4 items-center">
          <button
            class="bg-red-500 p-4 text-white rounded-md mr-4"
            @click="closeReOpenModal"
          >
            Cancel
          </button>
          <button
            class="bg-green-500 p-4 text-white rounded-md"
            @click="reopenRun"
          >
            Acept
          </button>
        </div>
      </template>
    </confirmation-modal>
  </div>
</template>

<script>
import { Navigation, Pagination } from "swiper";
import { ref, nextTick } from "vue";
import Modal from "../../../Jetstream/Modal.vue";
import ConfirmationModal from "../../../Jetstream/ConfirmationModal.vue";
import useHelper from "@/composables/useHelper";
import NotesRun from "./NotesRun.vue";
import PhotosRun from "./PhotosRun.vue";
import FormEmail from "./FormEmail.vue";
import TablePartDetailVue from "./TablePartDetail.vue";
import { Inertia } from "@inertiajs/inertia";
import IconEmail from "@/assets/Icons/iconEmail.vue";
import IconEdit from "@/assets/Icons/iconEdit.vue";
import IconDelete from "@/assets/Icons/iconDelete.vue";
import IconReOpen from "@/assets/Icons/iconReopen.vue";
import IconResult from "@/assets/Icons/iconResult.vue";
import IconPhoto from "@/assets/Icons/iconPhoto.vue";
import IconClose from "@/assets/Icons/iconClose.vue";
const $ = require("jquery");
var dt = require("datatables.net");
import "datatables.net-responsive-dt";
import "datatables.net-rowreorder-dt";
import IconPlusVue from "@/assets/Icons/iconPlus.vue";

export default {
  emits: ["openModalButton"],
  components: {
    modal: Modal,
    confirmationModal: ConfirmationModal,
    notesRun: NotesRun,
    photosRun: PhotosRun,
    FormEmail,
    IconEmail,
    IconEdit,
    IconDelete,
    IconReOpen,
    IconClose,
    IconResult,
    IconPhoto,
    IconPlus: IconPlusVue,
    tablePartDetail: TablePartDetailVue,
    IconClose,
  },
  setup(props, { emit }) {
    const { makeToast } = useHelper();
    let runs = ref([]);
    const run = ref(null);
    const runDetail = ref(null);
    const modalWidthDetail = ref("200");
    const isModalPhotos = ref(false);
    const isModalResults = ref(false);
    const isModalNotes = ref(false);
    const isModalDelete = ref(false);
    const isModalClose = ref(false);
    const isModalReOpen = ref(false);
    const emailSend = ref("");
    const idGlobal = ref(0);
    const filterOption = ref(3);
    const modalEmail = ref(false);
    const onSwiper = (swiper) => {};
    const onSlideChange = () => {};
    $(document).ready(function () {
      $("#filterRunInput")
        .off()
        .keyup(function () {
          $("#activeRuns").DataTable().search(this.value).draw();
        });
    });
    const findRun = (id) => {
      run.value = runs.value.find((run) => run.id === id);
      idGlobal.value = id;
    };
    // Photos
    const showPhotos = (id) => {
      findRun(id);
      isModalPhotos.value = true;
    };
    const closePhotosModal = () => {
      isModalPhotos.value = false;
    };
    // Results
    const showResults = (id) => {
      findRun(id);
      idGlobal.value = id;
      isModalResults.value = true;
    };
    const closeResultsModal = () => {
      isModalResults.value = false;
      idGlobal.value = 0;
    };
    // Notes
    const showNotes = (id) => {
      findRun(id);
      isModalNotes.value = true;
    };
    const closeNotesModal = () => {
      isModalNotes.value = false;
    };
    const noteAdded = () => {
      gettingData();
    };
    const photoAdded = () => {
      gettingData();
    };
    // Delete
    const showDelete = (id) => {
      findRun(id);
      isModalDelete.value = true;
    };
    const closeDeleteModal = () => (isModalDelete.value = false);
    // Close
    const showClose = (id) => {
      idGlobal.value = id;
      isModalClose.value = true;
    };
    const closeCloseModal = () => (isModalClose.value = false);
    // ReOpen
    const showReOpen = (id) => {
      idGlobal.value = id;
      isModalReOpen.value = true;
    };
    const closeReOpenModal = () => (isModalReOpen.value = false);

    const calculateHours = (
      id,
      status,
      created_date,
      edit,
      lastDate,
      hours,
      closeDate
    ) => {
      if (status === 1) {
        //cerrado
        if (edit) {
          return hours;
        } else {
          const closeNonEdit =
            Math.abs(new Date(closeDate) - new Date(created_date)) / 36e5;
          return closeNonEdit | 0; // trunca los decimales y se queda con el entero
        }
      } else {
        if (edit) {
          const activeEdit = Math.abs(new Date() - new Date(lastDate)) / 36e5;
          const hoursEdited = activeEdit | 0; // trunca los decimales y se queda con el entero
          return hours + hoursEdited;
        } else {
          const activeNonEdit =
            Math.abs(new Date() - new Date(created_date)) / 36e5;
          return activeNonEdit | 0; // trunca los decimales y se queda con el entero
        }
      }
    };

    const closeRun = async () => {
      try {
        const res = await axios.put(`/run/closeRun/${idGlobal.value}`);
        const { ok, message, value } = res.data;
        if (ok) {
          isModalClose.value = false;
          makeToast(message);
          gettingData(filterOption.value);
          // filterOption.value = 1;
          idGlobal.value = 0;
        } else {
          makeToast(message, "error");
        }
      } catch (e) {
        console.log(e);
      }
    };

    const reopenRun = async () => {
      try {
        const res = await axios.put(`/run/reopenRun/${idGlobal.value}`);
        const { ok, message, value } = res.data;
        if (ok) {
          isModalReOpen.value = false;
          makeToast(message);
          gettingData(filterOption.value);

          // filterOption.value = 0;
          idGlobal.value = 1;
        } else {
          makeToast(message, "error");
        }
      } catch (error) {
        console.log(error);
      }
    };

    const deleteRun = async () => {
      try {
        const res = await axios.delete(`/run/deleteRun/${idGlobal.value}`);
        const { ok, message, value } = res.data;
        if (ok) {
          isModalDelete.value = false;
          makeToast(message);
          gettingData();
          idGlobal.value = 1;
        } else {
          makeToast(message, "error");
        }
      } catch (error) {
        console.log(error);
      }
    };
    const gettingData = async (status = 3) => {
      try {
        // const res = await axios.get(`/run/getAllRuns/${status}`);
        // runs.value = res.data.data;
        // runs.value = res.data;
        $("#activeRuns").DataTable().destroy();
        await generateDataTable(status);
      } catch (e) {
        console.log(e);
      }
    };
    const changeFilter = async () => {
      gettingData(filterOption.value);
    };
    const generateDataTable = (status) => {
      const self = this;
      nextTick(() => {
        $("#activeRuns").DataTable({
          // scrollY: 350,
          ordering: true,
          bLengthChange: false,
          pageLength: 10,
          processing: true,
          serverSide: true,
          stateSave: true,
          rowReorder: {
            selector: "td:nth-child(2)",
          },
          columnDefs: [
            {
              defaultContent: "-",
              targets: "_all",
            },
          ],
          responsive: true,
          language: {
            paginate: {
              next: `→`, // or '→'
              previous: `←`, // or '←'
            },
            info: "Showing results _START_ to _END_ from _TOTAL_",
          },
          ajax: {
            url: `/run/getAllRuns/${status}`,
          },
          stateSaveCallback: function (settings, data) {
            const state = settings.aoData;
            let arr = [];
            state.forEach((element) => {
              arr.push(element._aData);
            });
            runs.value = arr;
          },
          columns: [
            {
              name: "start_date",
              searchable: true,
              render: function (data, type, row, meta) {
                return "<td>" + row.start_date.slice(0, 10) + "</td>";
              },
            },
            {
              name: "company.name",
              searchable: true,
              render: function (data, type, row, meta) {
                return "<td>" + row.company.name + "</td>";
              },
            },
            {
              name: "id",
              searchable: true,
              render: function (data, type, row, meta) {
                return "<td>" + row.id + "</td>";
              },
            },
            {
              name: "Method",
              searchable: false,
              render: function (data, type, row, meta) {
                return "<td>" + row.method.name + "</td>";
              },
            },
            {
              name: "Status",
              searchable: false,
              render: function (data, type, row, meta) {
                let status;
                if (row.status === 1) {
                  status = "<span>Complete</span>";
                } else {
                  status = "<span>Active</span>";
                }
                return status;
              },
            },
            {
              name: "Hours",
              searchable: false,
              render: function (data, type, row, meta) {
                return (
                  '<td class="text-center">' +
                  calculateHours(
                    row.id,
                    row.status,
                    row.start_date,
                    row.isEdit,
                    row.last_edit,
                    row.hours,
                    row.closed_date
                  ) +
                  "</td>"
                );
              },
            },
            {
              name: "PhotoAction",
              searchable: false,
              render: function (data, type, row, meta) {
                return (
                  '<button class="showphotos"  itemId=' +
                  row.id +
                  '>  <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><path fill="#75DDDD" d="M0 0h25v25H0z"/><g fill="#FFF" fill-rule="nonzero"><path d="M20.48 12.166c-.148-.21-3.679-5.138-8.187-5.138-4.51 0-8.04 4.928-8.188 5.138-.14.199-.14.469 0 .668.148.21 3.679 5.138 8.188 5.138 4.508 0 8.039-4.928 8.187-5.138a.582.582 0 0 0 0-.668zm-8.187 4.674c-3.322 0-6.198-3.271-7.05-4.34.85-1.07 3.721-4.34 7.05-4.34 3.32 0 6.197 3.27 7.049 4.34-.85 1.07-3.721 4.34-7.05 4.34z"/><path d="M12.293 9.104c-1.81 0-3.281 1.523-3.281 3.396s1.472 3.396 3.28 3.396c1.81 0 3.281-1.523 3.281-3.396s-1.472-3.396-3.28-3.396zm0 5.66c-1.206 0-2.187-1.015-2.187-2.264 0-1.249.98-2.264 2.187-2.264 1.206 0 2.187 1.015 2.187 2.264 0 1.249-.981 2.264-2.187 2.264z"/></g></g></svg>  </button>'
                );
              },
            },
            {
              name: "ResultAction",
              searchable: false,
              render: function (data, type, row, meta) {
                return (
                  '<button class="showresults" itemId=' +
                  row.id +
                  '>  <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><path fill="#508991" d="M0 0h25v25H0z"/><g fill="#FFF" fill-rule="nonzero"><path d="M12.586 9.915a.564.564 0 0 0-.797 0l-1.851 1.852-.727-.728a.563.563 0 0 0-.796.796L9.54 12.96a.565.565 0 0 0 .795.001l2.25-2.25a.563.563 0 0 0 0-.796zM12.586 14.415a.564.564 0 0 0-.797 0l-1.851 1.852-.727-.727a.563.563 0 0 0-.796.796L9.54 17.46a.56.56 0 0 0 .796 0l2.25-2.25a.563.563 0 0 0 0-.796zM16.688 10.875h-2.25a.563.563 0 0 0 0 1.125h2.25a.563.563 0 0 0 0-1.125zM16.688 15.375h-2.25a.563.563 0 0 0 0 1.125h2.25a.563.563 0 0 0 0-1.125z"/><path d="M18.375 5.25h-2.25v-.563a.563.563 0 0 0-.563-.562h-1.22A1.692 1.692 0 0 0 12.75 3c-.733 0-1.359.47-1.592 1.125h-1.22a.563.563 0 0 0-.563.563v.562h-2.25C6.505 5.25 6 5.755 6 6.375v13.5C6 20.495 6.505 21 7.125 21h11.25c.62 0 1.125-.505 1.125-1.125v-13.5c0-.62-.505-1.125-1.125-1.125zm-7.875 0h1.125c.31 0 .563-.252.563-.563a.563.563 0 0 1 1.124 0c0 .311.252.563.563.563H15v1.125h-4.5V5.25zm7.875 14.625H7.125v-13.5h2.25v.563c0 .31.252.562.563.562h5.624c.311 0 .563-.252.563-.563v-.562h2.25v13.5z"/>        </g></g></svg>  </button>'
                );
              },
            },
            {
              name: "EditAction",
              searchable: false,
              render: function (data, type, row, meta) {
                return (
                  '<button class="editrun" itemId=' +
                  row.id +
                  '>  <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">    <g fill="none" fill-rule="evenodd">        <path fill="#227D9E" d="M0 0h25v25H0z"/>        <g fill="#FFF" fill-rule="nonzero" stroke="#FFF" stroke-width=".5">            <path d="M18.218 13.773a.353.353 0 0 0-.357.35v3.103a1.06 1.06 0 0 1-1.07 1.048H6.783c-.59 0-1.069-.47-1.07-1.048V8.117c.001-.579.48-1.048 1.07-1.049H9.95a.353.353 0 0 0 .357-.35c0-.192-.16-.349-.357-.349H6.783C5.8 6.37 5.001 7.152 5 8.117v9.109c.001.965.799 1.746 1.783 1.748h10.008c.985-.002 1.783-.783 1.784-1.748v-3.104c0-.193-.16-.35-.357-.35z"/>            <path d="M18.434 5.47a1.628 1.628 0 0 0-2.27 0L9.8 11.706a.348.348 0 0 0-.091.154l-.837 2.96a.345.345 0 0 0 .091.34.362.362 0 0 0 .348.09l3.02-.82a.358.358 0 0 0 .158-.09l6.363-6.235a1.552 1.552 0 0 0 0-2.225l-.418-.41zm-7.857 6.463 5.208-5.104 1.68 1.646-5.208 5.104-1.68-1.646zm-.335.66 1.342 1.315-1.856.504.514-1.82zm8.106-4.983-.379.371-1.68-1.646.38-.37a.905.905 0 0 1 1.26 0l.419.41a.863.863 0 0 1 0 1.235z"/>        </g>    </g></svg>  </button>'
                );
              },
            },
            {
              name: "DeleteAction",
              searchable: false,
              render: function (data, type, row, meta) {
                return (
                  '<button class="showdelete" itemId=' +
                  row.id +
                  '>  <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"> <g fill="none" fill-rule="evenodd"> <path fill="#0271C5" d="M0 0h25v25H0z"/> <g fill="#FFF" fill-rule="nonzero"> <path d="m18.495 8.13-.312-.927a.869.869 0 0 0-.826-.59h-2.62v-.846A.77.77 0 0 0 13.966 5h-2.4a.77.77 0 0 0-.772.767v.846h-2.62a.869.869 0 0 0-.826.59l-.311.926a.69.69 0 0 0 .096.625c.13.18.343.288.567.288h.326l.717 8.794A1.279 1.279 0 0 0 10.014 19h5.648c.66 0 1.218-.511 1.272-1.164l.717-8.794h.18a.703.703 0 0 0 .568-.288.69.69 0 0 0 .096-.625zM11.62 5.82h2.29v.793h-2.29V5.82zm4.49 11.95a.45.45 0 0 1-.448.41h-5.648a.45.45 0 0 1-.447-.41l-.712-8.728h7.966l-.712 8.728zM7.876 8.222l.255-.76a.043.043 0 0 1 .041-.029h9.184c.019 0 .035.012.041.03l.256.76H7.877z"/> <path d="M14.79 17.741h.022c.218 0 .401-.17.412-.388l.389-7.394a.412.412 0 0 0-.392-.43.412.412 0 0 0-.434.387l-.389 7.394a.411.411 0 0 0 .392.431zM10.324 17.354a.412.412 0 0 0 .436.387.411.411 0 0 0 .39-.432l-.406-7.394a.412.412 0 0 0-.436-.387.411.411 0 0 0-.39.432l.406 7.394zM12.77 17.742a.412.412 0 0 0 .413-.41V9.937a.412.412 0 0 0-.413-.41.412.412 0 0 0-.414.41v7.393c0 .227.185.41.414.41z"/> </g> </g> </svg>  </button>'
                );
              },
            },
            {
              name: "CloseAction",
              searchable: false,
              render: function (data, type, row, meta) {
                if (row.status === 1) {
                  return (
                    '<button class="showclose" itemId=' +
                    row.id +
                    ' disabled>  <svg width="25" height="25" class="grayscale" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><path fill="#17C4B2" d="M0 0h25v25H0z"/><path stroke="#FFF" stroke-linecap="round" d="m7 7 11.129 11.129M18.129 7 6.999 18.129"/></g></svg>  </button>'
                  );
                } else {
                  return (
                    '<button class="showclose" itemId=' +
                    row.id +
                    '>  <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><path fill="#17C4B2" d="M0 0h25v25H0z"/><path stroke="#FFF" stroke-linecap="round" d="m7 7 11.129 11.129M18.129 7 6.999 18.129"/></g></svg> </button>'
                  );
                }
              },
            },
            {
              name: "ReOpenAction",
              searchable: false,
              render: function (data, type, row, meta) {
                if (row.status === 0) {
                  return (
                    '<button class="showreopen" itemId=' +
                    row.id +
                    ' disabled>  <svg width="25" height="25" class="grayscale" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><path fill="#0ABC8A" d="M0 0h25v25H0z"/><rect stroke="#FFF" x="6.5" y="6.5" width="8" height="8" rx="1"/><rect stroke="#FFF" x="11.5" y="11.5" width="8" height="8" rx="1"/></g></svg> </button>'
                  );
                } else {
                  return (
                    '<button class="showreopen" itemId=' +
                    row.id +
                    '> <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><path fill="#0ABC8A" d="M0 0h25v25H0z"/><rect stroke="#FFF" x="6.5" y="6.5" width="8" height="8" rx="1"/><rect stroke="#FFF" x="11.5" y="11.5" width="8" height="8" rx="1"/></g></svg> </button>'
                  );
                }
              },
            },
            {
              name: "reportAction",
              searchable: false,
              render: function (data, type, row, meta) {
                return (
                  '<button class="reportrun" itemId=' +
                  row.id +
                  '>  <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg" > <g fill="none" fill-rule="evenodd"><path fill="#024CC5" d="M0 0h25v25H0z" /> <g fill="#FFF" fill-rule="nonzero"> <path d="M11.586 9.915a.564.564 0 0 0-.797 0l-1.851 1.852-.727-.728a.563.563 0 0 0-.796.796L8.54 12.96a.565.565 0 0 0 .795.001l2.25-2.25a.563.563 0 0 0 0-.796zM11.586 14.415a.564.564 0 0 0-.797 0l-1.851 1.852-.727-.727a.563.563 0 0 0-.796.796L8.54 17.46a.56.56 0 0 0 .796 0l2.25-2.25a.563.563 0 0 0 0-.796zM15.688 10.875h-2.25a.563.563 0 0 0 0 1.125h2.25a.563.563 0 0 0 0-1.125zM15.688 15.375h-2.25a.563.563 0 0 0 0 1.125h2.25a.563.563 0 0 0 0-1.125z" /> <path d="M17.375 5.25h-2.25v-.563a.563.563 0 0 0-.563-.562h-1.22A1.692 1.692 0 0 0 11.75 3c-.733 0-1.359.47-1.592 1.125h-1.22a.563.563 0 0 0-.563.563v.562h-2.25C5.505 5.25 5 5.755 5 6.375v13.5C5 20.495 5.505 21 6.125 21h11.25c.62 0 1.125-.505 1.125-1.125v-13.5c0-.62-.505-1.125-1.125-1.125zm-7.875 0h1.125c.31 0 .563-.252.563-.563a.563.563 0 0 1 1.124 0c0 .311.252.563.563.563H14v1.125H9.5V5.25zm7.875 14.625H6.125v-13.5h2.25v.563c0 .31.252.562.563.562h5.624c.311 0 .563-.252.563-.563v-.562h2.25v13.5z"/></g></g></svg> </button> <button class="reportandphotosrun" itemId=' +
                  row.id +
                  '> <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"> <g fill="none" fill-rule="evenodd"> <path fill="#1E385E" d="M0 0h25v25H0z"/> <g fill="#FFF" fill-rule="nonzero"> <path d="M14.584 12.249a3.253 3.253 0 0 0-3.249 3.25 3.253 3.253 0 0 0 3.25 3.249 3.253 3.253 0 0 0 3.249-3.25 3.253 3.253 0 0 0-3.25-3.249zm0 5.308a2.061 2.061 0 0 1-1.814-3.03l1.393 1.393a.596.596 0 0 0 .421.174h1.97a2.062 2.062 0 0 1-1.97 1.463zm.247-2.654-1.219-1.219a2.062 2.062 0 0 1 2.942 1.219h-1.723z"/> <path d="M18.09 4.77h-1.712a1.414 1.414 0 0 0-1.31-.885h-.925c-.23-.52-.75-.885-1.355-.885-.604 0-1.125.364-1.355.885h-.925c-.592 0-1.1.366-1.309.884H7.486C6.666 4.77 6 5.436 6 6.255v13.26C6 20.333 6.667 21 7.486 21H18.09c.819 0 1.486-.666 1.486-1.486V6.255c0-.82-.667-1.486-1.486-1.486zm-7.803.527c0-.122.1-.221.221-.221h1.396a.595.595 0 0 0 .595-.596.29.29 0 0 1 .578 0c0 .329.267.596.596.596h1.395c.122 0 .221.099.221.22v1.022c0 .122-.1.22-.221.22h-4.56a.221.221 0 0 1-.22-.22V5.297zm7.804 14.512H7.486a.295.295 0 0 1-.295-.295V6.255c0-.163.132-.295.295-.295h1.61v.358c0 .778.634 1.412 1.412 1.412h4.56c.779 0 1.412-.634 1.412-1.412V5.96h1.61c.163 0 .296.132.296.295v13.26a.295.295 0 0 1-.295.294z"/> <path d="M8.365 9.901h4.423a.595.595 0 0 0 0-1.19H8.365a.595.595 0 0 0 0 1.19zM8.365 11.67h2.654a.595.595 0 0 0 0-1.19H8.365a.595.595 0 0 0 0 1.19zM11.614 12.844a.595.595 0 0 0-.595-.595H8.365a.595.595 0 0 0 0 1.19h2.654a.595.595 0 0 0 .595-.595z"/> </g> </g> </svg> </button>'
                );
              },
            },
            {
              name: "emailAction",
              searchable: false,
              render: function (data, type, row, meta) {
                return (
                  '<button class="runemail" itemId=' +
                  row.id +
                  '>  <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"> <g fill="none" fill-rule="evenodd"> <path fill="#50BEFF" d="M0 0h25v25H0z"/> <path d="M19.5 7h-13C5.673 7 5 7.673 5 8.5v9c0 .827.673 1.5 1.5 1.5h13c.827 0 1.5-.673 1.5-1.5v-9c0-.827-.673-1.5-1.5-1.5zm0 1c.068 0 .133.014.192.039L13 13.839l-6.692-5.8A.498.498 0 0 1 6.5 8h13zm0 10h-13a.5.5 0 0 1-.5-.5V9.095l6.672 5.783a.5.5 0 0 0 .656 0L20 9.095V17.5a.5.5 0 0 1-.5.5z" fill="#FFF" fill-rule="nonzero"/> </g></svg> </button>'
                );
              },
            },
          ],
          drawCallback: function () {
            $("#activeRuns").on("click","[class*=showphotos]", function (e) { showPhotos(e.currentTarget.attributes[1].value); });
            $("#activeRuns").on("click","[class*=showresults]", function (e) { showResults(e.currentTarget.attributes[1].value); });
            $("#activeRuns").on("click","[class*=editrun]", function (e) { editRun(e.currentTarget.attributes[1].value); });
            $("#activeRuns").on("click","[class*=showdelete]", function (e) { showDelete(e.currentTarget.attributes[1].value); });
            $("#activeRuns").on("click","[class*=showclose]", function (e) { showClose(e.currentTarget.attributes[1].value); });
            $("#activeRuns").on("click","[class*=showreopen]", function (e) { showReOpen(e.currentTarget.attributes[1].value); });
            $("#activeRuns").on("click","[class*=reportrun]", function (e) { reportRun(e.currentTarget.attributes[1].value); });
            $("#activeRuns").on("click","[class*=reportandphotosrun]", function (e) { reportAndPhotosRun(e.currentTarget.attributes[1].value)});
            $("#activeRuns").on("click","[class*=runemail]", function (e) { openModalEmail(e.currentTarget.attributes[1].value); });
          },
        });
      });
    };

    // const editRun = (id) => (window.location.href = `/part/${id}`);
    const editRun = (id) => {
      // this.$inertia.get(route(`/part/${id}`));
      Inertia.get(`/part/${id}`);
    };
    const reportRun = (id) => {
      window.location.href = `/run/download/${id}`;
    };
    const reportAndPhotosRun = (id) => {
      window.location.href = `/run/downloadPlus/${id}`;
    };
    const openModalEmail = (id) => {
      modalEmail.value = true;
      idGlobal.value = id;
    };
    const closeModalEmail = () => {
      modalEmail.value = false;
      idGlobal.value = 0;
    };

    gettingData();

    return {
      runs,
      run,
      onSwiper,
      onSlideChange,
      modules: [Pagination, Navigation],
      isModalPhotos,
      isModalResults,
      isModalNotes,
      isModalDelete,
      isModalClose,
      isModalReOpen,
      reportRun,
      reportAndPhotosRun,
      editRun,
      generateDataTable,
      gettingData,
      deleteRun,
      closeRun,
      closeReOpenModal,
      closeDeleteModal,
      findRun,
      showPhotos,
      closePhotosModal,
      showResults,
      closeResultsModal,
      showNotes,
      closeNotesModal,
      noteAdded,
      photoAdded,
      showDelete,
      showClose,
      closeCloseModal,
      showReOpen,
      reopenRun,
      filterOption,
      changeFilter,
      runDetail,
      modalWidthDetail,
      idGlobal,
      calculateHours,
      modalEmail,
      openModalEmail,
      emailSend,
      closeModalEmail,
      openModalButton: () => emit("openModalButton"),
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