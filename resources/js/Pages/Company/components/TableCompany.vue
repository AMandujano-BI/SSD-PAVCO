<template>
  <button
    @click="openModal"
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
  >
    +
  </button>
  <modal :show="openModalCompany">
    <div class="p-5">
      <form-company
        :countries="countries"
        :distributors="distributors"
        @closeModal="closeModal"
      />
    </div>
  </modal>
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
        <td class="text-center">{{ "distributor" }}</td>
        <td class="text-center">{{ company.address }}</td>
        <td class="text-center">{{ company.city }}</td>
        <td class="text-center">{{ company.state }}</td>
        <td class="text-center">{{ company.zip }}</td>
        <td class="text-center">{{ company.country.name }}</td>
        <td class="text-center">{{ company.phone }}</td>
        <td class="text-center">{{ company.fax }}</td>
        <td class="text-center">
          {{ company.customer == 0 ? "Customer" : "Distributor" }}
        </td>
        <td class="text-center">
          <button @click="openModalEditClick(company.id)">
            <icon-edit/>
          </button>
        </td>
        <td class="text-center">
          <button @click="openModalDeleteClick(company.id)">
            <icon-delete/>
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
        <button
          class="bg-green-500 p-4 text-white rounded-md"
          @click="deleteCompany()"
        >
          Acept
        </button>
      </div>
    </template>
  </confirmation-modal>
</template>

<script>
import dt from "datatables.net";
import axios from "axios";
import { ref, nextTick } from "vue";
const $ = require("jquery");
import ConfirmationModal from "@/Jetstream/ConfirmationModal.vue";
import Modal from "@/Jetstream/Modal";
import useHelper from "@/composables/useHelper";
import FormCompany from "./FormCompany.vue";
import IconEdit from "@/assets/Icons/iconEdit.vue";
import IconDelete from "@/assets/Icons/iconDelete.vue";
export default {
  props: ["countries", "distributors"],
  components: {
    modal: Modal,
    confirmationModal: ConfirmationModal,
    FormCompany,
    IconEdit,
    IconDelete,
  },
  setup() {
    const companiesTable = ref([]);
    const idCompany = ref(0);
    const showModalDelete = ref(false);
    const { makeToast } = useHelper();
    const openModalEditClick = (id) => {
      console.log(id);
    };
    const openModalDeleteClick = (id) => {
      showModalDelete.value = true;
      idCompany.value = id;
    };

    const getCompanies = async () => {
      await axios.get("companies");
    };
    const deleteCompany = async () => {
      try {
        const res = await axios.delete(`/company/${idCompany.value}`);
        console.log(res.data);
        const { ok, message, value } = res.data;
        if (ok) {
          idCompany.value = 0;
          showModalDelete.value = false;
          makeToast(message);
        } else {
          makeToast(message, "error");
        }
      } catch (e) {
        makeToast("Error", "error");
        console.log(e);
      }
    };
    const closeModalDelete = () => (showModalDelete.value = false);
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
    const openModalCompany = ref(false);
    const openModal = () => {
      openModalCompany.value = true;
    };

    const closeModal = () => {
      openModalCompany.value = false;
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
      deleteCompany,
      openModalCompany,
      openModal,
      closeModal,
    };
  },
};
</script>

<style>
</style>