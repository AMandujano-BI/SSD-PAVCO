<template>
  <h1 class="text-center font-bold text-2xl">New Company</h1>
  <form @submit.prevent="submitForm">
    <div>
      <label for="">Country</label>
      <multi-select
        :options="countries"
        class="w-full"
        v-model="form.country_id"
        :searchable="true"
        placeholder="Select Country"
      />
    </div>

    <div>
      <label for="">Name</label>
      <input type="text" class="w-full" v-model="form.name" />
      <p
        v-for="error of v$.name.$errors"
        :key="error.$uid"
        class="text-red-400"
      >
        {{ error.$message }}
      </p>
    </div>
    <div class="flex justify-around w-full gap-4">
      <button
        type="button"
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
import { reactive,ref } from "vue";
import Multiselect from "@vueform/multiselect";
import useVuelidate from "@vuelidate/core";
import { required, helpers } from "@vuelidate/validators";
export default {
  props: ["countries"],
  components: {
    multiSelect: Multiselect,
  },
  setup(props) {
    const form = reactive({
      id: 0,
      name: "",
      address: "",
      city: "",
      state: "",
      zip: "",
      phone: "",
      fax: "",
      customer: 0,
      distributor: 0,
      notes: "",
      country_id: 0,
    });
    const rules = {
      name: {
        required,
      },
      address: {
        required,
      },
      city: {
        required,
      },
      state: {
        required,
      },
    };

    const v$ = useVuelidate(rules, form);
    const submitForm = async () => {
      const isFormCorrect = await v$.value.$validate();
      console.log(isFormCorrect);
    };

    return {
      form,
       v$,
      submitForm,
    };
  },
};
</script>
