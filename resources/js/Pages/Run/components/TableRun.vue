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
            <td class="text-center">{{ calculateHours(run.id, run.status, run.created_at, run.isEdit, run.lastDateEdit, run.hours, run.closed_date)  }}</td>
            <!-- Photos action -->
            <td class="text-center">
              <button @click="showPhotos(run.id)">
                <img :src="ImgPhotos" alt="iconPhotos" />
              </button>
            </td>
            <!-- Result action -->
            <td class="text-center">
              <button @click="showResults(run.id)">
                <img :src="ImgResult" alt="iconResult" />
              </button>
            </td>
            <!-- Edit action -->
            <td class="text-center">
              <button @click="editRun(run.id)">
                <img :src="ImgEdit" alt="iconResult" />
              </button>
            </td>
            <!-- Delete action -->
            <td class="text-center">
              <button @click="showDelete(run.id)">
                <img :src="require('@/assets/Icons/iconDelete/iconDelete.png').default" alt="iconDelete" />
              </button>
            </td>
            <!-- Close action -->
            <td class="text-center">
              <button @click="showClose(run.id)" :disabled="run.status == 1">

                <img :src="ImgClose" alt="iconClose"  :class="{'grayscale':run.status ==1}"/>
              </button>
            </td>
            <!-- ReOpen action -->
            <td class="text-center">
              <button @click="showReOpen(run.id)" :disabled="run.status == 0">
                <img :src="ImgReOpen" alt="iconNote"  :class="{'grayscale':run.status ==0}"/>
              </button>
            </td>
            <!-- Notes action -->
            <!-- <td class="text-center">
              <button @click="showNotes(run.id)">
                <img :src="ImgNote" alt="iconNote" />
              </button>
            </td> -->
            <!-- Report action -->
            <td class="text-center">
              <button @click="reportRun(run.id)">
                <img :src="ImgReportRun" alt="iconReportRun" />
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
              <button>
                <img :src="ImgEmail" alt="iconEmail" />
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

    <notes-run
      v-if="run"
      :isModalNotes="isModalNotes"
      :run="run"
      @closeModal="closeNotesModal"
      @noteAdded="noteAdded"
    />

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
import ImgResult from "@/assets/Icons/documentShow/group5.png";
import ImgPhotos from "@/assets/Icons/iconPhotos/iconPhotos.png";
import ImgEdit from "@/assets/Icons/iconEdit/iconEdit.png";
import ImgDelete from "@/assets/Icons/iconDelete/iconDelete.png";
import ImgClose from "@/assets/Icons/iconClose/iconClose.png";
import ImgNote from "@/assets/Icons/iconNote/iconNote.png";
import ImgReOpen from "@/assets/Icons/iconReOpen/iconReOpen.png";
import ImgEmail from "@/assets/Icons/iconEmail/iconEmail.png";
import ImgReportRun from "@/assets/Icons/iconReport/iconReport.png";
import { Inertia } from '@inertiajs/inertia'
const $ = require("jquery");

export default {
  components: {
    modal: Modal,
    confirmationModal: ConfirmationModal,
    notesRun: NotesRun,
    photosRun: PhotosRun,
    tablePartDetail: TablePartDetailVue,
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
    const onSwiper = (swiper) => {
    };
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
    const closeDeleteModal = () => {
      isModalDelete.value = false;
    };
    // Close
    const showClose = (id) => {
      idGlobal.value = id;
      isModalClose.value = true;
    };
    const closeCloseModal = () => {
      isModalClose.value = false;
    };
    // ReOpen
    const showReOpen = (id) => {
      idGlobal.value = id;
      isModalReOpen.value = true;
    };
    const closeReOpenModal = () => {
      isModalReOpen.value = false;
    };

    const calculateHours = (id,status, created_date, edit, lastDate, hours, closeDate) => {
      if (status === 1 ){ //cerrado
        if (edit) {
          const hoursClose = Math.round( Math.abs(new Date(closeDate) - new Date(lastDate)) / 36e5);
          return hoursClose + hours;
        } else {
          return Math.round(Math.abs(new Date(closeDate) - new Date(created_date)) / 36e5);
        }
      } else {
        if (edit) {
          const hoursEdited = Math.round( Math.abs(new Date() - new Date(lastDate)) / 36e5 )
          return hours + hoursEdited;
        } else {
          return Math.round(Math.abs(new Date() - new Date(created_date)) / 36e5);
        }
      }
    }

    const closeRun = async () => {
      try {
        const res = await axios.put(`/run/closeRun/${idGlobal.value}`);
        const { ok, message, value } = res.data;
        if (ok) {
          isModalClose.value = false;
          makeToast(message);
          gettingData();
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
          gettingData();
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
    const editRun = (id) =>{
      // this.$inertia.get(route(`/part/${id}`));
      Inertia.get(`/part/${id}`)
    } 
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

      // IMAGES
      ImgResult,
      ImgPhotos,
      ImgEdit,
      ImgDelete,
      ImgClose,
      ImgNote,
      ImgReOpen,
      ImgEmail,
      ImgReportRun,
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