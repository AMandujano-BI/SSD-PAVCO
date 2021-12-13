<template>
  <form @submit.prevent="submitForm">
    <h1 class="text-center">Run Editor</h1>
    <div>
      <label for="">Customer</label>
      <select class="w-full" v-model="form.user_id">
        <option value="0" selected>Select a Customer</option>
        <option value="1">Test</option>
        <option value="2">Test2</option>
        <option value="3">Test3</option>
        <option value="4">Test4</option>
      </select>
      <p
        v-for="error of v$.user_id.$errors"
        :key="error.$uid"
        class="text-red-400"
      >
        {{ error.$message }}
      </p>
    </div>
    <div>
      <label for="">Start Date</label>
      <input type="date" class="w-full" v-model="form.startDate" />
    </div>

    <div>
      <label for="">Plate Method</label>
      <multi-select
        :options="plateMethods"
        class="w-full"
        v-model="form.plate_methods_id"
        :searchable="true"
        placeholder="Select plate Method"
      />
        <p
            v-for="error of v$.plate_methods_id.$errors"
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
    <button class="bg-primary rounded w-full py-5 text-white px-3 mt-2">
      Save
    </button>
  </form>
</template>

<script>
import { required, helpers } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import axios from "axios";
import { ref, reactive } from "vue";
import useHelper from "@/composables/useHelper";
import Multiselect from "@vueform/multiselect";
const isDiferentZero = (value) => {
  return value != 0;
};
export default {
  props: ["plateMethods", "run"],
   components: {
    multiSelect: Multiselect,
  },
  setup(props) {
    const { run } = props;
    const { makeToast } = useHelper();
    const form = reactive({
      id: run.id,
      number: 0,
      startDate: new Date(run.startDate).toISOString().slice(0, 10),
      description: run.description,
      status: 0,
      idCustomer: run.idCustomer,
      user_id: 1,
      plate_methods_id: run.plate_methods_id,
      coatId: 0,
      primaryCoatId: 0,
      topCoatId: 0,
      plate_types_id: 0,
      plateThick: 0,
      //------Details
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

      numberParts: 0,
    });
    const rules = {
      description: {
        required,
      },
      startDate: {
        required,
      },
      plate_methods_id: {
        isDiferentZero: helpers.withMessage(
          "You must select an option",
          isDiferentZero
        ),
        required
      },
      user_id: {
        isDiferentZero: helpers.withMessage(
          "You must select an option",
          isDiferentZero
        ),
      },
    };
    const v$ = useVuelidate(rules, form);
    const submitForm = async () => {
      try {
        const isFormCorrect = await v$.value.$validate();
        console.log(v$.value);
        if (!isFormCorrect) return;
        let res;
        res = await axios.put(`/run/${form.id}`, form);
        const { ok, value, message } = res.data;
        if (ok) {
          makeToast("Part was updated successfully");
        } else {
          console.log(res.data);
          makeToast("An error has occurred", "error");
        }
      } catch (e) {
        console.log(e);
        makeToast("An error has occurred", "error");
      }
    };

    return {
      form,
      v$,
      submitForm,
    };
  },
};
</script>
