<template>
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
        <th class="no-sort">Delete</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="photo in photos" :key="photo.id">
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
        <td class="text-center">Report</td>
        <td class="text-center">
          <!-- <button @click="openModalDelete(part.id)"> -->
          <button>
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
    @closeModal="closePhotosModal"
    @photoEdited="photoAdded"
  />

  <modal :show="openModalPhotosCreate" @close="closeModalPhotosCreate">
    <form-photos-create />
  </modal>
</template>

<script>
const $ = require("jquery");
import dt from "datatables.net";
import { nextTick } from "vue";
import PhotosRun from "../../Run/components/PhotosRun.vue";
import { ref } from "vue";
import FormPhotosCreateVue from "./FormPhotosCreate.vue";
import Modal from "@/Jetstream/Modal";

export default {
  props: ["photos"],
  components: {
    photosRun: PhotosRun,
    formPhotosCreate: FormPhotosCreateVue,
    modal: Modal,
  },
  setup(props) {
    console.log(props.photos);
    const { photos } = props;
    let currentPhoto = ref([]);
    let isModalPhotos = ref(false);
    const openModalPhotosCreate = ref(false);

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
      console.log(pic);
      currentPhoto.value.push(pic);
      isModalPhotos.value = true;
    };
    const closePhotosModal = () => {
      currentPhoto.value = [];
      isModalPhotos.value = false;
    };
    const photoAdded = () => {
      console.log("photoAdded");
    };
    const openModalPhotosForm = () => {
      console.log("ehco");
      openModalPhotosCreate.value = true;
    };
    const closeModalPhotosCreate = () => {
      openModalPhotosCreate.value = false;
    };

    generateDataTable();
    return {
      generateDataTable,
      isModalPhotos,
      currentPhoto,
      showPhotos,
      closePhotosModal,
      photoAdded,
      openModalPhotosCreate,
      openModalPhotosForm,
      closeModalPhotosCreate,
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