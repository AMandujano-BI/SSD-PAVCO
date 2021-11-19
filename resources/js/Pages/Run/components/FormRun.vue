<template>
  <form @submit.prevent="submitForm">
    <h1 class="text-center">Run Editor</h1>
    <div>
      <label for="">Customer</label>
      <!-- <select v-model="form.type" class="w-full"> -->
      <select class="w-full">
        <option value="1" selected>Plating</option>
        <option value="2">Chromate</option>
        <option value="3">TopCoat</option>
        <option value="4">Secondary TopCoat</option>
      </select>
    </div>
    <div>
      <label for="">Start Date</label>
      <input type="date" class="w-full" v-model="form.startDate" />
    </div>

    <div>
      <label for="">Plate Method</label>
      <select class="w-full" v-model="form.plate_methods_id">
        <option value="0" selected>Select plate Method</option>
        <option
          v-for="plateMethod in plateMethods"
          v-bind:key="plateMethod.id"
          :value="plateMethod.id"
        >
          {{ plateMethod.name }}
        </option>
      </select>

      <!-- <div v-if="v$.form.plate_methods_id.$error" class="text-red-400">
        PlateMethod field has an error.
      </div> -->
      <p
        v-for="error of v$.form.plate_methods_id.$errors"
        :key="error.$uid"
        class="text-red-400"
      >
        {{ error.$message }}
      </p>
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
        v-for="error of v$.form.description.$errors"
        :key="error.$uid"
        class="text-red-400"
      >
        {{ error.$message }}
      </p>
    </div>
    <div>
      <label for="">Plate Type</label>
      <div class="flex w-full justify-around gap-2">
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
        <div>
          <input type="text" class="w-[60px]" />
          <span class="w-1/2">mil</span>
        </div>
      </div>
    </div>
    <div>
      <label for="">Chromate</label>
      <div class="flex w-full flex-col md:flex-row gap-2">
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
        <div class="flex gap-2">
          <div>
            <input type="text" class="w-[60px]" v-model="form.plateThick" />
            <span>%</span>
          </div>

          <div>
            <input type="text" class="w-[60px]" />
            <span>°F</span>
          </div>
          <div>
            <input type="text" class="w-[60px]" />
            <span>pH</span>
          </div>
          <div>
            <input type="text" class="w-[60px]" />
            <span>sec</span>
          </div>
        </div>
      </div>
    </div>
    <div>
      <label for="">TopCoat</label>
      <div class="flex w-full justify-around gap-2">
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
        <div class="flex">
          <div>
            <input type="text" class="w-[60px]" />
            <span>%</span>
          </div>

          <div>
            <input type="text" class="w-[60px]" />
            <span>°F</span>
          </div>
          <div>
            <input type="text" class="w-[60px]" />
            <span>pH</span>
          </div>
          <div>
            <input type="text" class="w-[60px]" />
            <span>sec</span>
          </div>
        </div>
      </div>
    </div>
    <div>
      <label for="">Secondary Topcoat</label>
      <div class="flex w-full justify-around gap-2">
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
        <div class="flex">
          <div>
            <input type="text" class="w-[60px]" />
            <span>%</span>
          </div>

          <div>
            <input type="text" class="w-[60px]" />
            <span>°F</span>
          </div>
          <div>
            <input type="text" class="w-[60px]" />
            <span>pH</span>
          </div>
          <div>
            <input type="text" class="w-[60px]" />
            <span>sec</span>
          </div>
        </div>
      </div>
    </div>
    <div class="py-5">
      <label for="">Parts</label>
      <input type="number" placeholder="Parts" v-model="form.numberParts" />
      <div v-if="v$.form.numberParts.$error" class="text-red-400">
        Parts field has an error.
      </div>
    </div>
    <button class="bg-blue-600 rounded w-full py-5 text-white px-3 mt-2">
      Save
    </button>
  </form>
</template>
<script>
import { required } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import axios from "axios";

//validations
const isDiferentZero = (value) => {
  console.log(value);
  return value != 0;
};
export default {
  props: [
    "plateMethods",
    "topCoats",
    "chromates",
    "plates",
    "secondaryCoats",
    "plateTypes",
  ],
  setup() {
    return {
      v$: useVuelidate(),
    };
  },
  data() {
    return {
      form: {
        id: 0,
        number: 0,
        startDate: "2021-11-19",
        description: "",
        status: 0,
        idCustomer: 0,
        plate_methods_id: 0,
        coatId: 0,
        primaryCoatId: 0,
        topCoatId: 0,
        plate_types_id: 0,
        plateThick: 0,
        primaryPer: 0,
        coatPer: 0,
        topCoatPer: 0,
        numberParts: "",
      },
    };
  },
  validations: {
    form: {
      description: {
        required,
      },
      startDate: {
        required,
      },
      plate_methods_id: {
        isDiferentZero,
      },
      primaryCoatId: {
        isDiferentZero,
      },
      numberParts: {
        required,
      },
    },
  },
  methods: {
    async submitForm() {
      try {
        const isFormCorrect = await this.v$.$validate();
        if (!isFormCorrect) return;
        let res;
        res = await axios.post(`/run/`, this.form);
        if (res.data.ok) {
          this.makeToast("Run was created successfully");
        } else {
          this.makeToast("An error has occurred", "error");
        }
      } catch (e) {
        this.makeToast("An error has occurred", "error");
        console.log(e);
      }
    },

    makeToast(message, type = "success") {
      this.$toast.open({
        message: message,
        type: type,
        duration: 5000,
        dismissible: true,
      });
    },
  },
};
</script>