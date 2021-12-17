<template>
  <table id="tableCompanies" class="display" style="width: 100%; height: 100%">
    <thead>
      <tr>
        <th>Company</th>
        <th>Distributor</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Zip</th>
        <th>Country</th>
        <th>Phone</th>
        <th>Fax</th>
        <th>Type</th>
        <th>Edit</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="company in companiesTable" :key="company.id">
        <td class="text-center">{{ company.name }}</td>
        <!-- <td class="text-center">{{company.id}}</td> -->
        <td class="text-center">{{ "distributor" }}</td>
        <td class="text-center">{{ company.address }}</td>
        <td class="text-center">{{ company.city }}</td>
        <td class="text-center">{{ company.state }}</td>
        <td class="text-center">{{ company.zip }}</td>
        <td class="text-center">{{ "country" }}</td>
        <td class="text-center">{{ company.phone }}</td>
        <td class="text-center">{{ company.fax }}</td>
        <td class="text-center">
          {{ company.customer == 0 ? "Customer" : "Distributor" }}
        </td>
        <td class="text-center">
          <button @click="openModalEditClick(company.id)">
            <img
              :src="require('@/assets/Icons/iconEdit/iconEdit.png').default"
              alt="iconDelete"
            />
          </button>
        </td>
        <td class="text-center">
          <button @click="openModalDeleteClick(company.id)">
            <img
              :src="require('@/assets/Icons/iconDelete/iconDelete.png').default"
              alt="iconDelete"
            />
          </button>
        </td>
      </tr>
    </tbody>
  </table>

  <confirmation-modal :show="showModalDelete">
    <template v-slot:title>
      <h1>Are you sure that delete this company?</h1>
    </template>
    <template v-slot:content>
      <div class="flex justify-center">
        <button
          class="bg-red-500 p-4 text-white rounded-md mr-4"
          @click="closeModalDelete"
        >
          Cancel
        </button>
        <button class="bg-green-500 p-4 text-white rounded-md">
          <!-- @click="deletePhoto()" -->
          Acept
        </button>
      </div>
    </template>
  </confirmation-modal>
</template>

<script>
import axios from "axios";
import { ref, nextTick } from "vue";
const $ = require("jquery");
import ConfirmationModal from "@/Jetstream/ConfirmationModal.vue";
import Modal from "@/Jetstream/Modal";
export default {
  components: {
    modal: Modal,
    confirmationModal: ConfirmationModal,
  },
  setup() {
    const companiesTable = ref([]);
    const showModalDelete = ref(false);

    const openModalEditClick = (id) => {
      console.log(id);
    };
    const openModalDeleteClick = (id) => {
      showModalDelete.value = true;
      console.log(id);
    };

    const getCompanies = async () => {
      await axios.get("companies");
    };
    const closeModalDelete = () => showModalDelete.value = false;
    const gettingData = async (type = 3) => {
      try {
        const res = await axios.get(`/company/getCompanies/${type}`);
        console.log(res.data);
        companiesTable.value = res.data;
        // $("#tableCompanies").DataTable().destroy();
        await generateDataTable();
      } catch (e) {
        console.log(e);
      }
    };
    const generateDataTable = () => {
      nextTick(() => {
        $("#tableCompanies").DataTable({
          scrollY: 350,
          ordering: true,
          bLengthChange: false,
          pageLength: 5,
        });
      });
    };

    gettingData();
    return {
      getCompanies,
      companiesTable,
      openModalEditClick,
      openModalDeleteClick,
      showModalDelete,
      closeModalDelete,
    };
  },
};
</script>

<style>
</style>