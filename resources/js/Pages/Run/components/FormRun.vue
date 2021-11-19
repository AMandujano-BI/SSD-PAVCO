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
      <select class="w-full" v-model="form.plateMethod">
        <option value="0" selected>Select plate Method</option>
        <option
          v-for="plateMethod in plateMethods"
          v-bind:key="plateMethod.id"
          :value="plateMethod.id"
        >
          {{ plateMethod.name }}
        </option>
      </select>

      <div v-if="v$.form.plateMethod.$error" class="text-red-400">
        PlateMethod field has an error.
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
        <select class="w-full" v-model="form.platetype">
          <option value="1" selected>Choose Plate</option>
          <option value="2">Chromate</option>
          <option value="3">TopCoat</option>
          <option value="4">Secondary TopCoat</option>
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
        <select class="w-full" v-model="form.chromate">
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
        <select class="w-full" v-model="form.topcoat">
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
        <select class="w-full" v-model="form.secondarycoat">
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
import { required, minLength } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import axios from "axios";
export default {
  props: ["plateMethods", "topCoats", "chromates", "plates", "secondaryCoats"],
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
        plateMethod: 0,
        secondarycoat: 0,
        chromates: 0,
        topcoat: 0,
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
      plateMethod: {
        required,
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

        console.log(this.form);
        let res;
        res = await axios.post(`/run/`, this.form);
        this.makeToast("Run was created successfully");
        console.log(res);
      } catch (e) {
        this.makeToast("error", "error");
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