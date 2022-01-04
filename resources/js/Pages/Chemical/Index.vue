<template>
  <app-layout title="Chemicals">
    <div class="container pt-7 mx-auto">
      <h1 class="text-center text-2xl p-5 font-bold text-[#3b4559]">
        List of Chemicals
      </h1>
      <div class="flex gap-8 items-center mb-5 pt-5 px-4 md:px-20">
        <button @click="openModal"><icon-plus /></button>
        <select class="w-full" v-model="selected" @change="filterChemicals">
          <option value="0" selected>All Chemical</option>
          <option value="1">Plating</option>
          <option value="2">Chromate</option>
          <option value="3">TopCoat</option>
          <option value="4">Secondary TopCoat</option>
        </select>
      </div>
      <div class="mt-10">
        <div class="rounded-lg shadow-lg p-5">
          <table
            id="chemicalTable"
            class="display"
            style="width: 100%; height: 100%"
          >
            <thead>
              <tr>
                <td>Name</td>
                <td>Type</td>
                <td class="no-sort">Edit</td>
                <td class="no-sort">Delete</td>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>

      <modal :show="show">
        <form @submit.prevent="submit" class="container mx-auto p-5">
          <p class="text-xl font-bold text-center">{{ modalTitle }}</p>
          <div>
            <div>
              <label for="">Name</label>
              <input
                type="text"
                v-model="form.name"
                class="w-full py-4 px-5"
                :class="{ 'border-red-500': v$.name.$error }"
              />
              <p
                v-for="error of v$.name.$errors"
                :key="error.$uid"
                class="text-red-400"
              >
                {{ error.$message }}
              </p>
            </div>
            <div>
              <label for="">Type</label>
              <multi-select
                :options="typesArray"
                class="w-full"
                v-model="form.type"
                :searchable="true"
                placeholder="Select Type"
              />
              <p
                v-for="error of v$.type.$errors"
                :key="error.$uid"
                class="text-red-400"
              >
                {{ error.$message }}
              </p>
            </div>
          </div>
          <div
            class="
              flex flex-col
              md:flex-row md:justify-between
              w-full
              md:gap-4
              pt-16
            "
          >
            <button
              type="button"
              class="
                bg-red-600
                hover:bg-red-800
                rounded-sm
                w-full
                py-5
                text-white
                px-3
                mt-5
              "
              @click="closeModal"
            >
              Cancel
            </button>
            <button
              class="
                bg-primary
                rounded-sm
                p-5
                font-bold
                text-white
                mt-5
                block
                hover:bg-primary-600
                w-full
              "
            >
              Save
            </button>
          </div>
        </form>
      </modal>
      <confirmation-modal :show="showDeleteModal">
        <template v-slot:title>
          <h1>Are you sure that delete this chemical?</h1>
        </template>
        <template v-slot:content>
          <div class="flex justify-center">
            <button
              class="bg-red-500 p-4 text-white rounded-md mr-4"
              @click="closeModalChemical"
            >
              Cancel
            </button>
            <button
              class="bg-green-500 p-4 text-white rounded-md"
              @click="deleteChemical"
            >
              Acept
            </button>
          </div>
        </template>
      </confirmation-modal>
    </div>
  </app-layout>
</template>

<script>
import { ref, nextTick, onMounted } from "vue";
import dt from "datatables.net";
import axios from "axios";
import { required } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import AppLayout from "../../Layouts/AppLayout.vue";
import Modal from "../../Jetstream/Modal.vue";
import ConfirmationModal from "../../Jetstream/ConfirmationModal.vue";
import useHelper from "@/composables/useHelper";
import Multiselect from "@vueform/multiselect";
const $ = require("jquery");
import IconEdit from "@/assets/Icons/iconEdit.vue";
import IconDelete from "@/assets/Icons/iconDelete.vue";
import IconPlus from "@/assets/Icons/iconPlus.vue";
export default {
  components: {
    AppLayout: AppLayout,
    modal: Modal,
    confirmationModal: ConfirmationModal,
    multiSelect: Multiselect,
    IconEdit,
    IconDelete,
    IconPlus,
  },
  setup() {
    const { makeToast } = useHelper();
    let selected = ref("0");
    let chemicalList = ref([]);
    const chemical = ref(null);
    const idGlobal = ref(0);
    let show = ref(false);
    let showDeleteModal = ref(false);
    let modalTitle = ref("");
    let form = ref({
      id: 0,
      name: "",
      type: "",
    });

    const rules = {
      name: {
        required,
      },
      type: {
        required,
      },
    };

    const v$ = useVuelidate(rules, form);

    const getChemical = async (type) => {
      try {
        // const res = await axios.get(`/chemical/getChemicals/${type}`);
        // chemicalList.value = res.data;

        $("#chemicalTable").DataTable().destroy();
        generateDataTable(type);
      } catch (error) {
        console.log(error);
        makeToast(
          "There is an error to load chemicals. Try again please.",
          "error"
        );
      }
    };
    const typesArray = ref([
      { value: 1, label: "Plating" },
      { value: 2, label: "Chromate" },
      { value: 3, label: "TopCoat" },
      { value: 4, label: "Secondary TopCoat" },
    ]);

    const submit = async () => {
      // if ((form.name === "") | (form.type === "")) return;
      const isFormCorrect = await v$.value.$validate();
      if (!isFormCorrect) return;
      let res;
      if (form._value.id !== 0) {
        res = await axios.put(`/chemical/${form._value.id}`, form._value);
      } else {
        res = await axios.post("/chemical", form._value);
      }
      const { ok, message, value } = res.data;
      if (ok === true) {
        makeToast(message);
        if (selected._value !== "0") {
          selected._value = form._value.type;
          getChemical(form._value.type);
        } else {
          filterChemicals();
        }
        reset();
      } else {
        makeToast(message, "error");
      }
    };

    const filterChemicals = () => {
      getChemical(selected._value);
    };
    const openModal = () => {
      modalTitle.value = "New Chemical";
      form._value.id = 0;
      show.value = true;
    };
    const closeModal = () => {
      show.value = false;
      reset();
    };
    const closeModalChemical = () => {
      showDeleteModal.value = false;
    };

    const findChemical = (id) => {
      const searchId = Number(id);
      chemical.value = chemicalList.value.find((chemical) => {
        if (chemical.id === searchId) {
          return chemical;
        }
      });

      idGlobal.value = Number(id);
    };

    const editChemical = (chemicalId) => {
      modalTitle.value = "Update Chemical";
      findChemical(chemicalId);
      show.value = true;
      form._value.id = chemical.value.id;
      form._value.name = chemical.value.name;
      form._value.type = chemical.value.type;
    };
    const deleteChemicalModal = (chemicalId) => {
      findChemical(chemicalId);
      showDeleteModal.value = true;
      form._value.id = chemical.value.id;
      form._value.type = chemical.value.type;
    };

    const deleteChemical = async (id) => {
      try {
        const resp = await axios.delete(`/chemical/${form._value.id}`);
        const { ok, message, value } = resp.data;
        if (ok === true) {
          makeToast(message);
          showDeleteModal.value = false;
          if (selected._value !== "0") {
            selected._value = form._value.type;
            getChemical(form._value.type);
          } else {
            filterChemicals();
          }
          reset();
        } else {
          makeToast(message, "error");
        }
      } catch (error) {
        makeToast(message, "error");
      }
    };

    const generateDataTable = (type) => {
      nextTick(() => {
        $("#chemicalTable").DataTable({
          scrollY: 350,
          ordering: true,
          bLengthChange: false,
          pageLength: 5,
          processing: true,
          serverSide: true,
          stateSave: true,
          language: {
            paginate: {
              next: `→`, // or '→'
              previous: `←`, // or '←'
            },
            info: "Showing results page _PAGE_ of _PAGES_",
          },
          ajax: `/chemical/getChemicals/${type}`,
          stateSaveCallback: function (settings, data) {
            const state = settings.aoData;
            let arr = [];
            state.forEach((element) => {
              arr.push(element._aData);
            });
            chemicalList.value = arr;
          },
          columns: [
            {
              targets: 1,
              name: "name",
              searchable: true,
              render: function (data, type, row, meta) {
                return row.name;
              },
            },
            {
              targets: 2,
              searchable: false,
              render: function (data, type, row, meta) {
                if (row.type === 1) {
                  return "<span>Plating</span>";
                }
                if (row.type === 2) {
                  return "<span>Chromate</span>";
                }
                if (row.type === 3) {
                  return "<span>TopCoat</span>";
                }
                if (row.type === 4) {
                  return "<span>Secondary</span>";
                }
              },
            },
            {
              targets: 3,
              searchable: false,
              render: function (data, type, row, meta) {
                return (
                  '<button class="editchemical" cName=' +
                  row.id +
                  '>  <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">    <g fill="none" fill-rule="evenodd">        <path fill="#227D9E" d="M0 0h25v25H0z"/>        <g fill="#FFF" fill-rule="nonzero" stroke="#FFF" stroke-width=".5">            <path d="M18.218 13.773a.353.353 0 0 0-.357.35v3.103a1.06 1.06 0 0 1-1.07 1.048H6.783c-.59 0-1.069-.47-1.07-1.048V8.117c.001-.579.48-1.048 1.07-1.049H9.95a.353.353 0 0 0 .357-.35c0-.192-.16-.349-.357-.349H6.783C5.8 6.37 5.001 7.152 5 8.117v9.109c.001.965.799 1.746 1.783 1.748h10.008c.985-.002 1.783-.783 1.784-1.748v-3.104c0-.193-.16-.35-.357-.35z"/>            <path d="M18.434 5.47a1.628 1.628 0 0 0-2.27 0L9.8 11.706a.348.348 0 0 0-.091.154l-.837 2.96a.345.345 0 0 0 .091.34.362.362 0 0 0 .348.09l3.02-.82a.358.358 0 0 0 .158-.09l6.363-6.235a1.552 1.552 0 0 0 0-2.225l-.418-.41zm-7.857 6.463 5.208-5.104 1.68 1.646-5.208 5.104-1.68-1.646zm-.335.66 1.342 1.315-1.856.504.514-1.82zm8.106-4.983-.379.371-1.68-1.646.38-.37a.905.905 0 0 1 1.26 0l.419.41a.863.863 0 0 1 0 1.235z"/>        </g>    </g></svg> </button>'
                );
              },
            },
            {
              targets: 4,
              searchable: false,
              render: function (data, type, row, meta) {
                return (
                  '<button class="deletechemical" cName=' +
                  row.id +
                  '>  <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"> <g fill="none" fill-rule="evenodd"> <path fill="#0271C5" d="M0 0h25v25H0z"/> <g fill="#FFF" fill-rule="nonzero"> <path d="m18.495 8.13-.312-.927a.869.869 0 0 0-.826-.59h-2.62v-.846A.77.77 0 0 0 13.966 5h-2.4a.77.77 0 0 0-.772.767v.846h-2.62a.869.869 0 0 0-.826.59l-.311.926a.69.69 0 0 0 .096.625c.13.18.343.288.567.288h.326l.717 8.794A1.279 1.279 0 0 0 10.014 19h5.648c.66 0 1.218-.511 1.272-1.164l.717-8.794h.18a.703.703 0 0 0 .568-.288.69.69 0 0 0 .096-.625zM11.62 5.82h2.29v.793h-2.29V5.82zm4.49 11.95a.45.45 0 0 1-.448.41h-5.648a.45.45 0 0 1-.447-.41l-.712-8.728h7.966l-.712 8.728zM7.876 8.222l.255-.76a.043.043 0 0 1 .041-.029h9.184c.019 0 .035.012.041.03l.256.76H7.877z"/> <path d="M14.79 17.741h.022c.218 0 .401-.17.412-.388l.389-7.394a.412.412 0 0 0-.392-.43.412.412 0 0 0-.434.387l-.389 7.394a.411.411 0 0 0 .392.431zM10.324 17.354a.412.412 0 0 0 .436.387.411.411 0 0 0 .39-.432l-.406-7.394a.412.412 0 0 0-.436-.387.411.411 0 0 0-.39.432l.406 7.394zM12.77 17.742a.412.412 0 0 0 .413-.41V9.937a.412.412 0 0 0-.413-.41.412.412 0 0 0-.414.41v7.393c0 .227.185.41.414.41z"/> </g> </g></svg> </button>'
                );
              },
            },
          ],
          drawCallback: function () {
            $(".editchemical").on("click", function (e) {
              editChemical(e.currentTarget.attributes[1].value);
            });
            $(".deletechemical").on("click", function (e) {
              deleteChemicalModal(e.currentTarget.attributes[1].value);
            });
          },
        });
      });
    };

    const reset = () => {
      showDeleteModal.value = false;
      show.value = false;
      form._value.id = 0;
      form._value.name = "";
      form._value.type = "";
    };

    onMounted(() => {
      getChemical(0);
    });

    return {
      selected,
      chemicalList,
      chemical,
      show,
      showDeleteModal,
      modalTitle,
      form,
      v$,
      getChemical,
      submit,
      filterChemicals,
      openModal,
      closeModal,
      closeModalChemical,
      editChemical,
      deleteChemicalModal,
      deleteChemical,
      generateDataTable,
      reset,
      typesArray,
    };
  },
};
</script>

<style scoped>
.no-sort::after {
  display: none !important;
}
.no-sort {
  pointer-events: none !important;
  cursor: default !important;
  background-image: none !important;
}
</style>
