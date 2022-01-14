<template>

  <div class="container p-9">
    <div class="flex gap-8 items-center mb-5 flex-col md:flex-row">
      <div class="flex gap-8 items-center flex-1 w-full">
        <button @click="openModalForm"><icon-plus /></button>
        <select
          class="w-full p-3 rounded-sm border-[#a2a2a2] text-[#a2a2a2] flex-1"
          @change="changeFilter"
          v-model="filterOption"
        >
          <option value="0" selected>All</option>
          <option :value="rol.value" v-for="rol in rols" :key="rol.id">
            {{ rol.label }}
          </option>
        </select>
      </div>
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
          id="filterUserInput"
          placeholder="Search Users..."
          autocomplete="off"
        />
      </div>
    </div>

    <div class="rounded-lg bg-white p-5">
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
        </tbody>
      </table>
    </div>

    <!-- MODAL RESET PASSWORD -->
    <modal :show="modalResetPassword">
      <div class="p-5">
        <form-reset
          @closeModal="closeModalResetPassword"
          :username="username"
        />
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
  </div>
</template>

<script>
import ModalVue from "@/Jetstream/Modal.vue";
import { computed, nextTick, onMounted, ref } from "vue";
import FormUserVue from "./FormUser.vue";
import FormResetPasswordVue from "./FormResetPassword.vue";
import { useStore } from "vuex";
const $ = require("jquery");
import IconEdit from "@/assets/Icons/iconEdit.vue";
import IconDelete from "@/assets/Icons/iconDelete.vue";
import IconReset from "@/assets/Icons/iconResetPassword.vue";
import ConfirmationModal from "@/Jetstream/ConfirmationModal.vue";
import Multiselect from "@vueform/multiselect";
import useHelper from "@/composables/useHelper";
import IconPlus from "@/assets/Icons/iconPlus.vue";
var dt = require("datatables.net");
import "datatables.net-responsive-dt";
import "datatables.net-rowreorder-dt";

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
      // const data = await store.dispatch("users/getUsers", type);
      // store.commit("users/setDataTable", data);
      await generateDataTable(type);
    };
    $(document).ready(function () {
      $("#filterUserInput")
        .off()
        .keyup(function () {
          $("#tableUsers").DataTable().search(this.value).draw();
        });
    });
    const generateDataTable = (type = 0) => {
      $("#tableUsers").DataTable().destroy();
      nextTick(() => {
        $("#tableUsers").DataTable({
          ordering: true,
          bLengthChange: false,
          pageLength: 5,
          stateSave: true,
          rowReorder: {
            selector: "td:nth-child(2)",
          },
          columnDefs: [
            {
              defaultContent: "-",
              targets: "_all",
            },
          ],
          responsive: true,
          language: {
            paginate: {
              next: `<svg class="arrow_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="15" height="14" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><g transform="rotate(270 10 10)"><path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="white"/></g></svg>`, // or '→'
              previous: `<svg class="arrow_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="15" height="14" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><g transform="rotate(90 10 10)"><path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="white"/></g></svg>`, // or '←'
            },
            info: "Showing results _START_ to _END_ from _TOTAL_",
          },
          ajax: {
            url: `user/getUsers/${type}`
          },
          stateSaveCallback: function (settings, data) {
            const state = settings.aoData;
            let arr = [];
            state.forEach((element) => {
              arr.push(element._aData);
            });
            store.commit("users/setDataTable",arr);
          },
          columns: [
            {
              name: "username",
              searchable: true,
              render: function(data, type, row, meta){
                return "<span>"+ row.username +"</span>";
              },
            },
            {
              name: "name",
              searchable: true,
              render: function(data, type, row, meta){
                let lastname = '';
                if( row.lastname ) {
                  lastname = row.lastname
                } 
                return "<span>"+ row.name + " " + lastname+"</span>";
              },
            },
            {
              name: "company.name",
              searchable: true,
              render: function(data, type, row, meta){
                let company = '';
                if( row.company ) {
                  company = row.company.name
                } 
                return "<span>" + company + "</span>";
              },
            },
            {
              name: "email",
              searchable: true,
              render: function(data, type, row, meta){
                return "<a href="+row.email+">" + row.email + "</a>";
              },
            },
            {
              name: "rols",
              searchable: false,
              render: function(data, type, row, meta){
                let rols = "";
                row.rols.forEach( rol => {
                  rols = rols + "<span>"+rol.name+"</span>";
                });
                return rols;
              },
            },
            {
              name: "resetPass",
              searchable: false,
              render: function(data, type, row, meta){
                return (
                  '<button class="openmodalresetpassword" idp='+
                    row.username
                  +'><svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"> <g fill="none" fill-rule="evenodd"> <path fill="#0ABC8A" d="M0 0h25v25H0z"/> <rect stroke="#FFF" x="6.5" y="6.5" width="8" height="8" rx="1"/> <rect stroke="#FFF" x="11.5" y="11.5" width="8" height="8" rx="1"/> </g></svg></button>'
                );
              },
            },
            {
              name: "edit",
              searchable: false,
              render: function(data, type, row, meta){
                return (
                  '<button class="openmodaleditclick" idp='+
                    row.id
                  +'><svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"> <g fill="none" fill-rule="evenodd"> <path fill="#227D9E" d="M0 0h25v25H0z"/> <g fill="#FFF" fill-rule="nonzero" stroke="#FFF" stroke-width=".5"> <path d="M18.218 13.773a.353.353 0 0 0-.357.35v3.103a1.06 1.06 0 0 1-1.07 1.048H6.783c-.59 0-1.069-.47-1.07-1.048V8.117c.001-.579.48-1.048 1.07-1.049H9.95a.353.353 0 0 0 .357-.35c0-.192-.16-.349-.357-.349H6.783C5.8 6.37 5.001 7.152 5 8.117v9.109c.001.965.799 1.746 1.783 1.748h10.008c.985-.002 1.783-.783 1.784-1.748v-3.104c0-.193-.16-.35-.357-.35z"/> <path d="M18.434 5.47a1.628 1.628 0 0 0-2.27 0L9.8 11.706a.348.348 0 0 0-.091.154l-.837 2.96a.345.345 0 0 0 .091.34.362.362 0 0 0 .348.09l3.02-.82a.358.358 0 0 0 .158-.09l6.363-6.235a1.552 1.552 0 0 0 0-2.225l-.418-.41zm-7.857 6.463 5.208-5.104 1.68 1.646-5.208 5.104-1.68-1.646zm-.335.66 1.342 1.315-1.856.504.514-1.82zm8.106-4.983-.379.371-1.68-1.646.38-.37a.905.905 0 0 1 1.26 0l.419.41a.863.863 0 0 1 0 1.235z"/> </g> </g></svg></button>'
                );
              },
            },
            {
              name: "delete",
              searchable: false,
              render: function(data, type, row, meta){
                return (
                  '<button class="openmodaldeleteclick" idp='+
                    row.id
                  +'><svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"> <g fill="none" fill-rule="evenodd"> <path fill="#0271C5" d="M0 0h25v25H0z"/> <g fill="#FFF" fill-rule="nonzero"> <path d="m18.495 8.13-.312-.927a.869.869 0 0 0-.826-.59h-2.62v-.846A.77.77 0 0 0 13.966 5h-2.4a.77.77 0 0 0-.772.767v.846h-2.62a.869.869 0 0 0-.826.59l-.311.926a.69.69 0 0 0 .096.625c.13.18.343.288.567.288h.326l.717 8.794A1.279 1.279 0 0 0 10.014 19h5.648c.66 0 1.218-.511 1.272-1.164l.717-8.794h.18a.703.703 0 0 0 .568-.288.69.69 0 0 0 .096-.625zM11.62 5.82h2.29v.793h-2.29V5.82zm4.49 11.95a.45.45 0 0 1-.448.41h-5.648a.45.45 0 0 1-.447-.41l-.712-8.728h7.966l-.712 8.728zM7.876 8.222l.255-.76a.043.043 0 0 1 .041-.029h9.184c.019 0 .035.012.041.03l.256.76H7.877z"/> <path d="M14.79 17.741h.022c.218 0 .401-.17.412-.388l.389-7.394a.412.412 0 0 0-.392-.43.412.412 0 0 0-.434.387l-.389 7.394a.411.411 0 0 0 .392.431zM10.324 17.354a.412.412 0 0 0 .436.387.411.411 0 0 0 .39-.432l-.406-7.394a.412.412 0 0 0-.436-.387.411.411 0 0 0-.39.432l.406 7.394zM12.77 17.742a.412.412 0 0 0 .413-.41V9.937a.412.412 0 0 0-.413-.41.412.412 0 0 0-.414.41v7.393c0 .227.185.41.414.41z"/> </g> </g></svg></button>'
                );
              },
            },
          ],
          drawCallback: function () {
            $("#tableUsers").on("click", "[class*=openmodalresetpassword]", function (e) {
              openModalResetPassword(e.currentTarget.attributes[1].value);
            });
            $("#tableUsers").on("click", "[class*=openmodaleditclick]", function (e) {
              openModalEditClick(e.currentTarget.attributes[1].value);
            });
            $("#tableUsers").on("click", "[class*=openmodaldeleteclick]", function (e) {
              openModalDeleteClick(e.currentTarget.attributes[1].value);
            });
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