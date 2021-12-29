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
        @generateTable="generateDataTable"
        @closeModal="closeModal"
      />
    </div>
  </modal>
  <div class="pt-5">
    <select class="w-full mb-5" @change="changeFilter" v-model="filterOption">
      <option value="4">All</option>
      <option value="0">Customer</option>
      <option value="1">Distributor</option>
    </select>
  </div>
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
        <th class="no-sort">Edit</th>
        <th class="no-sort">Delete</th>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="company in companiesTable"
        :key="company.id"
        class="cursor-pointer"
      >
        <td class="text-center">{{ company.name }}</td>
        <td class="text-center">
          {{ company.company_id == null ? "none" : company.children?.name }}
        </td>
        <td class="text-center">{{ company.address }}</td>
        <td class="text-center">{{ company.city }}</td>
        <td class="text-center">{{ company.state }}</td>
        <td class="text-center">{{ company.zip }}</td>
        <td class="text-center">{{ company.country?.name }}</td>
        <td class="text-center">{{ company.phone }}</td>
        <td class="text-center">{{ company.fax }}</td>
        <td class="text-center">
          {{ company.customer == 1 ? "Customer" : "Distributor" }}
        </td>
        <td class="text-center">
          <button @click="openModalEditClick(company.id)">
            <icon-edit />
          </button>
        </td>
        <td class="text-center">
          <button @click="openModalDeleteClick(company.id)">
            <icon-delete />
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
import { ref, nextTick, computed, watch, onMounted } from "vue";
const $ = require("jquery");
import ConfirmationModal from "@/Jetstream/ConfirmationModal.vue";
import Modal from "@/Jetstream/Modal";
import useHelper from "@/composables/useHelper";
import FormCompany from "./FormCompany.vue";
import IconEdit from "@/assets/Icons/iconEdit.vue";
import IconDelete from "@/assets/Icons/iconDelete.vue";
import { useStore } from "vuex";
export default {
  props: ["countries"],
  components: {
    modal: Modal,
    confirmationModal: ConfirmationModal,
    FormCompany,
    IconEdit,
    IconDelete,
  },
  setup() {
    const store = useStore();
    const companiesTable = ref(
      computed(() => store.state.companies.tableCompanies)
    );
    const showModalDelete = ref(false);
    const { makeToast } = useHelper();
    const openModalCompany = ref(false);
    const filterOption = ref("4");

    const openModalEditClick = async (id) => {
      const data = await store.dispatch("companies/getDistributors", id);
      store.commit("companies/setDistributors", data);
      store.commit("companies/setFormCompany", id);
      openModalCompany.value = true;
    };
    const openModalDeleteClick = (id) => {
      store.commit("companies/setFormCompany", id);
      showModalDelete.value = true;
    };

    const deleteCompany = async () => {
      try {
        const id = store.state.companies.form.id;
        const res = await store.dispatch("companies/deleteCompany", id);
        const { ok, message } = res.data;
        if (ok) {
          showModalDelete.value = false;
          makeToast(message);
          store.commit("companies/setFormCompany", 0);
          store.commit("companies/deleteItem", id);
          await generateDataTable();
        } else {
          makeToast(message, "error");
        }
      } catch (e) {
        makeToast("Error", "error");
        console.log(e);
      }
    };
    const closeModalDelete = () => (showModalDelete.value = false);
    const gettingData = async (type = 4) => {
      const data = await store.dispatch("companies/getCompanies", type);
      store.commit("companies/setDataTable", data);
      await generateDataTable();
    };
    const openModal = async () => {
      store.commit("companies/setFormCompany", 0);
      const data = await store.dispatch("companies/getDistributors", 0);
      store.commit("companies/setDistributors", data);
      openModalCompany.value = true;
    };

    const closeModal = () => {
      store.commit("companies/setFormCompany", 0);
      openModalCompany.value = false;
    };
    const changeFilter = async () => {
      await gettingData(filterOption.value);
    };
    const generateDataTable = () => {
      $("#tableCompanies").DataTable().destroy();
      nextTick(() => {
        $("#tableCompanies").DataTable({
          scrollY: 350,
          ordering: true,
          bLengthChange: false,
          pageLength: 5,
          language: {
            paginate: {
              next: `→`, // or '→'
              previous: `←`, // or '←'
            },
            info: "Showing results page _PAGE_ of _PAGES_",
          },
        });
      });
    };
    onMounted(() => {
      gettingData();
    });

    return {
      companiesTable,
      openModalEditClick,
      openModalDeleteClick,
      showModalDelete,
      closeModalDelete,
      deleteCompany,
      openModalCompany,
      openModal,
      closeModal,
      generateDataTable,
      filterOption,
      changeFilter,
    };
  },
};
</script>

<style>
</style>