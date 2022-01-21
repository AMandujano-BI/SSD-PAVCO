<template>
  <h1 class="text-center text-2xl p-5 font-bold text-[#3b4559]">
    Table Photos
  </h1>
  <div class="flex gap-5">
    <button @click="openModalPhotosForm"><icon-plus /></button>
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
        id="filterPhotosInputBot"
        placeholder="Search Photos..."
        autocomplete="off"
      />
    </div>
  </div>
  <div class="rounded-lg bg-white p-5 mt-2">
    <table id="photosTable" class="display responsive" style="width: 100%">
      <thead>
        <tr>
          <th>Name</th>
          <th>Date Added</th>
          <th>Hours</th>
          <th>Description</th>
          <th class="no-sort">Report</th>
          <th class="no-sort">View</th>
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
          <td class="text-center">{{ photo.report == 1 ? "Yes" : "No" }}</td>
          <td class="text-center">
            <!-- <button @click="showPhotos"> -->
            <button :itemId="photo.id" class="showPhotos" @click="showPhotos(photo.id)">
              <div
                class="
                  w-[25px]
                  h-[25px]
                  flex
                  justify-center
                  items-center
                  bg-[#084E93]
                "
              >
                <svg
                  width="16"
                  height="12"
                  viewBox="0 0 16 12"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <g fill="#FFF" fill-rule="nonzero">
                    <path
                      d="M14.626 0H.488A.488.488 0 0 0 0 .488v11.024c0 .27.218.488.488.488h14.138c.27 0 .488-.218.488-.488V.488A.488.488 0 0 0 14.626 0zm-.487.976v9.31l-3.904-4.151a.488.488 0 0 0-.71 0l-1.667 1.77L5.623 4.96a.488.488 0 0 0-.778 0l-3.87 5.12V.976H14.14zm-.645 10.048H1.484l3.751-4.962 2.194 2.893a.488.488 0 0 0 .744.04L9.88 7.18l3.614 3.843z"
                    />
                    <path
                      d="M10.1 5.058c.884 0 1.604-.72 1.604-1.604 0-.885-.72-1.604-1.605-1.604-.884 0-1.604.72-1.604 1.604 0 .884.72 1.604 1.604 1.604zm0-2.232a.629.629 0 1 1-.002 1.258.629.629 0 0 1 .001-1.258z"
                    />
                  </g>
                </svg>
              </div>
            </button>
          </td>
          <td class="text-center">
            <button 
            :itemId="photo.id"
            class="clickEdit" 
            @click="openModalEditClick(photo.id)">

              <icon-edit />
            </button>
          </td>
          <td class="text-center">
            <button :itemId="photo.id" class="clickDelete" @click="openModalDeleteClick(photo.id)">
              
              <icon-delete />
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <!-- <modal :show="isModalPhotos" @close="closePhotosModalView">
    <photos-run
      :id="idPhoto"
      :photos="currentPhoto"
    />
  </modal> -->
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
import { nextTick } from "vue";
import PhotosRun2 from "../../Run/components/PhotosRun2.vue";
import { ref } from "vue";
import FormPhotosCreateVue from "./FormPhotosCreate.vue";
import Modal from "@/Jetstream/Modal";
import ConfirmationModal from "@/Jetstream/ConfirmationModal.vue";
import axios from "axios";
import useHelper from "@/composables/useHelper";
import FormPhotosUpdateVue from "./FormPhotosUpdate.vue";
import IconEdit from "@/assets/Icons/iconEdit.vue";
const $ = require("jquery");
var dt = require("datatables.net");
import "datatables.net-responsive-dt";
import "datatables.net-rowreorder-dt";
import IconDelete from "@/assets/Icons/iconDelete.vue";
import IconPlus from "@/assets/Icons/iconPlus.vue";
export default {
  props: ["photos", "run"],
  components: {
    photosRun: PhotosRun2,
    formPhotosCreate: FormPhotosCreateVue,
    formPhotosUpdate: FormPhotosUpdateVue,
    modal: Modal,
    IconEdit,
    IconDelete,
    IconPlus,
    confirmationModal: ConfirmationModal,
  },
  setup(props) {
    
    const { photos, run } = props;
    const photosTable = ref([]);
    const run_id = ref(run.id);
    const currentPhoto = ref(null);
    const isModalPhotos = ref(false);
    const idPhoto = ref(0);
    const openModalDelete = ref(false);
    const openModalEdit = ref(false);
    const showModalDelete = ref(false);
    const openModalPhotosCreate = ref(false);
    const photoItem = ref(null);
    const { makeToast } = useHelper();

    const gettData = async () => {
      const res = await axios.get(`/photo/getPhotosByRun/${run_id.value}`);
      photosTable.value = res.data;
      generateDataTable()
    };

    $(document).ready(function () {
      $("#filterPhotosInputBot")
        .off()
        .keyup(function () {
          $("#photosTable").DataTable().search(this.value).draw();
        });
    });
    const generateDataTable = () => {
      nextTick(() => {
        $("#photosTable").DataTable({
          ordering: true,
          bLengthChange: false,
          pageLength: 5,
          responsive: true,
           columnDefs: [
            {
              defaultContent: "-",
              targets: "_all",
            },
          ],
          language: {
            paginate: {
              next: `<svg class="arrow_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="15" height="14" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><g transform="rotate(270 10 10)"><path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="white"/></g></svg>`, // or '→'
              previous: `<svg class="arrow_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="15" height="14" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><g transform="rotate(90 10 10)"><path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="white"/></g></svg>`, // or '←'
            },
            info: "Showing results _START_ to _END_ from _TOTAL_",
          },
           drawCallback: function () {
            $("#photosTable").on("click", "[class*=showPhotos]", function (e) {
              showPhotos(e.currentTarget.attributes[0].value);
            });
             $("#photosTable").on("click", "[class*=clickEdit]", function (e) {
              const id = e.currentTarget.attributes[0].value
              openModalEditClick(id)
            });
             $("#photosTable").on("click", "[class*=clickDelete]", function (e) {
             const id = e.currentTarget.attributes[0].value
             openModalDeleteClick(id)
            });
           }
        });
      });
    };
    const showPhotos = (id) => {
      const pic = photosTable.value.find((pic) => pic.id == id);
      currentPhoto.value = pic;
      idPhoto.value = id;
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
      console.log(id)
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


    gettData();
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
      dt,
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