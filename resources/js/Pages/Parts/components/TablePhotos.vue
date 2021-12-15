<template>
  <h1 class="text-center font-bold text-2xl">Table Photos</h1>
  <button
    class="bg-blue-600 rounded w-[100] py-1 text-white px-3 mt-2"
    @click="openModalPhotosForm"
  >
    +
  </button>
  <table id="photosTable" class="display" style="width: 100%">
    <thead>
      <tr>
        <th>Name</th>
        <th>Date Added</th>
        <th>Hours</th>
        <th>Description</th>
        <th class="no-sort">View</th>
        <th class="no-sort">Report</th>
        <th class="no-sort">Edit</th>
        <th class="no-sort">Delete</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="photo in photosTable" :key="photo.id">
        <td>{{ photo.name }}</td>
        <td>{{ photo.created_at }}</td>
        <td>{{ photo.hours }}</td>
        <td>{{ photo.description }}</td>
        <td class="text-center">
          <!-- <button @click="showPhotos"> -->
          <button @click="showPhotos(photo.id)">
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
        <td class="text-center">{{ photo.report == 1 ? "Yes" : "No" }}</td>
        <td class="text-center">
          <button @click="openModalEditClick(photo.id)">
            <img :src="ImgEdit" alt="icon Edit" />
          </button>
        </td>
        <td class="text-center">
          <button @click="openModalDeleteClick(photo.id)">
            <!-- <button> -->
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
      </tr>
    </tbody>
  </table>

  <photos-run
    v-if="photos"
    :isModalPhotos="isModalPhotos"
    :photos="currentPhoto"
    @closeModal="closePhotosModalView"
  />
  <!-- @photoEdited="photoAdded" -->

  <!-- MODAL-->
  <modal :show="openModalPhotosCreate">
    <form-photos-create
      :run_id="run_id"
      @closeModal="closeModalPhotosCreate"
      @generateDataTable="generateDataTable"
      :photosTable="photosTable"
    />
  </modal>
  <modal :show="openModalEdit">
    <form-photos-update
      :run_id="run_id"
      @closeModal="closePhotosModal"
      @generateDataTable="generateDataTable"
      :photosTable="photosTable"
      :photoItem="photoItem"
    />
  </modal>
  <!-- CONFIRMATION MODAL -->
  <confirmation-modal :show="showModalDelete">
    <template v-slot:title>
      <h1>Are you sure that delete this photo?</h1>
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
          @click="deletePhoto()"
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
import { nextTick } from "vue";
import PhotosRun from "../../Run/components/PhotosRun.vue";
import { ref } from "vue";
import FormPhotosCreateVue from "./FormPhotosCreate.vue";
import Modal from "@/Jetstream/Modal";
import ConfirmationModal from "@/Jetstream/ConfirmationModal.vue";
import axios from "axios";
import useHelper from "@/composables/useHelper";
import ImgEdit from "@/assets/Icons/iconEdit/iconEdit.png";
import FormPhotosUpdateVue from "./FormPhotosUpdate.vue";
export default {
  props: ["photos", "run"],
  components: {
    photosRun: PhotosRun,
    formPhotosCreate: FormPhotosCreateVue,
    formPhotosUpdate: FormPhotosUpdateVue,
    modal: Modal,
    confirmationModal: ConfirmationModal,
  },
  setup(props) {
    const { photos, run } = props;
    const photosTable = ref(photos);
    const run_id = ref(run.id);
    const currentPhoto = ref([]);
    const isModalPhotos = ref(false);
    const idPhoto = ref(0);
    const openModalDelete = ref(false);
    const openModalEdit = ref(false);
    const showModalDelete = ref(false);
    const openModalPhotosCreate = ref(false);
    const photoItem = ref(null);
    const { makeToast } = useHelper();

    const generateDataTable = () => {
      nextTick(() => {
        $("#photosTable").DataTable({
          scrollY: 300,
          ordering: true,
          bLengthChange: false,
          bInfo: false,
          pageLength: 5,
        });
      });
    };
    const showPhotos = (id) => {
      const pic = photos.find((pic) => pic.id === id);
      currentPhoto.value.push(pic);
      isModalPhotos.value = true;
    };
    const closePhotosModalView = () => {
      currentPhoto.value = [];
      isModalPhotos.value = false;
    };
    const openModalPhotosForm = () => (openModalPhotosCreate.value = true);
    const closeModalPhotosCreate = () => (openModalPhotosCreate.value = false);
    const closeModalDelete = () => (showModalDelete.value = false);
    const openModalDeleteClick = (id) => {
      idPhoto.value = id;
      showModalDelete.value = true;
    };
    const deletePhoto = async () => {
      const res = await axios.delete(`/photo/${idPhoto.value}`);
      const { message, value, ok } = res.data;
      if (ok) {
        makeToast(message);
        closeModalDelete();
        photosTable.value = photosTable.value.filter(
          (item) => item.id !== value
        );
      } else {
        makeToast(message, "error");
      }
    };
    const openModalEditClick = (id) => {
      idPhoto.value = id;
      photoItem.value = photosTable.value.find((item) => item.id == id);
      openModalEdit.value = true;
    };

    generateDataTable();
    return {
      generateDataTable,
      isModalPhotos,
      currentPhoto,
      showPhotos,
      closePhotosModalView,
      openModalPhotosCreate,
      openModalPhotosForm,
      closeModalPhotosCreate,
      run_id,
      showModalDelete,
      closeModalDelete,
      openModalDelete,
      openModalDeleteClick,
      deletePhoto,
      photosTable,
      ImgEdit,
      openModalEdit,
      openModalEditClick,
      photoItem,
      closePhotosModal: () => (openModalEdit.value = false),
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