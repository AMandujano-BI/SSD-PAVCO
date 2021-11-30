<template>
  <h1 class="text-center font-bold text-2xl">New Part</h1>
  <form @submit.prevent="submitForm">
    <div>
      <label for="">Plate Type</label>
      <div class="flex w-full justify-between gap-2">
        <div class="w-full">
          <select class="w-full" v-model="form.plate_types_id">
            <option value="0" selected>Select Plate Type</option>
            <option
              v-for="plateType in plateTypes"
              v-bind:key="plateType.id"
              :value="plateType.id"
            >
              {{ plateType.name }}
            </option>
          </select>
          <p
            v-for="error of v$.plate_types_id.$errors"
            :key="error.$uid"
            class="text-red-400"
          >
            {{ error.$message }}
          </p>
        </div>
        <div>
          <input type="text" class="w-[60px]" v-model="form.plateThick" />
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
          <select class="w-full" v-model="form.primaryCoatId">
            <option value="0" selected>Select Chromate</option>
            <option
              v-for="chromate in chromates"
              v-bind:key="chromate.id"
              :value="chromate.id"
            >
              {{ chromate.name }}
            </option>
          </select>
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
            <input type="text" class="w-[60px]" v-model="form.primaryPer" />
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
            <input type="text" class="w-[60px]" v-model="form.primaryTemp" />
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
            <input type="text" class="w-[60px]" v-model="form.primaryPH" />
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
            <input type="text" class="w-[60px]" v-model="form.primaryDiptime" />
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
          <select class="w-full" v-model="form.topCoatId">
            <option value="0" selected>Select TopCoat</option>
            <option
              v-for="topCoat in topCoats"
              v-bind:key="topCoat.id"
              :value="topCoat.id"
            >
              {{ topCoat.name }}
            </option>
          </select>
          <p
            v-for="error of v$.topCoatId.$errors"
            :key="error.$uid"
            class="text-red-400"
          >
            {{ error.$message }}
          </p>
        </div>
        <div class="flex flex-col">
          <div class="flex">
            <div>
              <input type="text" class="w-[60px]" v-model="form.topCoatPer" />
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
              <input type="text" class="w-[60px]" v-model="form.topCoatTemp" />
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
              <input type="text" class="w-[60px]" v-model="form.topCoatPH" />
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
          <select class="w-full" v-model="form.coatId">
            <option value="0">Select Secondary Topcoat</option>
            <option
              :selected="secondaryCoat.id == form.coatId"
              v-for="secondaryCoat in secondaryCoats"
              v-bind:key="secondaryCoat.id"
              :value="secondaryCoat.id"
            >
              {{ secondaryCoat.name }}
            </option>
          </select>
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
            <input type="text" class="w-[60px]" v-model="form.coatPer" />
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
            <input type="text" class="w-[60px]" v-model="form.coatTemp" />
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
            <input type="text" class="w-[60px]" v-model="form.coatPH" />
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
            <input type="text" class="w-[60px]" v-model="form.coatDiptime" />
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
      ></textarea>
      <!-- <div v-if="v$.form.description.$error" class="text-red-400">Name field has an error.</div> -->
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
      <button class="bg-green-600 rounded w-full py-5 text-white px-3 mt-2">
        Save
      </button>
    </div>
  </form>
</template>

<script>
import useHelper from "@/composables/useHelper";
import { required, helpers } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import axios from "axios";
import { ref, reactive } from "vue";
const isDiferentZero = (value) => {
  return value != 0;
};
export default {
  emits: ["closeModalNewPart", "generateDataTable"],
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
    const form = ref({
      id: 0,
      description: "",
      run_id: run_id,
      plateThick: 0,
      coatId: 0,
      primaryCoatId: 0,
      topCoatId: 0,
      plate_types_id: 0,
      topCoatPer: 0,
      topCoatPer: 0,
      topCoatTemp: 0,
      topCoatPH: 0,
      topCoatDiptime: 0,
      primaryPer: 0,
      primaryTemp: 0,
      primaryPH: 0,
      primaryDiptime: 0,
      coatPer: 0,
      coatTemp: 0,
      coatPH: 0,
      coatDiptime: 0,
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
      emit("closeModalNewPart");
    };

    const submitForm = async () => {
      try {
        const isFormCorrect = await v$.value.$validate();
        console.log(isFormCorrect);
        if (!isFormCorrect) return;
        let res;

        console.log(form);
        res = await axios.post(`/part`, form.value);
        const { ok, value, message } = res.data;
        console.log(res.data);
        if (ok) {
          makeToast(message);
          console.log(value);
          console.log(parts.value);
          // parts.value = [...parts.value,value]
          parts.value.push(value);
          console.log(parts.value);
          emit("generateDataTable");
          emit("closeModalNewPart");
        } else {
          makeToast("An error has occurred", "error");
        }
      } catch (e) {
        makeToast(e, "error");
        console.log(e);
      }
    };

    return {
      form,
      closeModal,
      v$,
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
