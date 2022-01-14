<template>
  <app-layout title="Detail Run">
    <div class="container mx-auto pt-14">
      <div class="container p-9">
        <div class="flex items-center md:justify-center">
          <div
            class="
              inline-flex
              md:hidden
              bg-[#34689c]
              text-[20px]
              rounded-[7px]
              p-5
              w-[50px]
              h-[50px]
              items-center
              justify-center
              cursor-pointer
            "
            @click="goBack"
          >
<svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M35 12.5L22.5 25L35 37.5L32.5 42.5L15 25L32.5 7.5L35 12.5Z" fill="white"/>
</svg>

          </div>

          <h1
            class="
              text-center text-2xl
              flex-1
              md:flex-none
              p-5
              font-bold
              text-[#3b4559]
            "
          >
            Run # {{ runDetail.id }}
          </h1>
        </div>
        <div
          class="
            grid grid-cols-2
            gap-4
            items-center
            sm:grid-cols-3
            lg:grid-cols-4
            py-5
          "
        >
          <p class="text-[#3b4559] text-[16px] font-semibold">
            <span class="text-[#838A97] font-light block py-2 md:inline">
              Start Date
            </span>
            <span> {{ startDate }} </span>
          </p>
          <p class="text-[#3b4559] text-[16px] font-semibold">
            <span class="text-[#838A97] font-light block py-2 md:inline"
              >Status
            </span>
            <span> {{ runDetail.status }} </span>
          </p>
          <p class="text-[#3b4559] text-[16px] font-semibold">
            <span class="text-[#838A97] font-light block py-2 md:inline"
              >Customer
            </span>
            <span> {{ runDetail.company?.name }} </span>
          </p>
          <p class="text-[#bcbec4] text-[16px] font-semibold">
            <span class="text-[#838A97] font-light block py-2 md:inline"
              >Hrs
            </span>
            <span> {{ calculateHours( runDetail.status, runDetail.start_date, runDetail.isEdit, runDetail.last_edit, runDetail.hours, runDetail.closed_date) }} </span>
          </p>
          <p class="text-[#3b4559] text-[16px] font-semibold">
            <span class="text-[#838A97] font-light block py-2 md:inline"
              >Method
            </span>
            <span> {{ runDetail.method?.name }} </span>
          </p>
        </div>

        <div
          class="
            grid grid-cols-2
            gap-4
            items-center
            sm:grid-cols-3
            lg:grid-cols-4
            py-5
          "
        >
          <div
            class="
              flex
              gap-2
              items-center
              bg-[#75DDDD]
              cursor-pointer
              text-white
              py-4
              justify-center
            "
          >
            <icon-photo />
            Photos
          </div>
          <div
            class="
              flex
              gap-2
              items-center
              bg-[#508991]
              cursor-pointer
              text-white
              py-4
              justify-center
            "
          >
            <icon-result />
            Results
          </div>
          <div
            class="
              flex
              gap-2
              items-center
              bg-[#227D9E]
              cursor-pointer
              text-white
              py-4
              justify-center
            "
          >
            <icon-edit />
            Edit
          </div>
          <div
            class="
              flex
              gap-2
              items-center
              bg-[#0271C5]
              cursor-pointer
              text-white
              py-4
              justify-center
            "
          >
            <icon-delete />
            Delete
          </div>
          <div
            class="
              flex
              gap-2
              items-center
              bg-[#17C4B2]
              cursor-pointer
              text-white
              py-4
              justify-center
            "
          >
            <icon-close />
            Close
          </div>
          <div
            class="
              flex
              gap-2
              items-center
              bg-[#0ABC8A]
              cursor-pointer
              text-white
              py-4
              justify-center
            "
          >
            <icon-re-open />
            Re-Open
          </div>

          <div
            class="
              flex
              gap-2
              items-center
              bg-[#024CC5]
              cursor-pointer
              text-white
              py-4
              justify-center
            "
          >
            <svg
              width="25"
              height="25"
              viewBox="0 0 25 25"
              xmlns="http://www.w3.org/2000/svg"
            >
              <g fill="none" fill-rule="evenodd">
                <path fill="#024CC5" d="M0 0h25v25H0z" />
                <g fill="#FFF" fill-rule="nonzero">
                  <path
                    d="M11.586 9.915a.564.564 0 0 0-.797 0l-1.851 1.852-.727-.728a.563.563 0 0 0-.796.796L8.54 12.96a.565.565 0 0 0 .795.001l2.25-2.25a.563.563 0 0 0 0-.796zM11.586 14.415a.564.564 0 0 0-.797 0l-1.851 1.852-.727-.727a.563.563 0 0 0-.796.796L8.54 17.46a.56.56 0 0 0 .796 0l2.25-2.25a.563.563 0 0 0 0-.796zM15.688 10.875h-2.25a.563.563 0 0 0 0 1.125h2.25a.563.563 0 0 0 0-1.125zM15.688 15.375h-2.25a.563.563 0 0 0 0 1.125h2.25a.563.563 0 0 0 0-1.125z"
                  />
                  <path
                    d="M17.375 5.25h-2.25v-.563a.563.563 0 0 0-.563-.562h-1.22A1.692 1.692 0 0 0 11.75 3c-.733 0-1.359.47-1.592 1.125h-1.22a.563.563 0 0 0-.563.563v.562h-2.25C5.505 5.25 5 5.755 5 6.375v13.5C5 20.495 5.505 21 6.125 21h11.25c.62 0 1.125-.505 1.125-1.125v-13.5c0-.62-.505-1.125-1.125-1.125zm-7.875 0h1.125c.31 0 .563-.252.563-.563a.563.563 0 0 1 1.124 0c0 .311.252.563.563.563H14v1.125H9.5V5.25zm7.875 14.625H6.125v-13.5h2.25v.563c0 .31.252.562.563.562h5.624c.311 0 .563-.252.563-.563v-.562h2.25v13.5z"
                  />
                </g>
              </g>
            </svg>
            Report
          </div>
          <div
            class="
              flex
              gap-2
              items-center
              bg-[#1E385E]
              cursor-pointer
              text-white
              py-4
              justify-center
            "
          >
            <svg
              width="25"
              height="25"
              viewBox="0 0 25 25"
              xmlns="http://www.w3.org/2000/svg"
            >
              <g fill="none" fill-rule="evenodd">
                <path fill="#1E385E" d="M0 0h25v25H0z" />
                <g fill="#FFF" fill-rule="nonzero">
                  <path
                    d="M14.584 12.249a3.253 3.253 0 0 0-3.249 3.25 3.253 3.253 0 0 0 3.25 3.249 3.253 3.253 0 0 0 3.249-3.25 3.253 3.253 0 0 0-3.25-3.249zm0 5.308a2.061 2.061 0 0 1-1.814-3.03l1.393 1.393a.596.596 0 0 0 .421.174h1.97a2.062 2.062 0 0 1-1.97 1.463zm.247-2.654-1.219-1.219a2.062 2.062 0 0 1 2.942 1.219h-1.723z"
                  />
                  <path
                    d="M18.09 4.77h-1.712a1.414 1.414 0 0 0-1.31-.885h-.925c-.23-.52-.75-.885-1.355-.885-.604 0-1.125.364-1.355.885h-.925c-.592 0-1.1.366-1.309.884H7.486C6.666 4.77 6 5.436 6 6.255v13.26C6 20.333 6.667 21 7.486 21H18.09c.819 0 1.486-.666 1.486-1.486V6.255c0-.82-.667-1.486-1.486-1.486zm-7.803.527c0-.122.1-.221.221-.221h1.396a.595.595 0 0 0 .595-.596.29.29 0 0 1 .578 0c0 .329.267.596.596.596h1.395c.122 0 .221.099.221.22v1.022c0 .122-.1.22-.221.22h-4.56a.221.221 0 0 1-.22-.22V5.297zm7.804 14.512H7.486a.295.295 0 0 1-.295-.295V6.255c0-.163.132-.295.295-.295h1.61v.358c0 .778.634 1.412 1.412 1.412h4.56c.779 0 1.412-.634 1.412-1.412V5.96h1.61c.163 0 .296.132.296.295v13.26a.295.295 0 0 1-.295.294z"
                  />
                  <path
                    d="M8.365 9.901h4.423a.595.595 0 0 0 0-1.19H8.365a.595.595 0 0 0 0 1.19zM8.365 11.67h2.654a.595.595 0 0 0 0-1.19H8.365a.595.595 0 0 0 0 1.19zM11.614 12.844a.595.595 0 0 0-.595-.595H8.365a.595.595 0 0 0 0 1.19h2.654a.595.595 0 0 0 .595-.595z"
                  />
                </g>
              </g>
            </svg>
            Report
          </div>
          <div
            class="
              flex
              gap-2
              items-center
              bg-[#50BEFF]
              text-white
              py-4
              justify-center
            "
          >
            <icon-email />
            E-mail
          </div>
        </div>

        <table id="activeRunsDetail" class="display" style="width: 100%">
          <thead>
            <tr>
              <th>Part description</th>
              <th>Plate Type</th>
              <th>Chromate</th>
              <th>Topcoat</th>
              <th>Secondary Topcoat</th>

              <!-- <th class="no-sort">Notes</th> -->
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import { reactive, nextTick, ref } from "vue";
const $ = require("jquery");
var dt = require("datatables.net");
import "datatables.net-responsive-dt";
import "datatables.net-rowreorder-dt";
import IconEmail from "@/assets/Icons/iconEmail.vue";
import IconEdit from "@/assets/Icons/iconEdit.vue";
import IconDelete from "@/assets/Icons/iconDelete.vue";
import IconReOpen from "@/assets/Icons/iconReopen.vue";
import IconResult from "@/assets/Icons/iconResult.vue";
import IconPhoto from "@/assets/Icons/iconPhoto.vue";
import IconClose from "@/assets/Icons/iconClose.vue";
export default {
  components: {
    AppLayout: AppLayout,
    IconEmail,
    IconEdit,
    IconDelete,
    IconReOpen,
    IconClose,
    IconResult,
    IconPhoto,
    IconDelete,
    IconEdit,
  },
  props: ["run"],
  setup() {
    const id = Inertia.page.url.split("/")[3];
    const runDetail = ref({});
    const startDate = ref('');
    const gettingData = async () => {
      try {
        const res = await axios.get(`/run/${id}`);
        console.log(res.data);
        runDetail.value = res.data;
        startDate.value = runDetail.value.start_date.slice(0, 10);
        $("#activeRunsDetail").DataTable().destroy();
        await generateDataTableDetail();
      } catch (e) {
        console.log(e);
      }
    };

    const calculateHours = (
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

    const goBack = () => {
      Inertia.replace("/run");
    };
    const generateDataTableDetail = () => {
      nextTick(() => {
        $("#activeRunsDetail").DataTable({
          ordering: true,
          bLengthChange: false,
          pageLength: 10,
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
            url: `/part/getPartsByRun/${runDetail.value.id}`,
          },
          columns: [
            {
              name: "description",
              searchable: true,
              render: function (data, type, row, meta) {
                return "<td>" + row.description  + "</td>";
              },
            },
            {
              name: "plate_type.name",
              searchable: true,
              render: function (data, type, row, meta) {
                return "<td>" + row.plate_type.name + "</td>";
              },
            },
            {
              name: "part.chromate.name",
              searchable: true,
              render: function (data, type, row, meta) {
                return "<td>" + row.chromate.name + " - " + row.primaryPer + " % " + "</td>";
              },
            },
            {
              name: "part.top_coat.name",
              searchable: true,
              render: function (data, type, row, meta) {
                return "<td>" + row.top_coat.name + " - " + row.topCoatPer + " % " + "</td>";
              },
            },
            {
              name: "part.coat.name",
              searchable: true,
              render: function (data, type, row, meta) {
                return "<td>" + row.coat.name + " - " + row.coatPer + " % " + "</td>";
              },
            },
            
          ]
        });
      });
    };
    gettingData();
    return {
      generateDataTableDetail,
      gettingData,
      calculateHours,
      runDetail,
      startDate,
      goBack,
    };
  },
};
</script>

<style>
</style>