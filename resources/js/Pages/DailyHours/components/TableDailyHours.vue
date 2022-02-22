<template>
  <input
    type="date"
    class="w-full mb-5"
    @change="changeDateFilter"
    v-model="startDate"
  />
  <button
    class="bg-primary-500 p-4 text-white rounded-md"
    @click="openModalChange"
  >
    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="24" height="24" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="white" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2zm.06 17v-2.01H12c-1.28 0-2.56-.49-3.54-1.46a5.006 5.006 0 0 1-.64-6.29l1.1 1.1c-.71 1.33-.53 3.01.59 4.13c.7.7 1.62 1.03 2.54 1.01v-2.14l2.83 2.83L12.06 19zm4.11-4.24l-1.1-1.1c.71-1.33.53-3.01-.59-4.13A3.482 3.482 0 0 0 12 8.5h-.06v2.15L9.11 7.83L11.94 5v2.02c1.3-.02 2.61.45 3.6 1.45c1.7 1.7 1.91 4.35.63 6.29z"/></svg>
  </button>
  <div class="rounded-lg bg-white p-5">
    <table
      id="activeRuns"
      class="display nowrap responsive"
      style="width: 100%; height: 100%"
    >
      <thead>
        <tr>
          <th>
            <input
              type="checkbox"
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
          <td>{{ item.start_date.slice(0,10) }}</td>
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
  <!-- MODALS -->

  <modal :show="openModal" @close="closeModalChange">
    <div class="p-5">
      <form-daily-hours :arrayId="arrayId" @closeModal="closeModalChange" />
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
    const arrayId = ref([]);
    const selected = ref([]);
    let table;
    let runs = ref([]);

    const changeDateFilter = (e) => {
      startDate.value = e.target.value;
      selectedCheckbox.value =false
      gettingData();
    };
    const gettingData = async (status = 3) => {
      try {
        $("#activeRuns").DataTable().clear().destroy();
        const res = await axios.get(
          `/run/getAllRunsByDate/'${startDate.value}'/${status}`
        );
        dataDailyHours.value = res.data;
        await generateDataTable(status);
      } catch (e) {
        console.log(e);
      }
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
      arrayId.value = [];
      data.map((item) => arrayId.value.push(item[2]));
      if (arrayId.value.length > 0) {
        openModal.value = true;
      } else {
        makeToast("You must select a run", "error");
      }
    };
    const toggleAll = () => {
      if (selectedCheckbox.value) {
        table.rows().select();
        const data = table.rows(".selected").data();
        arrayId.value = [];
        data.map((item) => arrayId.value.push(item[2]));
      } else {
        table.rows().deselect();
      }
    };

    const generateDataTable = (status) => {
      const self = this;
      nextTick(() => {
        table = $("#activeRuns").DataTable({
          ordering: true,
          bLengthChange: false,
          pageLength: 3,
          stateSave: true,
          columnDefs: [
            {
              orderable: false,
              defaultContent: "-",
              targets: 0,
              className: "select-checkbox",
            },
          ],
          responsive: true,
          select: {
            style: "os",
            selector: "td:first-child",
          },
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

    gettingData();
    return {
      changeDateFilter,
      startDate,
      openModal,
      openModalChange,
      closeModalChange: () => (openModal.value = false),
      arrayId,
      toggleAll,
      selectedCheckbox,
      dataDailyHours,
      calculateHours,
    };
  },
};
</script>

<style>
</style>