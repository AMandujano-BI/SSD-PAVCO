<template>
  <div class="p-2">
    <div class="pb-4">
      <select
        class="w-full p-3 rounded-sm border-[#a2a2a2] text-[#a2a2a2] flex-1"
        @change="changeFilter"
        v-model="filterOption"
      >
        <option value="3">Show All</option>
        <option value="0">Active</option>
        <option value="1">Complete</option>
      </select>
    </div>

    <input
      type="date"
      class="w-full mb-5"
      @change="changeDateFilter"
      v-model="startDate"
    />
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
            <th data-priority="1">
              <input
                type="checkbox"
                id="main_checkbox"
                name="main_checkbox"
                @change="toggleAll"
                v-model="selectedCheckbox"
              />
            </th>
            <th data-priority="2">StartDate</th>
            <th data-priority="1">Run #</th>
            <th data-priority="1" class="max-w-[150px]">Customer</th>
            <th>Method</th>
            <th data-priority="2">Status</th>
            <th>Hrs</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="item in dataDailyHours" :key="item.id">
            <td></td>
            <td>{{ currentStartDate(item.start_date) }}</td>
            <td>{{ item.id }}</td>
            <td>{{ item.company.name }}</td>
            <td>{{ item.method.name }}</td>
            <td>{{ item.status === 1 ? "Complete" : "Active" }}</td>
            <td>
              {{
                calculateHours(
                  item.id,
                  item.status,
                  item.start_date,
                  item.isEdit,
                  item.last_edit,
                  item.hours,
                  item.closed_date
                )
              }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- MODALS -->

  <modal :show="openModal" @close="closeModalChange">
    <div class="p-5">
      <form-daily-hours :arrayId="arrayId" @closeModal="apply" />
    </div>
  </modal>
</template>

<script>
const $ = require("jquery");
var dt = require("datatables.net");
import { ref, nextTick } from "vue";
import "datatables.net-responsive-dt";
import "datatables.net-rowreorder-dt";
import "datatables.net-select-dt";
import useHelper from "@/composables/useHelper";
import ModalVue from "@/Jetstream/Modal.vue";
import FormDailyHoursVue from "./FormDailyHours.vue";
import axios from "axios";
export default {
  components: {
    modal: ModalVue,
    FormDailyHours: FormDailyHoursVue,
  },
  setup() {
    // Declare variables
    const { getCurrentDate } = useHelper();
    const startDate = ref(getCurrentDate());
    const dataDailyHours = ref([]);
    const { makeToast } = useHelper();
    const openModal = ref(false);
    const selectedCheckbox = ref(false);
    const filterOption = ref(3);
    const arrayId = ref([]);
    const selected = ref([]);
    let table;
    let runs = ref([]);

    const changeDateFilter = (e) => {
      startDate.value = e.target.value;
      selectedCheckbox.value = false;
      gettingData();
    };
    const gettingData = async (status = 3) => {
      try {
        let initDate = `${startDate.value} 0:01`;
        let finalDate = `${startDate.value} 23:59`;

        const initUTCDate = convertUTC(initDate);
        const finalUTCDate = convertUTC(finalDate);
        const res = await axios.get(
          `/run/getAllRunsByDate/'${initUTCDate}'/'${finalUTCDate}'/${status}`
        );
        dataDailyHours.value = res.data;

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

    const calculateHours = (
      id,
      status,
      created_date,
      edit,
      lastDate,
      hours,
      closeDate
    ) => {
      if (status === 1) {
        //cerrado
        if (edit) {
          return hours;
        } else {
          const closeNonEdit =
            Math.abs(new Date(closeDate) - new Date(created_date)) / 36e5;
          return closeNonEdit | 0; // trunca los decimales y se queda con el entero
        }
      } else {
        if (edit) {
          const activeEdit = Math.abs(new Date() - new Date(lastDate)) / 36e5;
          const hoursEdited = activeEdit | 0; // trunca los decimales y se queda con el entero
          return hours + hoursEdited;
        } else {
          const activeNonEdit =
            Math.abs(new Date() - new Date(created_date)) / 36e5;
          return activeNonEdit | 0; // trunca los decimales y se queda con el entero
        }
      }
    };

    const openModalChange = () => {
      const data = table.rows(".selected").data();
      let indexData;
      let dataId;
      arrayId.value = [];
      data.map((item) => {
        dataId = Number(item[2]);
        indexData = dataDailyHours.value.findIndex((run) => run.id === dataId);
        arrayId.value.push({
          id: item[2],
          hours: calculateHours(
            dataDailyHours.value[indexData].id,
            dataDailyHours.value[indexData].status,
            dataDailyHours.value[indexData].start_date,
            dataDailyHours.value[indexData].isEdit,
            dataDailyHours.value[indexData].last_edit,
            dataDailyHours.value[indexData].hours,
            dataDailyHours.value[indexData].closed_date
          ),
        });
      });
      // }arrayId.value.push(item[2]));
      if (arrayId.value.length > 0) {
        openModal.value = true;
      } else {
        makeToast("You must select a run", "error");
      }
    };
    const toggleAll = () => {
      if (selectedCheckbox.value) {
        table.rows().select();
        const dataAll = document.querySelectorAll(`[data-id]`);
        let indexData;
        let dataId;
        dataAll.forEach((item) => {
          item.checked = true;
        });
        const data = table.rows(".selected").data();
        arrayId.value = [];
        data.map((item) => {
          dataId = Number(item[2]);
          indexData = dataDailyHours.value.findIndex(
            (run) => run.id === dataId
          );
          arrayId.value.push({
            id: item[2],
            hours: calculateHours(
              dataDailyHours.value[indexData].id,
              dataDailyHours.value[indexData].status,
              dataDailyHours.value[indexData].start_date,
              dataDailyHours.value[indexData].isEdit,
              dataDailyHours.value[indexData].last_edit,
              dataDailyHours.value[indexData].hours,
              dataDailyHours.value[indexData].closed_date
            ),
          });
        });
      } else {
        table.rows().deselect();

        const dataAll = document.querySelectorAll(`[data-id]`);
        dataAll.forEach((item) => {
          item.checked = false;
        });
      }
    };

    const generateDataTable = (status) => {
      const self = this;
      // $("#activeRuns").DataTable().clear().destroy();
      $("#activeRuns").DataTable().destroy();

      nextTick(() => {
        table = $("#activeRuns")
          .DataTable({
            ordering: true,
            bLengthChange: false,
            pageLength: 10,
            columnDefs: [
              {
                orderable: false,
                defaultContent: "-",
                render: function (data, type, row) {
                  return type === "display"
                    ? `<input class="select-checkbox" data-id="${parseInt(
                        row[2]
                      )}" type="checkbox">`
                    : "";
                },
                targets: 0,
              },
            ],
            responsive: true,
            select: "multi",

            language: {
              paginate: {
                next: `<svg class="arrow_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="15" height="14" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><g transform="rotate(270 10 10)"><path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="white"/></g></svg>`, // or '→'
                previous: `<svg class="arrow_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="15" height="14" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><g transform="rotate(90 10 10)"><path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="white"/></g></svg>`, // or '←'
              },
              info: "Showing results _START_ to _END_ from _TOTAL_ ",
            },
          })
          .on("select.dt deselect.dt", function (e, dt, type, indexes) {
            const id = dt.rows(indexes).data()[0][2];
            const data = document.querySelector(`[data-id="${id}"]`);
            const count = table.rows({ selected: true }).count();
            const countAll = table.rows().count();
            if (count === countAll) {
              document.querySelector("#main_checkbox").checked = true;
            } else {
              document.querySelector("#main_checkbox").checked = false;
            }
            data.checked = true;
          })
          .on(" deselect.dt", function (e, dt, type, indexes) {
            const id = dt.rows(indexes).data()[0][2];
            const data = document.querySelector(`[data-id="${id}"]`);
            data.checked = false;
          });
      });
    };

    const apply = (jsonData) => {
      // console.log(jsonData);
      let idArr;
      let indexArr;
      let hourArr = Number(jsonData.hours);
      jsonData.arrayId.forEach((el) => {
        idArr = Number(el.id);

        indexArr = dataDailyHours.value.findIndex((run) => run.id === idArr);
        // console.log(dataDailyHours.value[indexArr].hours);
        dataDailyHours.value[indexArr].isEdit = true;
        dataDailyHours.value[indexArr].last_edit = new Date();
        dataDailyHours.value[indexArr].hours = hourArr + el.hours;
      });
      openModal.value = false;
    };

    const closeModalChange = () => {
      openModal.value = false;
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
    const changeFilter = async () => {
      await gettingData(filterOption.value);
    };

    gettingData();

    return {
      changeDateFilter,
      startDate,
      openModal,
      openModalChange,
      closeModalChange,
      apply,
      arrayId,
      toggleAll,
      filterOption,
      changeFilter,
      selectedCheckbox,
      dataDailyHours,
      calculateHours,
      currentStartDate,
    };
  },
};
</script>

<style>
</style>