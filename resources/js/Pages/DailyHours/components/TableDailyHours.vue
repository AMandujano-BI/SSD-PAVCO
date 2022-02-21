<template>
  <input
    type="date"
    class="w-full mb-5"
    @change="changeDateFilter"
    v-model="startDate"
  />
  <button
    class="bg-primary-500 p-5 text-white rounded-md"
    @click="openModalChange"
  >
    Change
  </button>
  <div class="rounded-lg bg-white p-5">
    <table
      id="activeRuns"
      class="display nowrap responsive"
      style="width: 100%; height: 100%"
    >
      <thead>
        <tr>
          <th class="no-sort"></th>
          <th data-priority="2">StartDate</th>
          <th data-priority="1">Run #</th>
          <th data-priority="1" class="max-w-[150px]">Customer</th>
          <th>Method</th>
          <th data-priority="2">Status</th>
          <th>Hrs</th>
        </tr>
      </thead>

      <tbody></tbody>
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
export default {
  components: {
    modal: ModalVue,
    FormDailyHours: FormDailyHoursVue,
  },
  setup() {
    // Declare variables
    const { getCurrentDate } = useHelper();
    const startDate = ref(getCurrentDate());
    const openModal = ref(false);
    const arrayId = ref([]);
    let table;
    let runs = ref([]);

    const changeDateFilter = (e) => {
      startDate.value = e.target.value;
      gettingData();
    };
    const gettingData = async (status = 3) => {
      try {
        $("#activeRuns").DataTable().clear().destroy();
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
      openModal.value = true;
      const data = table.rows(".selected").data();
      arrayId.value =[]
      data.map((item) =>  arrayId.value.push(item.id));
    };

    const generateDataTable = (status) => {
      const self = this;
      nextTick(() => {
        table = $("#activeRuns").DataTable({
          ordering: true,
          bLengthChange: false,
          pageLength: 10,
          processing: true,
          serverSide: true,
          stateSave: true,
          columnDefs: [
            {
              defaultContent: "-",
              targets: "_all",
            },
          ],
          order: [[1, "asc"]],
          responsive: true,
          select: {
            style: "os",
            selector: "td:first-child",
          },
          rowId: "id",
          language: {
            paginate: {
              next: `<svg class="arrow_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="15" height="14" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><g transform="rotate(270 10 10)"><path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="white"/></g></svg>`, // or '→'
              previous: `<svg class="arrow_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="15" height="14" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><g transform="rotate(90 10 10)"><path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="white"/></g></svg>`, // or '←'
            },
            info: "Showing results _START_ to _END_ from _TOTAL_",
          },
          ajax: {
            url: `/run/getAllRunsByDate/'${startDate.value}'/${status}`,
          },
          // language:{
          //   // processing:"<h1 style='background:#333;color:#fff;height:800px;'>Loading..</h1>"
          // },
          stateSaveCallback: function (settings, data) {
            const state = settings.aoData;
            let arr = [];
            state.forEach((element) => {
              arr.push(element._aData);
            });
            runs.value = arr;
          },
          columns: [
            {
              name: "select",
              searchable: true,
              render: function (data, type, row, meta) {
                // return "<td> <input type='checkbox' /> </td>";
                return "<td></td>";
              },
            },
            {
              name: "start_date",
              searchable: true,
              render: function (data, type, row, meta) {
                return "<td>" + row.start_date.slice(0, 10) + "</td>";
              },
            },
            {
              name: "id",
              searchable: true,
              render: function (data, type, row, meta) {
                return "<td>" + row.id + "</td>";
              },
            },
            {
              name: "company.name",
              searchable: true,
              render: function (data, type, row, meta) {
                return "<td>" + row.company.name + "</td>";
              },
            },
            {
              name: "method.name",
              searchable: false,
              render: function (data, type, row, meta) {
                return "<td>" + row.method.name + "</td>";
              },
            },
            {
              name: "status",
              searchable: false,
              render: function (data, type, row, meta) {
                let status;
                if (row.status === 1) {
                  status = "<span>Complete</span>";
                } else {
                  status = "<span>Active</span>";
                }
                return status;
              },
            },
            {
              name: "hours",
              searchable: false,
              render: function (data, type, row, meta) {
                const hours = calculateHours(
                  row.id,
                  row.status,
                  row.start_date,
                  row.isEdit,
                  row.last_edit,
                  row.hours,
                  row.closed_date
                );
                return `<td class="text-center">${hours}</td>`;
              },
            },
          ],
          drawCallback: function () {
            $("#activeRuns").on("click", "[class*=showphotos]", function (e) {
              showPhotos(e.currentTarget.attributes[1].value);
            });
            $("#activeRuns").on(
              "click",
              "[class*=reportandphotosrun]",
              function (e) {
                reportAndPhotosRun(e.currentTarget.attributes[1].value);
              }
            );
            $("#activeRuns").on("click", "[class*=runemail]", function (e) {
              openModalEmail(e.currentTarget.attributes[1].value);
            });
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
      arrayId
    };
  },
};
</script>

<style>
</style>