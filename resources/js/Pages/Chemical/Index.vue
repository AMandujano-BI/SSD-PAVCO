<template>
  <app-layout title="Chemical">
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
      <table>
        <thead>
          <tr>
            <td>Name</td>
            <td>Type</td>
            <td>Edit</td>
            <td>Delete</td>
          </tr>
        </thead>
        <tr v-for="chemical in chemicalList" v-bind:key="chemical.id">
          <td class="border px-4 py-2">
            {{ chemical.name }}
          </td>

          <td class="border px-4 py-2">
            {{ chemical.type }}
          </td>
          <td class="border px-4 py-2">
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
          <td class="border px-4 py-2">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 cursor-pointer"
              viewBox="0 0 20 20"
              fill="#DD5145"
              @click="deleteChemicalModal(chemical.id)"
            >
              <path
                fill-rule="evenodd"
                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                clip-rule="evenodd"
              />
            </svg>
          </td>
        </tr>
      </table>
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

  </app-layout>
</template>

<script>
import axios from "axios";
import { required } from '@vuelidate/validators';
import AppLayout from '../../Layouts/AppLayout.vue';
import Modal from '../../Jetstream/Modal.vue';
import ConfirmationModal from '../../Jetstream/ConfirmationModal.vue';

export default {
  components: {
    AppLayout: AppLayout,
    modal: Modal,
    confirmationModal: ConfirmationModal
  },
    data() {
      return {
        form: {
          id: 0,
          name: "",
          type: "",
        },
        selected: "0",
        chemicalList: [],
        show: false,
        showDeleteModal: false,
        modalTitle: ''
      }
    },
    validations: {
      form: {
        name: {
          required,
        },
        validationGroup: [""],
      },
    },
    methods: {
      async getChemical(type) {
          const res = await axios.get(`/chemical/getChemicals/${type}`);
          this.chemicalList = res.data;
      },
      async submit() {
        if (this.form.name === "" | this.form.type === "" ) return
        let res
        if (this.form.id !== 0) {
          res = await axios.put(`/chemical/${this.form.id}`, this.form)
        } else {
          res = await axios.post('/chemical', this.form)
        }
        const { ok, message, value } = res.data;
        if (ok === true) {
          if(this.selected !== '0') {
            this.selected = this.form.type;
            this.getChemical(this.form.type);
          } else {
            this.filterChemicals();
          }
          this.reset()
        } else {
          console.log({res});
        }
      }
      ,
      filterChemicals() {
        this.getChemical(this.selected);
      },
      openModal() {
        this.modalTitle = 'Nuevo Chemical';
        this.show = true;
      },
      closeModal() {
        this.show = false;
        this.reset();
      },
      closeModalChemical() {
        this.showDeleteModal = false;
      },
      editChemical(chemical) {
        this.modalTitle = 'Editar Chemical';
        this.show = true;
        this.form.id = chemical.id;
        this.form.name = chemical.name;
        this.form.type = chemical.type;
      },
      deleteChemicalModal(id) {
        this.showDeleteModal = true;
        this.form.id = id;
      },
      async deleteChemical(id) {
        try {
          const resp = await axios.delete(`/chemical/${this.form.id}`)
          const { ok, message, value } = resp.data
          if (ok === true) {
            this.showDeleteModal = false
            this.getChemical(this.selected)
            this.reset();
          } else {
            console.log(message);
          }
        } catch (error) {
          console.log(error);
        }
      },
      reset() {
        this.showDeleteModal = false;
        this.show = false;
        this.form = {
          id: 0,
          name: "",
          type: "",
        };
      },
    },
    async mounted() {
      this.getChemical(0);
    },
}
</script>

<style>

</style>