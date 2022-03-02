<template>
  <h1 class="text-center font-bold text-2xl text-[#3b4559]">New Part</h1>
  <form @submit.prevent="submitForm">
    <div>
      <label class="text-[#3b4559] font-semibold">Plate Type</label>
      <div class="flex w-full justify-between gap-2">
        <div class="w-full">
          <multi-select
            :options="plateTypes"
            class="w-full multiselect-blue"
            v-model="form.plate_types_id"
            :searchable="true"
            :class="{ 'border-red-500': v$.plate_types_id.$error }"
            placeholder="Select Plate Type"
          />
          <p
            v-for="error of v$.plate_types_id.$errors"
            :key="error.$uid"
            class="text-red-400"
          >
            {{ error.$message }}
          </p>
        </div>
        <div class="w-[180px]">
          <multi-select
            :options="[
              {
                value: 1,
                label: 'micra',
              },
              {
                value: 2,
                label: 'mils',
              },
            ]"
            class="w-full"
            v-model="form.typePlateThick"
            :searchable="true"
            placeholder="Select Type "
          />
          <p
            v-for="error of v$.typePlateThick.$errors"
            :key="error.$uid"
            class="text-red-400"
          >
            {{ error.$message }}
          </p>
        </div>
        <div>
          <input
            type="number"
            step=".01"
            class="w-[80px]"
            v-model="form.plateThick"
            :class="{ 'border-red-500': v$.plateThick.$error }"
          />
          <p
            v-for="error of v$.plateThick.$errors"
            :key="error.$uid"
            class="text-red-400"
          >
            {{ error.$message }}
          </p>
        </div>
      </div>
    </div>
    <div>
      <label class="text-[#3b4559] font-semibold">Chromate</label>
      <div class="flex w-full flex-col md:flex-row gap-2">
        <div class="w-full">
          <multi-select
            :options="chromates"
            class="w-full"
            v-model="form.primaryCoatId"
            :searchable="true"
            placeholder="Select Chromates "
          />
        </div>
        <div class="flex gap-2">
          <div>
            <input
              type="number"
              step=".01"
              class="w-[60px]"
              v-model="form.primaryPer"
            />
            <span class="text-center block pt-1 text-[#3b4559]">%</span>
          </div>

          <div>
            <input
              type="number"
              step=".01"
              class="w-[60px]"
              v-model="form.primaryTemp"
            />
            <span class="text-center block pt-1 text-[#3b4559]">°F</span>
          </div>
          <div>
            <input
              type="number"
              step=".01"
              class="w-[60px]"
              v-model="form.primaryPH"
            />
            <span class="text-center block pt-1 text-[#3b4559]">pH</span>
          </div>
          <div>
            <input
              type="number"
              step=".01"
              class="w-[60px]"
              v-model="form.primaryDiptime"
            />
            <span class="text-center block pt-1 text-[#3b4559]">sec</span>
          </div>
        </div>
      </div>
    </div>
    <div>
      <label class="text-[#3b4559] font-semibold">TopCoat</label>
      <div class="flex w-full flex-col md:flex-row gap-2">
        <div class="w-full">
          <multi-select
            :options="topCoats"
            class="w-full"
            v-model="form.topCoatId"
            :searchable="true"
            placeholder="Select TopCoat"
          />
        </div>
        <div class="flex flex-col">
          <div class="flex gap-2">
            <div>
              <input
                type="number"
                step=".01"
                class="w-[60px]"
                v-model="form.topCoatPer"
              />
              <span class="text-center block pt-1 text-[#3b4559]">%</span>
            </div>

            <div>
              <input
                type="number"
                step=".01"
                class="w-[60px]"
                v-model="form.topCoatTemp"
              />
              <span class="text-center block pt-1 text-[#3b4559]">°F</span>
            </div>
            <div>
              <input
                type="number"
                step=".01"
                class="w-[60px]"
                v-model="form.topCoatPH"
              />
              <span class="text-center block pt-1 text-[#3b4559]">pH</span>
            </div>
            <div>
              <input
                type="number"
                step=".01"
                class="w-[60px]"
                v-model="form.topCoatDiptime"
              />
              <span class="text-center block pt-1 text-[#3b4559]">sec</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div>
      <label class="text-[#3b4559] font-semibold">Secondary Topcoat</label>
      <div class="flex w-full flex-col md:flex-row gap-2">
        <div class="w-full">
          <multi-select
            :options="secondaryCoats"
            class="w-full"
            v-model="form.coatId"
            :searchable="true"
            placeholder="Select Secondary TopCoat"
          />
        </div>
        <div class="flex gap-2">
          <div>
            <input
              type="number"
              step=".01"
              class="w-[60px]"
              v-model="form.coatPer"
            />
            <span class="text-center block pt-1 text-[#3b4559]">%</span>
          </div>

          <div>
            <input
              type="number"
              step=".01"
              class="w-[60px]"
              v-model="form.coatTemp"
            />
            <span class="text-center block pt-1 text-[#3b4559]">°F</span>
          </div>
          <div>
            <input
              type="number"
              step=".01"
              class="w-[60px]"
              v-model="form.coatPH"
            />
            <span class="text-center block pt-1 text-[#3b4559]">pH</span>
          </div>
          <div>
            <input
              type="number"
              step=".01"
              class="w-[60px]"
              v-model="form.coatDiptime"
            />
            <span class="text-center block pt-1 text-[#3b4559]">sec</span>
          </div>
        </div>
      </div>
    </div>
    <div>
      <label class="text-[#3b4559] font-semibold">Description</label>
      <textarea
        cols="30"
        rows="5"
        class="w-full"
        v-model="form.description"
      ></textarea>
    </div>

    <div class="flex flex-col md:flex-row justify-around w-full gap-4">
      <button
        type="button"
        @click="closeModal"
        class="
          bg-red-600
          hover:bg-red-800
          rounded
          w-full
          py-5
          text-white
          px-3
          mt-2
        "
      >
        Cancel
      </button>
      <div v-if="!loading" class="w-full">
        <button
          class="
            bg-primary
            rounded
            w-full
            py-5
            text-white
            px-3
            mt-2
            hover:bg-primary-600
          "
        >
          Save
        </button>
      </div>
      <div v-if="loading" class="w-full">
        <button
          class="
            bg-primary
            rounded
            w-full
            py-5
            text-white
            px-3
            mt-2
            hover:bg-primary-600
          "
          disabled
        >
          <div
            className="animate-spin rounded-full h-6 w-6 border-b-2 border-t-2 border-white inline-block"
          ></div>
        </button>
      </div>
    </div>
  </form>
</template>

<script>
import useHelper from "@/composables/useHelper";
import { required, helpers } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import axios from "axios";
import { ref, reactive } from "vue";
import Multiselect from "@vueform/multiselect";
const isDiferentZero = (value) => {
  return value != 0 && value != null;
};
export default {
  emits: ["closeModalNewPart", "generateDataTable", "gettinDataParts"],
  components: {
    multiSelect: Multiselect,
  },
  props: [
    "plateMethods",
    "topCoats",
    "chromates",
    "secondaryCoats",
    "plateTypes",
    "partUpdate",
    "partsTable",
    "run_id",
    "parts",
  ],
  setup(props, { emit }) {
    const { makeToast } = useHelper();
    const { run_id, partsTable } = props;
    const parts = ref(partsTable);
    const loading = ref(false);
    const form = reactive({
      id: 0,
      description: "",
      run_id: run_id,
      plateThick: 0,
      typePlateThick: 0,
      coatId: 0,
      primaryCoatId: 0,
      topCoatId: 0,
      plate_types_id: 0,
      topCoatPer: "",
      topCoatPer: "",
      topCoatTemp: "",
      topCoatPH: "",
      topCoatDiptime: "",
      primaryPer: "",
      primaryTemp: "",
      primaryPH: "",
      primaryDiptime: "",
      coatPer: "",
      coatTemp: "",
      coatPH: "",
      coatDiptime: "",
    });
    const rules = {
      plate_types_id: {
        isDiferentZero: helpers.withMessage(
          "You must select an option",
          isDiferentZero
        ),
      },

      typePlateThick: {
        isDiferentZero: helpers.withMessage(
          "You must select an option",
          isDiferentZero
        ),
      },
      plateThick: {
        required,
      },
    };
    const v$ = useVuelidate(rules, form);
    const closeModal = () => {
      emit("closeModalNewPart");
    };

    const submitForm = async () => {
      try {
        const isFormCorrect = await v$.value.$validate();
        if (!isFormCorrect) return;
        let res;
        loading.value = true;
        res = await axios.post(`/part`, form);
        loading.value = false;
        const { ok, message } = res.data;
        if (ok) {
          makeToast(message);
          emit("gettinDataParts");
          emit("closeModalNewPart");
        } else {
          makeToast("An error has occurred", "error");
        }
      } catch (e) {
        loading.value = false;
        makeToast(e, "error");
      }
    };

    return {
      form,
      closeModal,
      v$,
      submitForm,
      loading,
    };
  },
  watch: {
    run() {
      this.parts = this.partsTable;
    },
  },
};
</script>
