<template>
  <div class="container p-9">
    <select class="w-full mb-5" @change="changeFilter" v-model="filterOption">
      <option value="3">All</option>
      <option value="0">Active</option>
      <option value="1">Complete</option>
    </select>
    <div class="rounded-lg shadow-lg p-5">
      <table id="activeRuns" class="display" style="width: 100%; height: 100%">
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
            <!-- <th class="no-sort">Notes</th> -->
            <th class="no-sort">Reports</th>
            <th class="no-sort">Email</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="run in runs" :key="run.id">
            <td class="text-center">{{ run.startDate.slice(0, 10) }}</td>
            <!-- <td class="text-center">{{ run.user_id }}</td> -->
            <td class="text-center">{{ "testing" }}</td>
            <td class="text-center">{{ run.id }}</td>
            <td class="text-center">{{ run.method.name }}</td>
            <td class="text-center">
              <span v-if="run.status == 0">Active</span>
              <span v-if="run.status == 1">Complete</span>
            </td>
            <td class="text-center">
              {{
                calculateHours(
                  run.id,
                  run.status,
                  run.created_at,
                  run.isEdit,
                  run.last_edit,
                  run.hours,
                  run.closed_date
                )
              }}
            </td>
            <!-- Photos action -->
            <td class="text-center">
              <button @click="showPhotos(run.id)">
                <icon-photo />
              </button>
            </td>
            <!-- Result action -->
            <td class="text-center">
              <button @click="showResults(run.id)">
                <icon-result />
              </button>
            </td>
            <!-- Edit action -->
            <td class="text-center">
              <button @click="editRun(run.id)">
                <icon-edit />
              </button>
            </td>
            <!-- Delete action -->
            <td class="text-center">
              <button @click="showDelete(run.id)">
                <icon-delete />
              </button>
            </td>
            <!-- Close action -->
            <td class="text-center">
              <button @click="showClose(run.id)" :disabled="run.status == 1">
                <icon-close :class="{ grayscale: run.status == 1 }" />
              </button>
            </td>
            <!-- ReOpen action -->
            <td class="text-center">
              <button @click="showReOpen(run.id)" :disabled="run.status == 0">
                <icon-re-open :class="{ grayscale: run.status == 0 }" />
              </button>
            </td>
            <!-- Report action -->
            <td class="text-center">
              <button @click="reportRun(run.id)">
                <svg
                  width="25"
                  height="25"
                  viewBox="0 0 25 25"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <g fill="none" fill-rule="evenodd">
                    <path fill="#024CC5" d="M0 0h25v25H0z" />
                    <g fill="#FFF" fill-rule="nonzero">
                      <path
                        d="M11.586 9.915a.564.564 0 0 0-.797 0l-1.851 1.852-.727-.728a.563.563 0 0 0-.796.796L8.54 12.96a.565.565 0 0 0 .795.001l2.25-2.25a.563.563 0 0 0 0-.796zM11.586 14.415a.564.564 0 0 0-.797 0l-1.851 1.852-.727-.727a.563.563 0 0 0-.796.796L8.54 17.46a.56.56 0 0 0 .796 0l2.25-2.25a.563.563 0 0 0 0-.796zM15.688 10.875h-2.25a.563.563 0 0 0 0 1.125h2.25a.563.563 0 0 0 0-1.125zM15.688 15.375h-2.25a.563.563 0 0 0 0 1.125h2.25a.563.563 0 0 0 0-1.125z"
                      />
                      <path
                        d="M17.375 5.25h-2.25v-.563a.563.563 0 0 0-.563-.562h-1.22A1.692 1.692 0 0 0 11.75 3c-.733 0-1.359.47-1.592 1.125h-1.22a.563.563 0 0 0-.563.563v.562h-2.25C5.505 5.25 5 5.755 5 6.375v13.5C5 20.495 5.505 21 6.125 21h11.25c.62 0 1.125-.505 1.125-1.125v-13.5c0-.62-.505-1.125-1.125-1.125zm-7.875 0h1.125c.31 0 .563-.252.563-.563a.563.563 0 0 1 1.124 0c0 .311.252.563.563.563H14v1.125H9.5V5.25zm7.875 14.625H6.125v-13.5h2.25v.563c0 .31.252.562.563.562h5.624c.311 0 .563-.252.563-.563v-.562h2.25v13.5z"
                      />
                    </g>
                  </g>
                </svg>
              </button>
              <button @click="reportAndPhotosRun(run.id)">
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
                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                  />
                </svg>
              </button>
            </td>
            <!-- Email action -->
            <td class="text-center">
              <button @click="openModalEmail">
                <icon-email />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <modal
      :show="isModalResults"
      :maxWidth="modalWidthDetail"
      @close="closeResultsModal"
    >
      <table-part-detail :id="idGlobal" />
    </modal>

    <photos-run
      v-if="run"
      :isModalPhotos="isModalPhotos"
      :photos="run.photos"
      @closeModal="closePhotosModal"
      @photoEdited="photoAdded"
    />

    <modal :show="modalEmail" @close="closeModalEmail">
      <div class="p-5">
        <h1 class="font-bold text-2xl text-center p-5">EMAIL RUN RESULTS</h1>
        <p class="pb-4">Please enter a valid email address below. The email address that you enter below will receive an email from dvega@letternine.net. This email will contain the results to the run as well as a link to view all of the images for this run.</p>
        <input type="text" class="w-full" placeholder="Email Address" />
        <button class="bg-primary p-5 rounded-md text-center text-white mt-5 w-full">Send Email</button>
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
import dt from "datatables.net";
import { Navigation, Pagination } from "swiper";
import { ref, nextTick } from "vue";
import Modal from "../../../Jetstream/Modal.vue";
import ConfirmationModal from "../../../Jetstream/ConfirmationModal.vue";
import useHelper from "@/composables/useHelper";
import NotesRun from "./NotesRun.vue";
import PhotosRun from "./PhotosRun.vue";
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

export default {
  components: {
    modal: Modal,
    confirmationModal: ConfirmationModal,
    notesRun: NotesRun,
    photosRun: PhotosRun,
    IconEmail,
    IconEdit,
    IconDelete,
    IconReOpen,
    IconClose,
    IconResult,
    IconPhoto,
    tablePartDetail: TablePartDetailVue,
    IconClose,
  },
  setup() {
    const { makeToast } = useHelper();
    const runs = ref([]);
    const run = ref(null);
    const runDetail = ref(null);
    const modalWidthDetail = ref("200");
    const isModalPhotos = ref(false);
    const isModalResults = ref(false);
    const isModalNotes = ref(false);
    const isModalDelete = ref(false);
    const isModalClose = ref(false);
    const isModalReOpen = ref(false);
    const idGlobal = ref(0);
    const filterOption = ref(3);
    const modalEmail = ref(false);
    const onSwiper = (swiper) => {};
    const onSlideChange = () => {
      // console.log('slide change');
    };

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
          gettingData(1);
          filterOption.value = 1;
          idGlobal.value = 0;
        } else {
          console.log("ha ocurrido un error");
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
          gettingData(0);

          filterOption.value = 0;
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
        const res = await axios.get(`/run/getAllRuns/${status}`);
        // runs.value = res.data.data;
        runs.value = res.data;
        $("#activeRuns").DataTable().destroy();
        await generateDataTable();
      } catch (e) {
        console.log(e);
      }
    };
    const changeFilter = async () => {
      gettingData(filterOption.value);
    };
    const generateDataTable = () => {
      nextTick(() => {
        $("#activeRuns").DataTable({
          scrollY: 350,
          ordering: true,
          bLengthChange: false,
          pageLength: 5,
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
      openModalEmail: () => (modalEmail.value = true),
      closeModalEmail: () => (modalEmail.value = false),
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