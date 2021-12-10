<template>
  <h1 class="text-center font-bold text-2xl">Update Part</h1>
  <form @submit.prevent="submitForm">
    <div>
      <label for="">Plate Type</label>
      <div class="flex w-full justify-between gap-2">
        <div class="w-full">
          <multi-select
            :options="plateTypes"
            class="w-full"
            v-model="form.plate_types_id"
            :searchable="true"
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
        <div>
          <input
            type="text"
            class="w-[60px]"
            v-model="form.plateThick"
            :class="{ 'border-red-500': v$.plateThick.$error }"
          />
          <span class="w-1/2">mil</span>
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
      <label for="">Chromate</label>
      <div class="flex w-full flex-col md:flex-row gap-2">
        <div class="w-full">
          <multi-select
            :options="chromates"
            class="w-full"
            v-model="form.primaryCoatId"
            :searchable="true"
            placeholder="Select Chromates "
          />
          <p
            v-for="error of v$.primaryCoatId.$errors"
            :key="error.$uid"
            class="text-red-400"
          >
            {{ error.$message }}
          </p>
        </div>
        <div class="flex gap-2">
          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.primaryPer"
              :class="{ 'border-red-500': v$.primaryPer.$error }"
            />
            <span>%</span>
            <p
              v-for="error of v$.primaryPer.$errors"
              :key="error.$uid"
              class="text-red-400"
            >
              {{ error.$message }}
            </p>
          </div>

          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.primaryTemp"
              :class="{ 'border-red-500': v$.primaryTemp.$error }"
            />
            <span>°F</span>
            <p
              v-for="error of v$.primaryTemp.$errors"
              :key="error.$uid"
              class="text-red-400"
            >
              {{ error.$message }}
            </p>
          </div>
          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.primaryPH"
              :class="{ 'border-red-500': v$.primaryPH.$error }"
            />
            <span>pH</span>
            <p
              v-for="error of v$.primaryPH.$errors"
              :key="error.$uid"
              class="text-red-400"
            >
              {{ error.$message }}
            </p>
          </div>
          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.primaryDiptime"
              :class="{ 'border-red-500': v$.primaryDiptime.$error }"
            />
            <span>sec</span>
            <p
              v-for="error of v$.primaryDiptime.$errors"
              :key="error.$uid"
              class="text-red-400"
            >
              {{ error.$message }}
            </p>
          </div>
        </div>
      </div>
    </div>
    <div>
      <label for="">TopCoat</label>
      <div class="flex w-full justify-around gap-2">
        <div class="w-full">
          <multi-select
            :options="topCoats"
            class="w-full"
            v-model="form.topCoatId"
            :searchable="true"
            placeholder="Select TopCoat"
          />
          <p
            v-for="error of v$.primaryCoatId.$errors"
            :key="error.$uid"
            class="text-red-400"
          >
            {{ error.$message }}
          </p>
        </div>
        <div class="flex flex-col">
          <div class="flex">
            <div>
              <input
                type="text"
                class="w-[60px]"
                v-model="form.topCoatPer"
                :class="{ 'border-red-500': v$.topCoatPer.$error }"
              />
              <span>%</span>
              <p
                v-for="error of v$.topCoatPer.$errors"
                :key="error.$uid"
                class="text-red-400"
              >
                {{ error.$message }}
              </p>
            </div>

            <div>
              <input
                type="text"
                class="w-[60px]"
                v-model="form.topCoatTemp"
                :class="{ 'border-red-500': v$.topCoatTemp.$error }"
              />
              <span>°F</span>
              <p
                v-for="error of v$.topCoatTemp.$errors"
                :key="error.$uid"
                class="text-red-400"
              >
                {{ error.$message }}
              </p>
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                v-model="form.topCoatPH"
                :class="{ 'border-red-500': v$.topCoatPH.$error }"
              />
              <span>pH</span>
              <p
                v-for="error of v$.topCoatPH.$errors"
                :key="error.$uid"
                class="text-red-400"
              >
                {{ error.$message }}
              </p>
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                v-model="form.topCoatDiptime"
                :class="{ 'border-red-500': v$.topCoatDiptime.$error }"
              />
              <span>sec</span>
              <p
                v-for="error of v$.topCoatDiptime.$errors"
                :key="error.$uid"
                class="text-red-400"
              >
                {{ error.$message }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div>
      <label for="">Secondary Topcoat</label>
      <div class="flex w-full justify-around gap-2">
        <div class="w-full">
          <multi-select
            :options="secondaryCoats"
            class="w-full"
            v-model="form.coatId"
            :searchable="true"
            placeholder="Select Secondary TopCoat"
          />
          <p
            v-for="error of v$.coatId.$errors"
            :key="error.$uid"
            class="text-red-400"
          >
            {{ error.$message }}
          </p>
        </div>
        <div class="flex">
          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.coatPer"
              :class="{ 'border-red-500': v$.coatPer.$error }"
            />
            <span>%</span>
            <p
              v-for="error of v$.coatPer.$errors"
              :key="error.$uid"
              class="text-red-400"
            >
              {{ error.$message }}
            </p>
          </div>

          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.coatTemp"
              :class="{ 'border-red-500': v$.coatTemp.$error }"
            />
            <span>°F</span>
            <p
              v-for="error of v$.coatTemp.$errors"
              :key="error.$uid"
              class="text-red-400"
            >
              {{ error.$message }}
            </p>
          </div>
          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.coatPH"
              :class="{ 'border-red-500': v$.coatPH.$error }"
            />
            <span>pH</span>
            <p
              v-for="error of v$.coatPH.$errors"
              :key="error.$uid"
              class="text-red-400"
            >
              {{ error.$message }}
            </p>
          </div>
          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.coatDiptime"
              :class="{ 'border-red-500': v$.coatDiptime.$error }"
            />

            <span>sec</span>
            <p
              v-for="error of v$.coatDiptime.$errors"
              :key="error.$uid"
              class="text-red-400"
            >
              {{ error.$message }}
            </p>
          </div>
        </div>
      </div>
    </div>
    <div>
      <label for="">Description</label>
      <textarea
        cols="30"
        rows="5"
        class="w-full"
        v-model="form.description"
        :class="{ 'border-red-500': v$.description.$error }"
      ></textarea>
      <p
        v-for="error of v$.description.$errors"
        :key="error.$uid"
        class="text-red-400"
      >
        {{ error.$message }}
      </p>
    </div>

    <div class="flex justify-around w-full gap-4">
      <button
        type="button"
        @click="closeModal"
        class="bg-red-600 rounded w-full py-5 text-white px-3 mt-2"
      >
        Cancel
      </button>
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
  </form>
</template>

<script>
import { required, helpers } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import axios from "axios";
import { ref } from "vue";
import useHelper from "@/composables/useHelper";
import Multiselect from "@vueform/multiselect";
const isDiferentZero = (value) => {
  return value != 0;
};
export default {
  emits: ["closeModal", "generateDataTable"],
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
    console.log(partUpdate);
    const parts = ref(partsTable);
    console.log(parts);
    const form = ref({
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
    });
    const rules = {
      description: {
        required,
      },
      plate_types_id: {
        isDiferentZero: helpers.withMessage(
          "You must select an option",
          isDiferentZero
        ),
      },
      primaryCoatId: {
        isDiferentZero: helpers.withMessage(
          "You must select an option",
          isDiferentZero
        ),
      },
      topCoatId: {
        isDiferentZero: helpers.withMessage(
          "You must select an option",
          isDiferentZero
        ),
      },
      coatId: {
        isDiferentZero: helpers.withMessage(
          "You must select an option",
          isDiferentZero
        ),
      },
      topCoatPer: {
        required,
      },
      topCoatTemp: {
        required,
      },
      topCoatPH: {
        required,
      },
      topCoatDiptime: {
        required,
      },
      primaryPer: {
        required,
      },
      primaryTemp: {
        required,
      },
      primaryPH: {
        required,
      },
      primaryDiptime: {
        required,
      },
      plateThick: {
        required,
      },
      coatPH: {
        required,
      },
      coatPer: {
        required,
      },
      coatTemp: {
        required,
      },
      coatDiptime: {
        required,
      },
    };
    const v$ = useVuelidate(rules, form);
    const closeModal = () => {
      emit("closeModal");
    };
    const submitForm = async () => {
      try {
        const isFormCorrect = await v$.value.$validate();
        const id = form.value.id;
        if (!isFormCorrect) return;
        let res;

        res = await axios.put(`/part/${id}`, form.value);
        const { ok, value, message } = res.data;
        if (ok) {
          makeToast(message);
          const index = parts.value.findIndex((item) => item.id == id);
          console.log(parts.value[index]);
          parts.value[index] = {
            ...parts.value[index],
            primaryCoatId: form.value.primaryCoatId,
            coatId: form.value.coatId,
            topCoatId: form.value.topCoatId,
            description: form.value.description,
          };
          emit("generateDataTable");
          emit("closeModal");
        } else {
          makeToast("An error has occurred", "error");
        }
      } catch (e) {
        makeToast(e, "error");
        console.log(e);
      }
    };
    return {
      v$,
      form,
      closeModal,
      submitForm,
    };
  },
  watch: {
    run() {
      this.parts = this.partsTable;
    },
  },
};
</script>
