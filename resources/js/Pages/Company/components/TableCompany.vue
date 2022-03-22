<template>
  <modal :show="openModalCompany">
    <div class="p-5">
      <form-company
        :countries="countries"
        @generateTable="generateDataTable"
        @closeModal="closeModal"
      />
    </div>
  </modal>
  <div class="container p-2">
    <div class="flex gap-8 items-center mb-5 flex-col md:flex-row">
      <div class="flex gap-8 items-center flex-1 w-full">
        <div
          v-if="
            $page.props.auth.rols[0].id == 1 || $page.props.auth.rols[0].id == 2
          "
        >
          <button @click="openModal"><icon-plus /></button>
        </div>
        <select
          class="w-full p-3 rounded-sm border-[#a2a2a2] text-[#a2a2a2] flex-1"
          @change="changeFilter"
          v-model="filterOption"
        >
          <option value="4">All</option>
          <option value="0">Customer</option>
          <option value="1">Distributor</option>
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
          id="filterCompaniesInputBot"
          placeholder="Search Companies..."
          autocomplete="off"
        />
      </div>
    </div>
    <div class="rounded-lg bg-white p-5">
      <table
        id="tableCompanies"
        class="display"
        style="width: 100%; height: 100%"
      >
        <thead
          v-if="
            $page.props.auth.rols[0].id == 1 || $page.props.auth.rols[0].id == 2
          "
        >
          <tr>
            <th>Company</th>
            <th>Distributor</th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>Zip</th>
            <th>Country</th>
            <th>Phone</th>
            <th>Fax</th>
            <th>Type</th>
            <th class="no-sort">Edit</th>
            <th class="no-sort">Delete</th>
          </tr>
        </thead>
        <thead
          v-if="
            $page.props.auth.rols[0].id != 1 && $page.props.auth.rols[0].id != 2
          "
        >
          <tr>
            <th>Company</th>
            <th>Distributor</th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>Zip</th>
            <th>Country</th>
            <th>Phone</th>
            <th>Fax</th>
            <th>Type</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <confirmation-modal :show="showModalDelete">
      <template v-slot:title>
        <h1>Are you sure that delete this company?</h1>
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
            @click="deleteCompany()"
          >
            Acept
          </button>
        </div>
      </template>
    </confirmation-modal>
  </div>
</template>

<script>
import axios from "axios";
import { ref, nextTick, computed, watch, onMounted } from "vue";
const $ = require("jquery");
import ConfirmationModal from "@/Jetstream/ConfirmationModal.vue";
import Modal from "@/Jetstream/Modal";
import useHelper from "@/composables/useHelper";
import FormCompany from "./FormCompany.vue";
import IconEdit from "@/assets/Icons/iconEdit.vue";
import IconDelete from "@/assets/Icons/iconDelete.vue";
import IconPlus from "@/assets/Icons/iconPlus.vue";
import { useStore } from "vuex";

import { Inertia } from "@inertiajs/inertia";
var dt = require("datatables.net");
import "datatables.net-responsive-dt";
import "datatables.net-rowreorder-dt";

export default {
  props: ["countries"],
  components: {
    modal: Modal,
    confirmationModal: ConfirmationModal,
    FormCompany,
    IconEdit,
    IconDelete,
    IconPlus,
  },
  setup() {
    const store = useStore();
    const companiesTable = ref(
      computed(() => store.state.companies.tableCompanies)
    );
    const rolConditional = ref(Inertia.page.props.auth.rols[0].id);
    const showModalDelete = ref(false);
    const { makeToast } = useHelper();
    const openModalCompany = ref(false);
    const filterOption = ref("4");

    const openModalEditClick = async (id) => {
      const data = await store.dispatch("companies/getDistributors", id);
      store.commit("companies/setDistributors", data);
      store.commit("companies/setFormCompany", id);
      openModalCompany.value = true;
    };
    const openModalDeleteClick = (id) => {
      store.commit("companies/setFormCompany", id);
      showModalDelete.value = true;
    };

    const deleteCompany = async () => {
      try {
        const id = store.state.companies.form.id;
        const res = await store.dispatch("companies/deleteCompany", id);
        const { ok, message } = res.data;
        if (ok) {
          showModalDelete.value = false;
          makeToast(message);
          store.commit("companies/setFormCompany", 0);
          store.commit("companies/deleteItem", id);
          await generateDataTable();
        } else {
          makeToast(message, "error");
        }
      } catch (e) {
        makeToast("Error", "error");
        console.log(e);
      }
    };
    const closeModalDelete = () => (showModalDelete.value = false);
    const gettingData = async (type = 4) => {
      // const data = await store.dispatch("companies/getCompanies", type);

      await generateDataTable(type);
    };
    const openModal = async () => {
      store.commit("companies/setFormCompany", 0);
      const data = await store.dispatch("companies/getDistributors", 0);
      store.commit("companies/setDistributors", data);
      openModalCompany.value = true;
    };

    const closeModal = () => {
      store.commit("companies/setFormCompany", 0);
      openModalCompany.value = false;
    };
    const changeFilter = async () => {
      await gettingData(filterOption.value);
    };
    $(document).ready(function () {
      $("#filterCompaniesInputBot")
        .off()
        .keyup(function () {
          $("#tableCompanies").DataTable().search(this.value).draw();
        });
    });
    const colNoAdmin = [
      {
        targets: 1,
        name: "name",
        searchable: true,
        render: function (data, type, row, meta) {
          return "<span>" + row.name + "</span>";
        },
      },
      {
        targets: 2,
        name: "children.name",
        searchable: true,
        render: function (data, type, row, meta) {
          if (row.company_id === null) {
            return "<span>None</span>";
          } else {
            if (row.children !== null) {
              return "<span>" + row.children.name + "</span>";
            } else {
              return "<span></span>";
            }
          }
        },
      },
      {
        targets: 3,
        searchable: true,
        name: "address",
        render: function (data, type, row, meta) {
          if (row.address != null) {
            return "<span>" + row.address + "</span>";
          }
          return "<span></span>";
        },
      },
      {
        targets: 4,
        searchable: true,
        name: "city",
        render: function (data, type, row, meta) {
          if (row.city != null) {
            return "<span>" + row.city + "</span>";
          }
          return "<span></span>";
        },
      },
      {
        targets: 5,
        searchable: true,
        name: "state",
        render: function (data, type, row, meta) {
          if (row.state != null) {
            return "<span>" + row.state + "</span>";
          }
          return "<span></span>";
        },
      },
      {
        targets: 6,
        searchable: true,
        name: "zip",
        render: function (data, type, row, meta) {
          if (row.zip != null) {
            return "<span>" + row.zip + "</span>";
          }
          return "<span></span>";
        },
      },
      {
        targets: 7,
        searchable: true,
        name: "country.name",
        render: function (data, type, row, meta) {
          if (row.country != null) {
            return `<span>${row.country?.name}</span> `;
          }
          return `<span></span>`;
        },
      },
      {
        targets: 8,
        searchable: true,
        name: "phone",
        render: function (data, type, row, meta) {
          if (row.phone != null) {
            return "<span>" + row.phone + "</span>";
          }
          return "<span></span>";
        },
      },
      {
        targets: 9,
        render: function (data, type, row, meta) {
          if (row.fax === null) {
            return "<span></span>";
          } else {
            return "<span>" + row.fax + "</span>";
          }
        },
      },
      {
        targets: 10,
        render: function (data, type, row, meta) {
          return row.customer == 1
            ? "<span>Customer</span>"
            : "<span>Distributor</span>";
        },
      },
    ];
    const colAdmin = [
      {
        targets: 1,
        name: "name",
        searchable: true,
        render: function (data, type, row, meta) {
          return "<span>" + row.name + "</span>";
        },
      },
      {
        targets: 2,
        name: "children.name",
        searchable: true,
        render: function (data, type, row, meta) {
          if (row.company_id === null) {
            return "<span>None</span>";
          } else {
            if (row.children !== null) {
              return "<span>" + row.children.name + "</span>";
            } else {
              return "<span></span>";
            }
          }
        },
      },
      {
        targets: 3,
        searchable: true,
        name: "address",
        render: function (data, type, row, meta) {
          if (row.address != null) {
            return "<span>" + row.address + "</span>";
          }
          return "<span></span>";
        },
      },
      {
        targets: 4,
        searchable: true,
        name: "city",
        render: function (data, type, row, meta) {
          if (row.city != null) {
            return "<span>" + row.city + "</span>";
          }
          return "<span></span>";
        },
      },
      {
        targets: 5,
        searchable: true,
        name: "state",
        render: function (data, type, row, meta) {
          if (row.state != null) {
            return "<span>" + row.state + "</span>";
          }
          return "<span></span>";
        },
      },
      {
        targets: 6,
        searchable: true,
        name: "zip",
        render: function (data, type, row, meta) {
          if (row.zip != null) {
            return "<span>" + row.zip + "</span>";
          }
          return "<span></span>";
        },
      },
      {
        targets: 7,
        searchable: true,
        name: "country.name",
        render: function (data, type, row, meta) {
          if (row.country != null) {
            return `<span>${row.country?.name}</span> `;
          }
          return `<span></span>`;
        },
      },
      {
        targets: 8,
        searchable: true,
        name: "phone",
        render: function (data, type, row, meta) {
          if (row.phone != null) {
            return "<span>" + row.phone + "</span>";
          }
          return "<span></span>";
        },
      },
      {
        targets: 9,
        render: function (data, type, row, meta) {
          if (row.fax === null) {
            return "<span></span>";
          } else {
            return "<span>" + row.fax + "</span>";
          }
        },
      },
      {
        targets: 10,
        render: function (data, type, row, meta) {
          return row.customer == 1
            ? "<span>Customer</span>"
            : "<span>Distributor</span>";
        },
      },
      {
        targets: 11,
        render: function (data, type, row, meta) {
          return (
            '<button class="openmodaleditclick" cName=' +
            row.id +
            '> <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"> <g fill="none" fill-rule="evenodd"> <path fill="#227D9E" d="M0 0h25v25H0z"/> <g fill="#FFF" fill-rule="nonzero" stroke="#FFF" stroke-width=".5"> <path d="M18.218 13.773a.353.353 0 0 0-.357.35v3.103a1.06 1.06 0 0 1-1.07 1.048H6.783c-.59 0-1.069-.47-1.07-1.048V8.117c.001-.579.48-1.048 1.07-1.049H9.95a.353.353 0 0 0 .357-.35c0-.192-.16-.349-.357-.349H6.783C5.8 6.37 5.001 7.152 5 8.117v9.109c.001.965.799 1.746 1.783 1.748h10.008c.985-.002 1.783-.783 1.784-1.748v-3.104c0-.193-.16-.35-.357-.35z"/> <path d="M18.434 5.47a1.628 1.628 0 0 0-2.27 0L9.8 11.706a.348.348 0 0 0-.091.154l-.837 2.96a.345.345 0 0 0 .091.34.362.362 0 0 0 .348.09l3.02-.82a.358.358 0 0 0 .158-.09l6.363-6.235a1.552 1.552 0 0 0 0-2.225l-.418-.41zm-7.857 6.463 5.208-5.104 1.68 1.646-5.208 5.104-1.68-1.646zm-.335.66 1.342 1.315-1.856.504.514-1.82zm8.106-4.983-.379.371-1.68-1.646.38-.37a.905.905 0 0 1 1.26 0l.419.41a.863.863 0 0 1 0 1.235z"/> </g> </g></svg> </button>'
          );
        },
      },
      {
        targets: 12,
        render: function (data, type, row, meta) {
          return (
            '<button class="openmodaldeleteclick" cName=' +
            row.id +
            '> <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"> <g fill="none" fill-rule="evenodd"> <path fill="#0271C5" d="M0 0h25v25H0z"/> <g fill="#FFF" fill-rule="nonzero"> <path d="m18.495 8.13-.312-.927a.869.869 0 0 0-.826-.59h-2.62v-.846A.77.77 0 0 0 13.966 5h-2.4a.77.77 0 0 0-.772.767v.846h-2.62a.869.869 0 0 0-.826.59l-.311.926a.69.69 0 0 0 .096.625c.13.18.343.288.567.288h.326l.717 8.794A1.279 1.279 0 0 0 10.014 19h5.648c.66 0 1.218-.511 1.272-1.164l.717-8.794h.18a.703.703 0 0 0 .568-.288.69.69 0 0 0 .096-.625zM11.62 5.82h2.29v.793h-2.29V5.82zm4.49 11.95a.45.45 0 0 1-.448.41h-5.648a.45.45 0 0 1-.447-.41l-.712-8.728h7.966l-.712 8.728zM7.876 8.222l.255-.76a.043.043 0 0 1 .041-.029h9.184c.019 0 .035.012.041.03l.256.76H7.877z"/> <path d="M14.79 17.741h.022c.218 0 .401-.17.412-.388l.389-7.394a.412.412 0 0 0-.392-.43.412.412 0 0 0-.434.387l-.389 7.394a.411.411 0 0 0 .392.431zM10.324 17.354a.412.412 0 0 0 .436.387.411.411 0 0 0 .39-.432l-.406-7.394a.412.412 0 0 0-.436-.387.411.411 0 0 0-.39.432l.406 7.394zM12.77 17.742a.412.412 0 0 0 .413-.41V9.937a.412.412 0 0 0-.413-.41.412.412 0 0 0-.414.41v7.393c0 .227.185.41.414.41z"/> </g> </g></svg> </button>'
          );
        },
      },
    ];
    const generateDataTable = (type) => {
      $("#tableCompanies").DataTable().clear().destroy();
      nextTick(() => {
        $("#tableCompanies").DataTable({
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
          responsive: true,
          language: {
            paginate: {
              next: `<svg class="arrow_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="15" height="14" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><g transform="rotate(270 10 10)"><path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="white"/></g></svg>`, // or '→'
              previous: `<svg class="arrow_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="15" height="14" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><g transform="rotate(90 10 10)"><path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="white"/></g></svg>`, // or '←'
            },
            info: "Showing results _START_ to _END_ from _TOTAL_",
          },
          ajax: {
            url: `/company/getCompanies/${type}`,
          },
          stateSaveCallback: function (settings, data) {
            const state = settings.aoData;
            let arr = [];
            state.forEach((element) => {
              arr.push(element._aData);
            });
            store.commit("companies/setDataTable", arr);
          },
          columns:
            rolConditional.value == 1 || rolConditional.value == 2
              ? colAdmin
              : colNoAdmin,
          drawCallback: function () {
            $("#tableCompanies").on(
              "click",
              "[class*=openmodaleditclick]",
              function (e) {
                const openmodaleditclickStr =
                  e.currentTarget.attributes[1].value;
                const openmodaleditclickId = Number(openmodaleditclickStr);
                openModalEditClick(openmodaleditclickId);
              }
            );
            $("#tableCompanies").on(
              "click",
              "[class*=openmodaldeleteclick]",
              function (e) {
                const openmodaldeleteclickStr =
                  e.currentTarget.attributes[1].value;
                const openmodaldeleteclickId = Number(openmodaldeleteclickStr);
                openModalDeleteClick(openmodaldeleteclickId);
              }
            );
          },
        });
      });
    };
    onMounted(() => {
      gettingData();
    });

    return {
      companiesTable,
      openModalEditClick,
      openModalDeleteClick,
      showModalDelete,
      closeModalDelete,
      deleteCompany,
      openModalCompany,
      openModal,
      closeModal,
      generateDataTable,
      filterOption,
      changeFilter,
    };
  },
};
</script>

<style>
</style>