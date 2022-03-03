<template>
  <h1 class="text-center font-bold text-2xl text-[#3b4559]">Update Part</h1>
  <form @submit.prevent="submitForm">
    <div>
      <label class="text-[#3b4559] font-semibold">Plate Type</label>
      <div class="flex w-full justify-between gap-2">
        <div class="w-full">
          <multi-select
            :options="plateTypes"
            class="w-full"
            v-model="form.plate_types_id"
            :searchable="true"
            placeholder="Select Plate Type"
          />
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
            step=".01"
            type="number"
            class="w-[80px]"
            v-model="form.plateThick"
            :class="{ 'border-red-500': v$.plateThick.$error }"
          />
          <span class="text-center block pt-1 text-[#3b4559]">mil</span>
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
              class="w-[60px] disabled:bg-gray-100"
              v-model="form.primaryPer"
              :disabled="form.primaryCoatId == '' || form.primaryCoatId == null"
            />
            <span class="text-center block pt-1 text-[#3b4559]">%</span>
          </div>

          <div>
            <input
              type="number"
              step=".01"
              class="w-[60px] disabled:bg-gray-100"
              :disabled="form.primaryCoatId == '' || form.primaryCoatId == null"
              v-model="form.primaryTemp"
            />
            <span class="text-center block pt-1 text-[#3b4559]">°F</span>
          </div>
          <div>
            <input
              type="number"
              step=".01"
              class="w-[60px] disabled:bg-gray-100"
              :disabled="form.primaryCoatId == '' || form.primaryCoatId == null"
              v-model="form.primaryPH"
            />
            <span class="text-center block pt-1 text-[#3b4559]">pH</span>
          </div>
          <div>
            <input
              type="number"
              step=".01"
              class="w-[60px] disabled:bg-gray-100"
              v-model="form.primaryDiptime"
              :disabled="form.primaryCoatId == '' || form.primaryCoatId == null"
            />
            <span class="text-center block pt-1 text-[#3b4559]">sec</span>
          </div>
        </div>
      </div>
    </div>
    <div>
      <label class="text-[#3b4559] font-semibold">TopCoat</label>
      <div class="flex w-full flex-col md:flex-row justify-around gap-2">
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
                class="w-[60px] disabled:bg-gray-100"
                v-model="form.topCoatPer"
                :disabled="form.topCoatId == '' || form.topCoatId == null"
              />
              <span class="text-center block pt-1 text-[#3b4559]">%</span>
            </div>

            <div>
              <input
                type="number"
                step=".01"
                class="w-[60px] disabled:bg-gray-100"
                v-model="form.topCoatTemp"
                :disabled="form.topCoatId == '' || form.topCoatId == null"
              />
              <span class="text-center block pt-1 text-[#3b4559]">°F</span>
            </div>
            <div>
              <input
                type="number"
                step=".01"
                class="w-[60px] disabled:bg-gray-100"
                v-model="form.topCoatPH"
                :disabled="form.topCoatId == '' || form.topCoatId == null"
              />
              <span class="text-center block pt-1 text-[#3b4559]">pH</span>
            </div>
            <div>
              <input
                type="number"
                step=".01"
                class="w-[60px] disabled:bg-gray-100"
                v-model="form.topCoatDiptime"
                :disabled="form.topCoatId == '' || form.topCoatId == null"
              />
              <span class="text-center block pt-1 text-[#3b4559]">sec</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div>
      <label class="text-[#3b4559] font-semibold">Secondary Topcoat</label>
      <div class="flex w-full flex-col md:flex-row justify-around gap-2">
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
              class="w-[60px] disabled:bg-gray-100"
              v-model="form.coatPer"
              :disabled="form.coatId == '' || form.coatId == null"
            />
            <span class="text-center block pt-1 text-[#3b4559]">%</span>
          </div>

          <div>
            <input
              type="number"
              step=".01"
              class="w-[60px] disabled:bg-gray-100"
              v-model="form.coatTemp"
              :disabled="form.coatId == '' || form.coatId == null"
            />
            <span class="text-center block pt-1 text-[#3b4559]">°F</span>
          </div>
          <div>
            <input
              type="number"
              step=".01"
              class="w-[60px] disabled:bg-gray-100"
              v-model="form.coatPH"
              :disabled="form.coatId == '' || form.coatId == null"
            />
            <span class="text-center block pt-1 text-[#3b4559]">pH</span>
          </div>
          <div>
            <input
              type="number"
              step=".01"
              class="w-[60px] disabled:bg-gray-100"
              v-model="form.coatDiptime"
              :disabled="form.coatId == '' || form.coatId == null"
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
        @click="closeModalForm"
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
import { required, helpers } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import axios from "axios";
import { computed, reactive, ref } from "vue";
import useHelper from "@/composables/useHelper";
import Multiselect from "@vueform/multiselect";
const isDiferentZero = (value) => {
  return value != 0 && value != null;
};
export default {
  emits: ["closeModal", "generateDataTable", "gettinDataParts"],
  props: [
    "plateMethods",
    "topCoats",
    "chromates",
    "secondaryCoats",
    "plateTypes",
    "partUpdate",
    "partsTable",
  ],
  components: {
    multiSelect: Multiselect,
  },
  setup(props, { emit }) {
    const { partUpdate, partsTable } = props;
    const { makeToast } = useHelper();
    const parts = ref(partsTable);
    const loading = ref(false);
    const form = reactive({
      id: partUpdate.id,
      description: partUpdate.description,
      run_id: partUpdate.run_id,
      plateThick: partUpdate.plateThick,
      coatId: partUpdate.coatId,
      primaryCoatId: partUpdate.primaryCoatId,
      topCoatId: partUpdate.topCoatId,
      plate_types_id: partUpdate.plate_types_id,
      topCoatPer: partUpdate.topCoatPer,
      topCoatPer: partUpdate.topCoatPer,
      topCoatTemp: partUpdate.topCoatTemp,
      topCoatPH: partUpdate.topCoatPH,
      topCoatDiptime: partUpdate.topCoatDiptime,
      primaryPer: partUpdate.primaryPer,
      primaryTemp: partUpdate.primaryTemp,
      primaryPH: partUpdate.primaryPH,
      primaryDiptime: partUpdate.primaryDiptime,
      coatPer: partUpdate.coatPer,
      coatTemp: partUpdate.coatTemp,
      coatPH: partUpdate.coatPH,
      coatDiptime: partUpdate.coatDiptime,
      typePlateThick: partUpdate.typePlateThick,
    });
    const rules = {
      description: {
        required,
      },
      typePlateThick: {
        isDiferentZero: helpers.withMessage(
          "You must select an option",
          isDiferentZero
        ),
      },
      plateThick: { required },
    };
    const v$ = useVuelidate(rules, form);
    const closeModalForm = () => {
      emit("closeModal");
    };
    const submitForm = async () => {
      try {
        const isFormCorrect = await v$.value.$validate();
        const id = form.id;
        if (!isFormCorrect) return;
        let res;
        loading.value = true;
        res = await axios.put(`/part/${id}`, form);
        loading.value = false;
        const { ok, message } = res.data;
        if (ok) {
          makeToast(message);
          emit("gettinDataParts");
          emit("closeModal");
        } else {
          makeToast("An error has occurred", "error");
        }
      } catch (e) {
        loading.value = false;
        makeToast(e, "error");
        console.log(e);
      }
    };
    return {
      v$,
      form,
      closeModalForm,
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
