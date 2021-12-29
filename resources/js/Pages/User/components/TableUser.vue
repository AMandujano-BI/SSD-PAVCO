<template>
  <button
    @click="openModalForm"
  >
  <icon-plus/>
  </button>
  <div class="pt-5">
    <select class="w-full mb-5" @change="changeFilter" v-model="filterOption">
      <option value="0" selected>All</option>
      <option :value="rol.value" v-for="rol in rols" :key="rol.id">
        {{ rol.label }}
      </option>
    </select>
  </div>

  <div class="rounded-lg shadow-lg p-5">
    <table id="tableUsers" class="display" style="width: 100%; height: 100%">
      <thead>
        <tr>
          <th>Username</th>
          <th>Name</th>
          <th>Company</th>
          <th>Email Address</th>
          <th>User Type</th>
          <th class="no-sort">Reset Password</th>
          <th class="no-sort">Edit</th>
          <th class="no-sort">Delete</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in usersTable" :key="user.id">
          <td class="text-center">{{ user.username }}</td>
          <td class="text-center">{{ user.name }} {{ user.lastname || "" }}</td>
          <td class="text-center">{{ user.company?.name }}</td>
          <td class="text-center">
            <a :href="`mailto:${user.email}`">{{ user.email }}</a>
          </td>
          <td
            class="
              text-center
              justify-center
              w-full
              grid grid-cols-[repeat(auto-fit,minmax(100px,1fr))]
              gap-2
              items-center
              h-full
            "
          >
            <span
              class="
                bg-primary-300
                max-w-[150px]
                font-bold
                text-sm
                rounded-md
                p-2
                mx-1
                break-words
              "
              v-for="rol in user.rols"
              :key="rol.id"
              >{{ rol.name }}</span
            >
          </td>
          <td class="text-center">
            <button @click="openModalResetPassword(user.username)">
              <icon-reset />
            </button>
          </td>
          <td class="text-center">
            <button @click="openModalEditClick(user.id)">
              <icon-edit />
            </button>
          </td>
          <td class="text-center">
            <button @click="openModalDeleteClick(user.id)">
              <icon-delete />
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- MODAL RESET PASSWORD -->
  <modal :show="modalResetPassword">
    <div class="p-5">
      <form-reset @closeModal="closeModalResetPassword" :username="username" />
    </div>
  </modal>

  <!-- MODAL FORM -->
  <modal :show="openModal">
    <div class="p-5">
      <form-user
        :companies="companies"
        :rols="rols"
        @closeModal="closeModalForm"
        @generateTable="generateDataTable"
      />
    </div>
  </modal>
  <!-- CONFIRMATION MODal -->
  <confirmation-modal :show="showModalDelete">
    <template v-slot:title>
      <h1>Are you sure that delete this User?</h1>
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
          @click="deleteUser()"
        >
          Acept
        </button>
      </div>
    </template>
  </confirmation-modal>
</template>

<script>
import ModalVue from "@/Jetstream/Modal.vue";
import { computed, nextTick, onMounted, ref } from "vue";
import FormUserVue from "./FormUser.vue";
import FormResetPasswordVue from "./FormResetPassword.vue";
import { useStore } from "vuex";
const $ = require("jquery");
import dt from "datatables.net";
import IconEdit from "@/assets/Icons/iconEdit.vue";
import IconDelete from "@/assets/Icons/iconDelete.vue";
import IconReset from "@/assets/Icons/iconResetPassword.vue";
import ConfirmationModal from "@/Jetstream/ConfirmationModal.vue";
import Multiselect from "@vueform/multiselect";
import useHelper from "@/composables/useHelper";
import IconPlus from "@/assets/Icons/iconPlus.vue";
export default {
  props: ["companies", "rols"],
  components: {
    Modal: ModalVue,
    FormUser: FormUserVue,
    confirmationModal: ConfirmationModal,
    IconEdit,
    IconDelete,
    IconReset,
    IconPlus,
    FormReset: FormResetPasswordVue,
    multiSelect: Multiselect,
  },
  setup(props) {
    const username = ref("");
    const openModal = ref(false);
    const store = useStore();
    const { makeToast } = useHelper();
    const showModalDelete = ref(false);
    const modalResetPassword = ref(false);
    const filterOption = ref(0);
    const usersTable = ref(computed(() => store.state.users.tableUsers));

    const changeFilter = async () => {
      await gettingData(filterOption.value);
    };
    const gettingData = async (type = 0) => {
      const data = await store.dispatch("users/getUsers", type);
      store.commit("users/setDataTable", data);
      await generateDataTable();
    };
    const generateDataTable = () => {
      $("#tableUsers").DataTable().destroy();
      nextTick(() => {
        $("#tableUsers").DataTable({
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
    const openModalDeleteClick = (id) => {
      store.commit("users/setFormUser", id);
      showModalDelete.value = true;
    };
    const openModalForm = () => {
      store.commit("users/setFormUser", 0);
      openModal.value = true;
    };
    const openModalEditClick = (id) => {
      store.commit("users/setFormUser", id);
      openModal.value = true;
    };
    const openModalResetPassword = (user) => {
      modalResetPassword.value = true;
      username.value = user;
    };
    const closeModalResetPassword = () => {
      modalResetPassword.value = false;
      username.value = null;
    };
    const deleteUser = async () => {
      try {
        const id = store.state.users.form.id;
        const res = await store.dispatch("users/deleteUser", id);
        const { ok, message, value } = res.data;
        if (ok) {
          showModalDelete.value = false;
          makeToast(message);
          store.commit("users/setFormUser", 0);
          store.commit("users/deleteItem", id);
          await generateDataTable();
        } else {
          makeToast(message, "error");
        }
      } catch (e) {
        makeToast("Error", "error");
        console.log(e);
      }
    };

    onMounted(() => {
      gettingData();
    });
    return {
      openModal,
      showModalDelete,
      openModalForm,
      closeModalForm: () => (openModal.value = false),
      usersTable,
      generateDataTable,
      openModalDeleteClick,
      openModalEditClick,
      deleteUser,
      modalResetPassword,
      openModalResetPassword,
      closeModalResetPassword,
      closeModalDelete: () => (showModalDelete.value = false),
      username,
      filterOption,
      changeFilter,
    };
  },
};
</script>

<style>
</style>