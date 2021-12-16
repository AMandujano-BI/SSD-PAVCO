<template>
  <app-layout title="Chemicals">
    <div class="container p-9 mx-auto">
      <p class="text-3xl text-center font-bold p-5">List of Chemicals</p>
      <button
        @click="openModal"
        class="bg-primary px-4 py-2 text-white rounded-md mb-2"
      >
        +
      </button>
      <div>
        Filter
        <select class="w-full" v-model="selected" @change="filterChemicals">
          <option value="0" selected>All Chemical</option>
          <option value="1">Plating</option>
          <option value="2">Chromate</option>
          <option value="3">TopCoat</option>
          <option value="4">Secondary TopCoat</option>
        </select>
      </div>
      <div class="mt-10">
        <div class="rounded-lg shadow-lg p-5">
          <table
            id="chemicalTable"
            class="display"
            style="width: 100%; height: 100%"
          >
            <thead>
              <tr>
                <td>Name</td>
                <td>Type</td>
                <td class="no-sort">Edit</td>
                <td class="no-sort">Delete</td>
              </tr>
            </thead>
            <tbody>
              <tr v-for="chemical in chemicalList" v-bind:key="chemical.id">
                <td class="border px-4 py-2">
                  {{ chemical.name }}
                </td>

                <td class="border px-4 py-2">
                  <span v-if="chemical.type == '1'">Plating</span>
                  <span v-if="chemical.type == '2'">Chromate</span>
                  <span v-if="chemical.type == '3'">TopCoat</span>
                  <span v-if="chemical.type == '4'">Secondary</span>
                </td>
                <td class="border text-center">
                  <button @click="editChemical(chemical)">
                    <img
                      :src="
                        require('@/assets/Icons/iconEdit/iconEdit.png').default
                      "
                      alt="iconEdit"
                    />
                  </button>
                </td>
                <td class="border text-center">
                  <button @click="deleteChemicalModal(chemical)">
                    <img
                      :src="
                        require('@/assets/Icons/iconDelete/iconDelete.png')
                          .default
                      "
                      alt="iconDelete"
                    />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <modal :show="show">
        <form @submit.prevent="submit" class="container mx-auto p-5">
          <p class="text-xl font-bold text-center">{{ modalTitle }}</p>
          <div>
            <div>
              <label for="">Name</label>
              <input type="text" v-model="form.name" class="w-full py-4 px-5" />
            </div>
            <div>
              <label for="">Type</label>
              <multi-select
                :options="typesArray"
                class="w-full"
                v-model="form.type"
                :searchable="true"
                placeholder="Select Type"
              />
            </div>
          </div>
          <div class="flex flex-col md:flex-row md:justify-between w-full md:gap-4 pt-16">
            <button
              type="button"
              class="bg-red-600  hover:bg-red-800 rounded-sm w-full py-5 text-white px-3 mt-5"
              @click="closeModal"
            >
              Cancel
            </button>
            <button
              class="
                bg-primary
                rounded-sm
                p-5
                font-bold
                text-white
                mt-5
                block
                hover:bg-primary-600
                w-full
              "
            >
              Save
            </button>
          </div>
        </form>
      </modal>
      <confirmation-modal :show="showDeleteModal">
        <template v-slot:title>
          <h1>Are you sure that delete this chemical?</h1>
        </template>
        <template v-slot:content>
          <div class="flex justify-center">
            <button
              class="bg-red-500 p-4 text-white rounded-md mr-4"
              @click="closeModalChemical"
            >
              Cancel
            </button>
            <button
              class="bg-green-500 p-4 text-white rounded-md"
              @click="deleteChemical"
            >
              Acept
            </button>
          </div>
        </template>
      </confirmation-modal>
    </div>
  </app-layout>
</template>

<script>
import { ref, nextTick, onMounted } from "vue";
import dt from "datatables.net";
import axios from "axios";
import { required } from "@vuelidate/validators";
import AppLayout from "../../Layouts/AppLayout.vue";
import Modal from "../../Jetstream/Modal.vue";
import ConfirmationModal from "../../Jetstream/ConfirmationModal.vue";
import useHelper from "@/composables/useHelper";
import Multiselect from "@vueform/multiselect";
const $ = require("jquery");

export default {
  components: {
    AppLayout: AppLayout,
    modal: Modal,
    confirmationModal: ConfirmationModal,
    multiSelect: Multiselect,
  },
  setup() {
    const { makeToast } = useHelper();
    let selected = ref("0");
    let chemicalList = ref([]);
    let show = ref(false);
    let showDeleteModal = ref(false);
    let modalTitle = ref("");
    let form = ref({
      id: 0,
      name: "",
      type: "",
    });

    const rules = {
      name: {
        required,
      },
      name: {
        required,
      },
    };

    const getChemical = async (type) => {
      try {
        const res = await axios.get(`/chemical/getChemicals/${type}`);
        chemicalList.value = res.data;

        $("#chemicalTable").DataTable().destroy();
        generateDataTable();
      } catch (error) {
        console.log(error);
        makeToast(
          "There is an error to load chemicals. Try again please.",
          "error"
        );
      }
    };
    const typesArray = ref([
      { value: 1, label: "Plating" },
      { value: 2, label: "Chromate" },
      { value: 3, label: "TopCoat" },
      { value: 4, label: "Secondary TopCoat" },
    ]);

    const submit = async () => {
      if ((form.name === "") | (form.type === "")) return;
      let res;
      if (form._value.id !== 0) {
        res = await axios.put(`/chemical/${form._value.id}`, form._value);
      } else {
        res = await axios.post("/chemical", form._value);
      }
      const { ok, message, value } = res.data;
      makeToast(message);
      if (ok === true) {
        if (selected._value !== "0") {
          selected._value = form._value.type;
          getChemical(form._value.type);
        } else {
          filterChemicals();
        }
        reset();
      } else {
        makeToast("There is an error. Try again please", "error");
      }
    };

    const filterChemicals = () => {
      getChemical(selected._value);
    };
    const openModal = () => {
      modalTitle.value = "New Chemical";
      show.value = true;
    };
    const closeModal = () => {
      show.value = false;
      reset();
    };
    const closeModalChemical = () => {
      showDeleteModal.value = false;
    };

    const editChemical = (chemical) => {
      modalTitle.value = "Update Chemical";
      show.value = true;
      form._value.id = chemical.id;
      form._value.name = chemical.name;
      form._value.type = chemical.type;
    };
    const deleteChemicalModal = (chemical) => {
      showDeleteModal.value = true;
      form._value.id = chemical.id;
      form._value.type = chemical.type;
    };

    const deleteChemical = async (id) => {
      try {
        const resp = await axios.delete(`/chemical/${form._value.id}`);
        const { ok, message, value } = resp.data;
        if (ok === true) {
          makeToast(message);
          showDeleteModal.value = false;
          if (selected._value !== "0") {
            selected._value = form._value.type;
            getChemical(form._value.type);
          } else {
            filterChemicals();
          }
          reset();
        } else {
          makeToast(message, "error");
        }
      } catch (error) {
        makeToast(message, "error");
      }
    };

    const generateDataTable = () => {
      nextTick(() => {
        $("#chemicalTable").DataTable({
          scrollY: 350,
          ordering: true,
          bLengthChange: false,
          pageLength: 5,
        });
      });
    };

    const reset = () => {
      showDeleteModal.value = false;
      show.value = false;
      form._value.id = 0;
      form._value.name = "";
      form._value.type = "";
    };

    onMounted(() => {
      getChemical(0);
    });

    return {
      selected,
      chemicalList,
      show,
      showDeleteModal,
      modalTitle,
      form,
      getChemical,
      submit,
      filterChemicals,
      openModal,
      closeModal,
      closeModalChemical,
      editChemical,
      deleteChemicalModal,
      deleteChemical,
      generateDataTable,
      reset,
      typesArray,
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
