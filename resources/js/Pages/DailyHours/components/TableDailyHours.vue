<template>
  <div class="p-2">
    <div class="flex gap-2 items-center">
      <button
        v-if="
          $page.props.auth.rols[0].id == 1 || $page.props.auth.rols[0].id == 2
        "
        class="bg-primary-500 p-4 text-white rounded-md"
        @click="openModalChange"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink"
          aria-hidden="true"
          role="img"
          width="24"
          height="24"
          preserveAspectRatio="xMidYMid meet"
          viewBox="0 0 24 24"
        >
          <path
            fill="white"
            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2zm.06 17v-2.01H12c-1.28 0-2.56-.49-3.54-1.46a5.006 5.006 0 0 1-.64-6.29l1.1 1.1c-.71 1.33-.53 3.01.59 4.13c.7.7 1.62 1.03 2.54 1.01v-2.14l2.83 2.83L12.06 19zm4.11-4.24l-1.1-1.1c.71-1.33.53-3.01-.59-4.13A3.482 3.482 0 0 0 12 8.5h-.06v2.15L9.11 7.83L11.94 5v2.02c1.3-.02 2.61.45 3.6 1.45c1.7 1.7 1.91 4.35.63 6.29z"
          />
        </svg>
      </button>
      <div
        class="relative text-gray-600 focus-within:text-gray-400 flex-1 w-full"
      >
        <span class="absolute inset-y-0 left-0 flex items-center pl-2">
          <button
            type="button"
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
          id="filterPartInputBot1"
          placeholder="Search ..."
          autocomplete="off"
        />
      </div>
    </div>
    <div class="rounded-lg bg-white p-5">
      <table
        id="activeRuns"
        class="display nowrap responsive"
        style="width: 100%; height: 100%"
        data-ordering="false"
      >
        <thead>
          <tr>
            <th data-priority="2">Date</th>
            <th data-priority="1">Hours</th>
            <th data-priority="1">Entered By</th>
            <th class="no-sort">Edit</th>
            <th class="no-sort">Delete</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="item in dataDailyHours" :key="item.id">
            <td>{{ item.dateChange }}</td>
            <td>{{ item.hourNumber }}</td>
            <td>{{ item.user.username }}</td>
            <td>
              <button
                class="editrun"
                @click="editHour(item.id)"
                v-if="
                  $page.props.auth.rols[0].id == 1 ||
                  $page.props.auth.rols[0].id == 2
                "
              >
                <svg
                  width="25"
                  height="25"
                  viewBox="0 0 25 25"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <g fill="none" fill-rule="evenodd">
                    <path fill="#227D9E" d="M0 0h25v25H0z" />
                    <g
                      fill="#FFF"
                      fill-rule="nonzero"
                      stroke="#FFF"
                      stroke-width=".5"
                    >
                      <path
                        d="M18.218 13.773a.353.353 0 0 0-.357.35v3.103a1.06 1.06 0 0 1-1.07 1.048H6.783c-.59 0-1.069-.47-1.07-1.048V8.117c.001-.579.48-1.048 1.07-1.049H9.95a.353.353 0 0 0 .357-.35c0-.192-.16-.349-.357-.349H6.783C5.8 6.37 5.001 7.152 5 8.117v9.109c.001.965.799 1.746 1.783 1.748h10.008c.985-.002 1.783-.783 1.784-1.748v-3.104c0-.193-.16-.35-.357-.35z"
                      />
                      <path
                        d="M18.434 5.47a1.628 1.628 0 0 0-2.27 0L9.8 11.706a.348.348 0 0 0-.091.154l-.837 2.96a.345.345 0 0 0 .091.34.362.362 0 0 0 .348.09l3.02-.82a.358.358 0 0 0 .158-.09l6.363-6.235a1.552 1.552 0 0 0 0-2.225l-.418-.41zm-7.857 6.463 5.208-5.104 1.68 1.646-5.208 5.104-1.68-1.646zm-.335.66 1.342 1.315-1.856.504.514-1.82zm8.106-4.983-.379.371-1.68-1.646.38-.37a.905.905 0 0 1 1.26 0l.419.41a.863.863 0 0 1 0 1.235z"
                      />
                    </g>
                  </g>
                </svg>
              </button>
            </td>
            <td>
              <button
                @click="openModalDelete(item.id)"
                v-if="
                  $page.props.auth.rols[0].id == 1 ||
                  $page.props.auth.rols[0].id == 2
                "
                class="showdelete"
              >
                <svg
                  width="25"
                  height="25"
                  viewBox="0 0 25 25"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <g fill="none" fill-rule="evenodd">
                    <path fill="#0271C5" d="M0 0h25v25H0z" />
                    <g fill="#FFF" fill-rule="nonzero">
                      <path
                        d="m18.495 8.13-.312-.927a.869.869 0 0 0-.826-.59h-2.62v-.846A.77.77 0 0 0 13.966 5h-2.4a.77.77 0 0 0-.772.767v.846h-2.62a.869.869 0 0 0-.826.59l-.311.926a.69.69 0 0 0 .096.625c.13.18.343.288.567.288h.326l.717 8.794A1.279 1.279 0 0 0 10.014 19h5.648c.66 0 1.218-.511 1.272-1.164l.717-8.794h.18a.703.703 0 0 0 .568-.288.69.69 0 0 0 .096-.625zM11.62 5.82h2.29v.793h-2.29V5.82zm4.49 11.95a.45.45 0 0 1-.448.41h-5.648a.45.45 0 0 1-.447-.41l-.712-8.728h7.966l-.712 8.728zM7.876 8.222l.255-.76a.043.043 0 0 1 .041-.029h9.184c.019 0 .035.012.041.03l.256.76H7.877z"
                      />
                      <path
                        d="M14.79 17.741h.022c.218 0 .401-.17.412-.388l.389-7.394a.412.412 0 0 0-.392-.43.412.412 0 0 0-.434.387l-.389 7.394a.411.411 0 0 0 .392.431zM10.324 17.354a.412.412 0 0 0 .436.387.411.411 0 0 0 .39-.432l-.406-7.394a.412.412 0 0 0-.436-.387.411.411 0 0 0-.39.432l.406 7.394zM12.77 17.742a.412.412 0 0 0 .413-.41V9.937a.412.412 0 0 0-.413-.41.412.412 0 0 0-.414.41v7.393c0 .227.185.41.414.41z"
                      />
                    </g>
                  </g>
                </svg>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- MODALS -->

  <modal :show="openModal" @close="closeModalChange">
    <div class="p-5">
      <form-daily-hours @closeModal1="apply" />
    </div>
  </modal>

  <modal :show="openModalEdit" @close="closeModalChangeEdit">
    <div class="p-5">
      <form-update-daily-hours :hourGet="hourGet" @closeModal="apply2" />
    </div>
  </modal>

  <confirmation-modal :show="showModalDelete">
    <template v-slot:title>
      <h1>Are you sure that delete this hour?</h1>
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
          @click="deleteHour()"
        >
          Acept
        </button>
      </div>
    </template>
  </confirmation-modal>
</template>

<script>
//// @ts-check
const $ = require("jquery");
var dt = require("datatables.net");
import { ref, nextTick, reactive } from "vue";
import "datatables.net-responsive-dt";
import "datatables.net-rowreorder-dt";
import "datatables.net-select-dt";
import useHelper from "@/composables/useHelper";
import ModalVue from "@/Jetstream/Modal.vue";
import FormDailyHoursVue from "./FormDailyHours.vue";
import axios from "axios";
import FormUpdateDailyHoursVue from "./FormUpdateDailyHours.vue";
import ConfirmationModal from "@/Jetstream/ConfirmationModal.vue";
export default {
  components: {
    modal: ModalVue,
    FormDailyHours: FormDailyHoursVue,
    confirmationModal: ConfirmationModal,
    FormUpdateDailyHours: FormUpdateDailyHoursVue,
  },
  setup() {
    // Declare variables
    const { getCurrentDate } = useHelper();
    const startDate = ref(getCurrentDate());
    const showModalDelete = ref(false);
    const { makeToast } = useHelper();

    const dataDailyHours = ref([]);
    const openModal = ref(false);
    const openModalEdit = ref(false);
    const selectedCheckbox = ref(false);
    const filterOption = ref(3);
    const arrayId = ref([]);
    const idDelete = ref(0);
    let table;
    const hourGet = ref({
      id: 0,
      dateChange: "",
      hourNumber: "",
    });
    $(document).ready(function () {
      $("#filterPartInputBot1")
        .off()
        .keyup(function () {
          table.search(this.value).draw();
        });
    });

    const gettingData = async (status = 3) => {
      try {
        const res = await axios.get(`/dailyHours/getDailyHours`);
        const data = res.data;
        dataDailyHours.value = data;
        await generateDataTable(status);
      } catch (e) {
        console.log(e);
      }
    };

    const currentStartDate = (start_date) => {
      const originalDate = new Date(start_date);
      let monthStartDate = originalDate.getMonth() + 1;
      let fullMonthStartDate = "0";
      monthStartDate.toString().length < 2
        ? (fullMonthStartDate = fullMonthStartDate.concat(monthStartDate))
        : (fullMonthStartDate = monthStartDate);
      const currentStartDateConverted =
        "" +
        originalDate.getFullYear() +
        "-" +
        fullMonthStartDate +
        "-" +
        originalDate.toString().slice(8, 10);

      return currentStartDateConverted;
    };

    const openModalChange = () => {
      openModal.value = true;
    };

    const generateDataTable = (status) => {
      const self = this;
      // $("#activeRuns").DataTable().clear().destroy();
      $("#activeRuns").DataTable().destroy();

      nextTick(() => {
        table = $("#activeRuns").DataTable({
          // ordering: true,
          bLengthChange: false,
          pageLength: 10,
          responsive: true,
          language: {
            paginate: {
              next: `<svg class="arrow_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="15" height="14" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><g transform="rotate(270 10 10)"><path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="white"/></g></svg>`, // or '→'
              previous: `<svg class="arrow_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="15" height="14" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><g transform="rotate(90 10 10)"><path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="white"/></g></svg>`, // or '←'
            },
            info: "Showing results _START_ to _END_ from _TOTAL_ ",
          },
        });
      });
    };

    const apply = () => {
      openModal.value = false;
      gettingData();
    };
    const apply2 = () => {
      openModalEdit.value = false;
      gettingData();
    };

    const closeModalChange = () => {
      openModal.value = false;
    };
    const closeModalChangeEdit = () => {
      openModalEdit.value = false;
    };
    const editHour = (id) => {
      hourGet.value = dataDailyHours.value.find((hour) => hour.id === id);
      openModalEdit.value = true;
    };
    const deleteHour = async () => {
      try {
        const res = await axios.delete(`/dailyHours/${idDelete.value}`);
        const { ok, value, message } = res.data;
        if (ok) {
          showModalDelete.value = false;
          makeToast(message);
          gettingData();
        } else {
          makeToast(message, "error");
        }
      } catch (e) {
        console.log(e);
      }
    };
    const closeModalDelete = () => {
      showModalDelete.value = false;
      idDelete.value = 0;
    };
    const openModalDelete = (id) => {
      idDelete.value = id;
      showModalDelete.value = true;
    };

    gettingData();

    return {
      startDate,
      openModal,
      openModalChange,
      closeModalChange,
      apply,
      arrayId,
      filterOption,
      selectedCheckbox,
      dataDailyHours,
      currentStartDate,
      editHour,
      hourGet,
      closeModalChangeEdit,
      openModalEdit,
      apply2,
      showModalDelete,
      deleteHour,
      closeModalDelete,
      openModalDelete,
    };
  },
};
</script>

<style>
</style>