<template>
  <h1 class="text-center text-2xl p-5 font-bold text-[#3b4559]">Table Photos</h1>
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
        <td class="text-center">{{ photo.name }}</td>
        <td class="text-center">{{ photo.created_at.slice(0, 10) }}</td>
        <td class="text-center">
          {{
            calculateHours(
              photo.isEdit,
              photo.last_edit,
              photo.created_at,
              photo.hours
            )
          }}
        </td>
        <td class="text-center">{{ photo.description }}</td>
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
            <icon-edit />
          </button>
        </td>
        <td class="text-center">
          <button @click="openModalDeleteClick(photo.id)">
            <icon-delete />
          </button>
        </td>
      </tr>
    </tbody>
  </table>
  <modal :show="isModalPhotos" @close="closePhotosModalView">
    <photos-run
      :id="idPhoto"
      :photos="currentPhoto"
    />
  </modal>
   <!-- <photos-run
    v-if="photos"
    :isModalPhotos="isModalPhotos"
    :photos="currentPhoto"
    @closeModal="closePhotosModalView"
  /> -->
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
import FormPhotosUpdateVue from "./FormPhotosUpdate.vue";
import IconEdit from "@/assets/Icons/iconEdit.vue";
import IconDelete from "@/assets/Icons/iconDelete.vue";
export default {
  props: ["photos", "run"],
  components: {
    photosRun: PhotosRun,
    formPhotosCreate: FormPhotosCreateVue,
    formPhotosUpdate: FormPhotosUpdateVue,
    modal: Modal,
    IconEdit,
    IconDelete,
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
      idPhoto.value = id
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

    const calculateHours = (edit, lastDate, created_date, hours) => {
      if (edit) {
        const hoursEdited = Math.abs(new Date() - new Date(lastDate)) / 36e5;
        const hoursRounded = hoursEdited | 0;
        return Number(hours) + hoursRounded;
      } else {
        const hoursDiff = Math.abs(new Date() - new Date(created_date)) / 36e5;
        return hoursDiff | 0;
      }
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
      openModalEdit,
      openModalEditClick,
      calculateHours,
      photoItem,
      idPhoto,
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