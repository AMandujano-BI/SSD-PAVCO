<template>
<h1 class="text-center font-bold text-2xl">Update Part</h1>
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
          </div>
          <div>
            <input type="text" class="w-[60px]" v-model="form.primaryPH" />
            <span>pH</span>
          </div>
          <div>
            <input type="text" class="w-[60px]" v-model="form.primaryDiptime" />
            <span>sec</span>
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
              <input type="text" class="w-[60px]" v-model="form.topCoatPer" />
              <span>%</span>
            </div>

            <div>
              <input type="text" class="w-[60px]" v-model="form.topCoatTemp" />
              <span>°F</span>
            </div>
            <div>
              <input type="text" class="w-[60px]" v-model="form.topCoatPH" />
              <span>pH</span>
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                v-model="form.topCoatDiptime"
              />
              <span>sec</span>
            </div>
          </div>
          <p
            v-for="error of v$.coatId.$errors"
            :key="error.$uid"
            class="text-red-400"
          >
            {{ error.$message }}
          </p>
        </div>
      </div>
    </div>
    <div>
      <label for="">Secondary Topcoat</label>
      <div class="flex w-full justify-around gap-2">
        <div class="w-full">
          <select class="w-full" v-model="form.coatId">
            <option value="0" selected>Select Secondary Topcoat</option>
            <option
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
          </div>

          <div>
            <input type="text" class="w-[60px]" v-model="form.coatTemp" />
            <span>°F</span>
          </div>
          <div>
            <input type="text" class="w-[60px]" v-model="form.coatPH" />
            <span>pH</span>
          </div>
          <div>
            <input type="text" class="w-[60px]" v-model="form.coatDiptime" />
            <span>sec</span>
          </div>
        </div>
      </div>
    </div>

    <div class="flex justify-around w-full gap-4">
      <button
        type="button"
        @click="closeModal"
        class="bg-red-600 rounded w-full py-5 text-white px-3 mt-2"
      >
        Cancel
      </button>
      <button class="bg-blue-600 rounded w-full py-5 text-white px-3 mt-2">
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
const isDiferentZero = (value) => {
  return value != 0;
};
export default {
  emits: ["closeModal"],
  props: [
    "plateMethods",
    "topCoats",
    "chromates",
    "secondaryCoats",
    "plateTypes",
    "partUpdate",
  ],
  setup(props, { emit }) {
    const { partUpdate } = props;
    console.log(partUpdate);
    const form = ref({
      id: partUpdate.id,
      description: partUpdate.description,
      run_id: partUpdate.run_id,
      plateThick: partUpdate.plateThick,
      coatId: partUpdate.coatId,
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
      primaryPer: {
        required,
      },
    };
    const v$ = useVuelidate(rules, form);
    const closeModal = () => {
      emit("closeModal");
    };
    const submitForm = async () => {
      console.log(form.value);
      const isFormCorrect = await v$.value.$validate();
      if (!isFormCorrect) return;
    };
    return {
      v$,
      form,
      closeModal,
      submitForm,
    };
  },
};
</script>
