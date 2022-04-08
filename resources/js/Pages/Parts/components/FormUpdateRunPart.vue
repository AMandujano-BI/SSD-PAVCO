<template>
  <form @submit.prevent="submitForm">
    <h1 class="text-center text-2xl p-5 font-bold text-[#3b4559]">
      Run Editor
    </h1>
    <div class="flex w-full justify-around flex-col md:flex-row gap-5 pb-8">
      <div class="w-full">
        <label for="" class="text-[#3b4559] font-bold text-lg pl-10 pb-2"
          >Customer</label
        >
        <multi-select
          :options="customers"
          class="w-full"
          v-model="form.company_id"
          :searchable="true"
          placeholder="Select Customer"
        />
        <p
          v-for="error of v$.user_id.$errors"
          :key="error.$uid"
          class="text-red-400"
        >
          {{ error.$message }}
        </p>
      </div>
      <div class="w-full">
        <label for="" class="text-[#3b4559] font-bold text-lg pl-10 pb-2 border-[#979797]"
          >Start Date</label
        >
        <input type="date" class="w-full" v-model="form.start_date" />
      </div>
    </div>

    <div class="flex w-full justify-around flex-col md:flex-row gap-5 pb-8">
      <div class="w-full">
        <label for="" class="text-[#3b4559] font-bold text-lg pl-10 pb-2"
          >Plate Method</label
        >
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
      <div class="w-full">
        <label class="text-[#3b4559] font-bold text-lg pl-10 pb-2">Hours</label>
        <input
          type="number"
          class="w-full border-[#979797]"
          v-model="form.hours"
          :class="{ 'border-red-500': v$.hours.$error }"
        />
        <p
          v-for="error of v$.hours.$errors"
          :key="error.$uid"
          class="text-red-400"
        >
          {{ error.$message }}
        </p>
      </div>
    </div>
    <div>
      <label for="" class="text-[#3b4559] font-bold text-lg pl-10 pb-2"
        >Description</label
      >
      <textarea
        cols="30"
        rows="3"
        class="w-full"
        v-model="form.description"
      ></textarea>
    </div>
    <div class="flex justify-end">
      <button
        class="
          bg-primary
          hover:bg-primary-600
          py-5
          text-white
          px-8
          mt-2
          w-[95px]
        "
      >
        Save
      </button>
    </div>
  </form>
</template>

<script>
import { required, helpers, minValue } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import axios from "axios";
import { ref, reactive } from "vue";
import useHelper from "@/composables/useHelper";
import Multiselect from "@vueform/multiselect";
const isDiferentZero = (value) => {
   return value != 0 && value !=null;
};
export default {
  props: ["plateMethods", "run", "customers"],
  components: {
    multiSelect: Multiselect,
  },
  setup(props) {
    const { run } = props;
    const { makeToast } = useHelper();


    const currentDate = run.start_date.slice(0, 10);
    const form = reactive({
      id: run.id,
      number: 0,
      start_date: currentDate,
      description: run.description,
      hours: run.hours,
      status: 0,
      idCustomer: run.idCustomer,
      user_id: 1,
      company_id: run.company_id,
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

      
      
      start_date_edit: null,
    });
    const rules = {
      start_date: {
        required,
      },
      hours: {
        required,
        minValue: minValue(0),
      },
      plate_methods_id: {
        isDiferentZero: helpers.withMessage(
          "You must select an option",
          isDiferentZero
        ),
        required,
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
        if (!isFormCorrect) return;
        let res;
        
        res = await axios.put(`/run/${form.id}`, form);
        const { ok, value, message } = res.data;
        
        if (ok) {
          makeToast("Part was updated successfully");
        } else {
          makeToast("An error has occurred in server", "error");
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
