<template>
  <div class="container p-9">
    <select class="w-full mb-5" @change="changeFilter" v-model="filterOption">
      <option value="3">All</option>
      <option value="0">Active</option>
      <option value="1">Complete</option>
    </select>
    <table id="activeRuns" class="display" style="width: 100%">
      <thead>
        <tr>
          <th>StartDate</th>
          <th>Customer</th>
          <th>Run #</th>
          <th>Method</th>
          <th>Status</th>
          <th>Hrs</th>
          <th>Photos</th>
          <th>Results</th>
          <th>Edit</th>
          <th>Delete</th>
          <th>Close</th>
          <th>Re-Open</th>
          <th>Notes</th>
          <th>Reports</th>
          <th>Email</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="run in runs" :key="run.id">
          <td class="text-center">{{ run.startDate }}</td>
          <td class="text-center">{{ run.user_id }}</td>
          <td class="text-center">{{ run.id }}</td>
          <td class="text-center">{{ run.plate_methods_id }}</td>
          <td class="text-center">
            <span v-if="run.status == 0">Active</span>
            <span v-if="run.status == 1">Complete</span>
          </td>
          <td class="text-center">{{ run.status }}</td>
          <!-- Photos action -->
          <td class="text-center">
            <button @click="showPhotos(run.id)">
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
                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
              </svg>
            </button>
          </td>
          <!-- Result action -->
          <td class="text-center">
            <button @click="showResults(run)">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                />
              </svg>
            </button>
          </td>
          <!-- Edit action -->
          <td class="text-center">
            <button @click="editRun(run.id)">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="#2563EB"
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
          <!-- Delete action -->
          <td class="text-center">
            <button @click="showDelete(run.id)">
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
          <!-- Close action -->
          <td class="text-center">
            <button @click="showClose(run.id)">
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
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </button>
          </td>
          <!-- ReOpen action -->
          <td class="text-center">
            <button @click="showReOpen(run.id)">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  fill-rule="evenodd"
                  d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v1.5a1.5 1.5 0 01-3 0V6z"
                  clip-rule="evenodd"
                />
                <path
                  d="M6 12a2 2 0 012-2h8a2 2 0 012 2v2a2 2 0 01-2 2H2h2a2 2 0 002-2v-2z"
                />
              </svg>
            </button>
          </td>
          <!-- Notes action -->
          <td class="text-center">
            <button @click="showNotes(run.id)">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                viewBox="0 0 20 20"
                fill="#DA9C14"
              >
                <path
                  fill-rule="evenodd"
                  d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                  clip-rule="evenodd"
                />
              </svg>
            </button>
          </td>
          <!-- Report action -->
          <td class="text-center">
            <button @click="reportRun(run.id)">
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
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="#7DBB52"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                />
              </svg>
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <modal :show="isModalPhotos">
      <div class="container mx-auto p-5">
        <button
          @click="closePhotosModal"
          class="bg-red-600 text-white p-2 rounded-md"
        >
          X
        </button>
        <p class="text-xl font-bold text-center">Pavco SSD Photo Viewer</p>

        <div class="mt-5" v-if="run.photos.length > 1">
          <swiper
            :modules="modules"
            :slides-per-view="1"
            :space-between="50"
            navigation
            :pagination="{ clickable: true }"
            @swiper="onSwiper"
            @slideChange="onSlideChange"
          >
            <swiper-slide v-for="photo in run.photos" :key="photo.id">
              <div>
                <p>Filename: {{ photo.name }}</p>
                <ul>
                  <li><strong>Name: </strong>{{ photo.name }}</li>
                  <li><strong>Date added: </strong>{{ photo.created_at }}</li>
                  <li><strong>Hours: </strong>{{ photo.hours }}</li>
                  <li><strong>Description: </strong>{{ photo.description }}</li>
                </ul>
                <img :src="photo.image" :alt="photo.name" />
              </div>
            </swiper-slide>
          </swiper>
        </div>
        <div v-else>
          <p class="text-center my-20">There is no images</p>
        </div>
      </div>
    </modal>

    <modal
      :show="isModalResults"
      :maxWidth="modalWidthDetail"
      @close="closeResultsModal"
    >
      <div class="container mx-auto p-5 relative">
        <!-- <button
          @click="closeResultsModal"
          class="right-10 absolute"
        >
          X
        </button> -->
        <p class="text-xl font-bold text-center">Run results</p>
        <p class="p-4">Enter results for each part in this run</p>
        <hr />
        <div>
          <h3>Run information</h3>
          <ul v-if="runDetail" class="shadow-lg rounded-md p-5 mb-5">
            <li><strong>Run: </strong> {{ runDetail.id }}</li>
            <li><strong>Customer: </strong> {{ runDetail.user_id }}</li>
            <li><strong>Start Date: </strong> {{ runDetail.created_at }}</li>
            <li><strong>Description: </strong> {{ runDetail.description }}</li>
          </ul>
        </div>
        <hr />
        <h3>Parts Data</h3>
        <table>
          <thead>
            <tr>
              <th>Part description</th>
              <th>Plate Type</th>
              <th>Chromate</th>
              <th>Topcoat</th>
              <th>Secondary Topcoat</th>
              <th>White Salts</th>
              <th>Red Rust</th>
              <th>Notes</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="run in runDetail.parts" :key="run.id">
              <td>{{ run.id }}</td>
              <td>{{ run.plate_type.name }}</td>
              <td>{{ run.chromate.name + "  -- " + run.primaryPer + " %" }}</td>
              <td>{{ run.top_coat.name + "  -- " + run.topCoatPer + " %" }}</td>
              <td>{{ run.coat.name + "  -- " + run.coatPer + " %" }}</td>
              <td>
                <input type="checkbox" value="false" />
              </td>
              <td>
                <input type="checkbox" value="false" />
              </td>
              <td>
                <button>
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
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                    />
                  </svg>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </modal>

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
import { Swiper, SwiperSlide } from "swiper/vue";
import { ref, nextTick } from "vue";
import Modal from "../../../Jetstream/Modal.vue";
import ConfirmationModal from "../../../Jetstream/ConfirmationModal.vue";
import useHelper from "@/composables/useHelper";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import NotesRun from "./NotesRun.vue";

const $ = require("jquery");

export default {
  props: ["data"],
  components: {
    modal: Modal,
    confirmationModal: ConfirmationModal,
    swiper: Swiper,
    swiperSlide: SwiperSlide,
    notesRun: NotesRun,
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
      // console.log(swiper);
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
    const showResults = (run) => {
      // findRun(id);
      console.log(run);
      runDetail.value = run;
      isModalResults.value = true;
    };
    const closeResultsModal = () => {
      isModalResults.value = false;
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

    const closeRun = async () => {
      try {
        const res = await axios.put(`/run/closeRun/${idGlobal.value}`);
        const { ok, message, value } = res.data;
        if (ok) {
          isModalClose.value = false;
          makeToast(message);
          gettingData();
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
        runs.value = res.data.data;
        generateDataTable();
        console.log(runs.value);
      } catch (e) {
        console.log(e);
      }
    };
    const changeFilter = async () => {
      gettingData(filterOption.value);
    };
    const generateDataTable = () => {
      nextTick(() => {
        $("#activeRuns").DataTable();
      });
    };
    const editRun = (id) => (window.location.href = `/part/${id}`);
    const reportRun = (id) => {
      window.location.href = `/run/download/${id}`;
      // try {
      //   const response = axios.get('/run/download');
      //   console.log(response);
      // } catch (error) {
      //   console.log(error);
      // }
    };
    gettingData(3);

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
    };
  },
};
</script>

<style>
</style>