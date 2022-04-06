<template>
  <div class="p-2">
    <div
      v-if="
        $page.props.auth.rols[0].id == 1 || $page.props.auth.rols[0].id == 2
      "
    >
      <button
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
    </div>
    <div class="rounded-lg bg-white p-5">
      <table
        id="activeRuns"
        class="display nowrap responsive"
        style="width: 100%; height: 100%"
      >
        <thead>
          <tr>
            <th data-priority="2">Date</th>
            <th data-priority="1">Hours</th>
            <th data-priority="1">Entered By</th>
            <th class="no-sort">Edit</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="item in dataDailyHours" :key="item.id">
            <td>{{ item.dateChange }}</td>
            <td>{{ item.hourNumber }}</td>
            <td>{{ item.user.username }}</td>
            <td>
              <button class="editrun" @click="editHour(item.id)">
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
export default {
  components: {
    modal: ModalVue,
    FormDailyHours: FormDailyHoursVue,
    FormUpdateDailyHours: FormUpdateDailyHoursVue,
  },
  setup() {
    // Declare variables
    const { getCurrentDate } = useHelper();
    const startDate = ref(getCurrentDate());
    const dataDailyHours = ref([]);
    const openModal = ref(false);
    const openModalEdit = ref(false);
    const selectedCheckbox = ref(false);
    const filterOption = ref(3);
    const arrayId = ref([]);
    let table;
    const hourGet = ref({
      id:0,
      dateChange: "",
      hourNumber: "",
    });

    const gettingData = async (status = 3) => {
      try {
        const res = await axios.get(`/dailyHours/getDailyHours`);
        const data = res.data;
        console.log(data);
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
          ordering: true,
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

    const convertUTC = (dateToConvert) => {
      const currentUTCDate = new Date(dateToConvert);
      const monthUTC = currentUTCDate.getUTCMonth() + 1;
      const dayUTC = currentUTCDate.getUTCDate();
      const hoursUTC = currentUTCDate.getUTCHours();
      const minutesUTC = currentUTCDate.getUTCMinutes();
      let fullMonthUTC = "0";
      let fullDayUTC = "0";
      let fullHoursUTC = "0";
      let fullMinutesUTC = "0";
      monthUTC.toString().length < 2
        ? (fullMonthUTC = fullMonthUTC.concat(monthUTC))
        : (fullMonthUTC = monthUTC);
      dayUTC.toString().length < 2
        ? (fullDayUTC = fullDayUTC.concat(dayUTC))
        : (fullDayUTC = dayUTC);
      hoursUTC.toString().length < 2
        ? (fullHoursUTC = fullHoursUTC.concat(hoursUTC))
        : (fullHoursUTC = hoursUTC);
      minutesUTC.toString().length < 2
        ? (fullMinutesUTC = fullMinutesUTC.concat(minutesUTC))
        : (fullMinutesUTC = minutesUTC);

      const utcDate =
        "" +
        currentUTCDate.getUTCFullYear() +
        "-" +
        fullMonthUTC +
        "-" +
        fullDayUTC +
        "T" +
        fullHoursUTC +
        ":" +
        fullMinutesUTC;

      return utcDate;
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
      apply2
    };
  },
};
</script>

<style>
</style>