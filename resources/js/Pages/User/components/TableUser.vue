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
  <modal :show="openModal" @close="closeModalForm">
    <div class="p-5">
      <form-user />
    </div>
  </modal>
  <div class="rounded-lg shadow-lg p-5">
    <table id="activeRuns" class="display" style="width: 100%; height: 100%">
      <thead>
        <tr>
          <th>Username</th>
          <th>FirtsName</th>
          <th>LastName</th>
          <th>Company</th>
          <th>EmailAddress</th>
          <th>UserType</th>
          <th>Reset Password</th>
          <th class="no-sort">Edit</th>
          <th class="no-sort">Delete</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</template>

<script>
import axios from "axios";
import ModalVue from "@/Jetstream/Modal.vue";
import { ref } from "vue";
import FormUserVue from "./FormUser.vue";
export default {
  components: {
    Modal: ModalVue,
    FormUser: FormUserVue,
  },
  setup() {
    const getUsers = async (type = 1) => {
      const res = await axios.get(`/user/getUsers/${type}`);
      console.log(res.data);
    };
    getUsers();
    const openModal = ref(false);
    const openModalForm = () => {
      openModal.value = true;
    };
    const closeModalForm = () => {
      openModal.value = false;
    };
    return {
      openModalForm,
      openModal,
      closeModalForm,
    };
  },
};
</script>

<style>
</style>