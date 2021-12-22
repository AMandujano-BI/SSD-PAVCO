<template>
  <button
    @click="openModalForm"
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

  <div class="rounded-lg shadow-lg p-5">
    <table id="tableUsers" class="display" style="width: 100%; height: 100%">
      <thead>
        <tr>
          <th>Username</th>
          <th>First Name</th>
          <th>Last Name</th>
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
          <td class="text-center">{{ user.name }}</td>
          <td class="text-center">{{ user.lastname }}</td>
          <td class="text-center">{{ user.company?.name }}</td>
          <td class="text-center">
            <a :href="`mailto:${user.email}`">{{ user.email }}</a>
          </td>
          <td class="text-center">{{ "type" }}</td>
          <td class="text-center">
            <button @click="openModalResetPassword(user.id)">
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
  <modal :show="modalResetPassword" @close="closeModalResetPassword">
    <div class="p-5">
      <form-reset @closeModal="closeModalResetPassword" />
    </div>
  </modal>

  <!-- MODAL FORM -->
  <modal :show="openModal">
    <div class="p-5">
      <form-user :companies="companies"  :rols="rols" @closeModal="closeModalForm" />
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
import useHelper from "@/composables/useHelper";
export default {
  props: ["companies","rols"],
  components: {
    Modal: ModalVue,
    FormUser: FormUserVue,
    confirmationModal: ConfirmationModal,
    IconEdit,
    IconDelete,
    IconReset,
    FormReset: FormResetPasswordVue,
  },
  setup(props) {
    console.log(props)
    const openModal = ref(false);
    const store = useStore();
    const { makeToast } = useHelper();
    const showModalDelete = ref(false);
    const modalResetPassword = ref(false);
    const usersTable = ref(computed(() => store.state.users.tableUsers));

    const gettingData = async (type = 3) => {
      await store.dispatch("users/getUsers", type);
      usersTable.value = store.state.users.tableUsers;
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
    const openModalResetPassword = (id) => {
      modalResetPassword.value = true;
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
      closeModalResetPassword: () => (modalResetPassword.value = false),
      closeModalDelete: () => (showModalDelete.value = false),
    };
  },
};
</script>

<style>
</style>