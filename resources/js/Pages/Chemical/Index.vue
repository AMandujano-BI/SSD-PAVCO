<template>
  <app-layout title="Chemicals">
      <div class="container p-9 mx-auto">
        <p class="text-3xl text-center font-bold p-5">List of Chemicals</p>
        <button
          @click="openModal"
          class="bg-blue-600 px-4 py-2 text-white rounded-md mb-2"
        > +
        </button>
        <div>
          Filter
          <select class="w-full" v-model="selected" @change="filterChemicals">
            <option value="0" selected>All Chemical</option>
            <option value="1" >Plating</option>
            <option value="2">Chromate</option>
            <option value="3">TopCoat</option>
            <option value="4">Secondary TopCoat</option>
          </select>
        </div>
    <div class="mt-10">
      <div class="rounded-lg shadow-lg p-5">
      <table id="chemicalTable" class="display" style="width: 100%; height: 100%">
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
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 cursor-pointer"
              viewBox="0 0 20 20"
              fill="#1147CE"
              @click="editChemical(chemical)"
            >
              <path
                d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"
              />
              <path
                fill-rule="evenodd"
                d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                clip-rule="evenodd"
              />
            </svg>
          </td>
          <td class="border text-center">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 cursor-pointer"
              viewBox="0 0 20 20"
              fill="#DD5145"
              @click="deleteChemicalModal(chemical)"
            >
              <path
                fill-rule="evenodd"
                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                clip-rule="evenodd"
              />
            </svg>
          </td>
        </tr>
        </tbody>
      </table>
      </div>
    </div>

    <modal :show="show">
      <form @submit.prevent="submit" class="container mx-auto p-5">
      <button @click="closeModal" class="bg-red-600 text-white p-2 rounded-md">
        X
      </button>
        <p class="text-xl font-bold text-center">{{modalTitle}}</p>
        <div>
          <div>
            <label for="">Name</label>
            <input type="text" v-model="form.name" class="w-full py-4 px-5" />
          </div>
          <div>
            <label for="">Type</label>
            <select v-model="form.type" class="w-full">
              <option value="1" selected>Plating</option>
              <option value="2">Chromate</option>
              <option value="3">TopCoat</option>
              <option value="4">Secondary TopCoat</option>
            </select>
          </div>
        </div>
        <button class="bg-green-500 rounded-sm p-5 font-bold text-white mt-5 block w-full">
          Save
        </button>
      </form>
    </modal>
    <confirmation-modal :show="showDeleteModal">
      <template v-slot:title>
        <h1>Are you sure that delete this chemical?</h1>
      </template>
      <template v-slot:content>
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
      </template>
    </confirmation-modal>
    </div>
  </app-layout>
</template>

<script>
import { ref, nextTick, onMounted } from "vue";
import dt from "datatables.net";
import axios from "axios";
import { required } from '@vuelidate/validators';
import AppLayout from '../../Layouts/AppLayout.vue';
import Modal from '../../Jetstream/Modal.vue';
import ConfirmationModal from '../../Jetstream/ConfirmationModal.vue';
import useHelper from "@/composables/useHelper";

const $ = require("jquery");

export default {
  components: {
    AppLayout: AppLayout,
    modal: Modal,
    confirmationModal: ConfirmationModal,

  },
  setup() {
    const { makeToast } = useHelper();
    let selected = ref('0')
    let chemicalList = ref([])
    let show = ref(false)
    let showDeleteModal = ref(false)
    let modalTitle = ref('')
    let form = ref({
      id: 0,
      name: '',
      type: ''
    })

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
        const res = await axios.get(`/chemical/getChemicals/${type}`)
        chemicalList.value = res.data
        $("#chemicalTable").DataTable().destroy();
        generateDataTable()
      } catch (error) {
        makeToast('There is an error to load chemicals. Try again please.', 'error')
      }
    }

    const submit = async () => {
        if (form.name === "" | form.type === "" ) return
        let res
        if (form._value.id !== 0) {
          res = await axios.put(`/chemical/${form._value.id}`, form._value)
        } else {
          res = await axios.post('/chemical', form._value)
        }
        const { ok, message, value } = res.data;
        makeToast(message)
        if (ok === true) {
          if(selected._value !== '0') {
            selected._value = form._value.type;
            getChemical(form._value.type);
          } else {
            filterChemicals();
          }
          reset()
        } else {
          makeToast('There is an error. Try again please', 'error')
        }
      }

      const filterChemicals = () => {
        getChemical(selected._value);
      }
      const openModal = () => {
        modalTitle.value = 'Nuevo Chemical';
        show.value = true;
      }
      const closeModal = () => {
        show.value = false;
        reset();
      }
      const closeModalChemical = () => {
        showDeleteModal.value = false;
      }

      const editChemical = (chemical) => {
        modalTitle.value = 'Editar Chemical';
        show.value = true;
        form._value.id = chemical.id;
        form._value.name = chemical.name;
        form._value.type = chemical.type;
      }
      const deleteChemicalModal = (chemical) => {
        showDeleteModal.value = true;
        form._value.id = chemical.id;
        form._value.type = chemical.type;
      }

      const deleteChemical = async (id) => {
        try {
          const resp = await axios.delete(`/chemical/${form._value.id}`)
          const { ok, message, value } = resp.data
          makeToast(message)
          if (ok === true) {
            showDeleteModal.value = false
            if(selected._value !== '0') {
              selected._value = form._value.type;
              getChemical(form._value.type);
            } else {
              filterChemicals();
            }
            reset();
          } else {
            makeToast('There is an error to delete', 'error')
          }
        } catch (error) {
          makeToast('There is an error to delete', 'error')
        }
      }

      const generateDataTable = () => {
        nextTick(() => {
          $("#chemicalTable").DataTable();
        });
      };

      const reset = () => {
        showDeleteModal.value = false;
        show.value = false;
        form._value.id = 0;
        form._value.name = '';
        form._value.type = '';
      }

    onMounted(() => {
      getChemical(0)
    })
    

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
      reset
    }
  }
}
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
