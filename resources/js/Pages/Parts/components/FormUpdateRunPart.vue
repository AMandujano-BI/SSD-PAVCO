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
        <input type="datetime-local" class="w-full" v-model="form.start_date" @change="startDateListener" />
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
          @change="hoursListener"
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
    let startDateChanged = false;
    let hoursChanged = false;


    const startDateListener = () => {
      startDateChanged = true;
    }
    const hoursListener = () => {
      hoursChanged = true;
    }


    const calculateHours = () => {
      if (run.status === 1) {
        //cerrado
        if (run.isEdit) {
          
          return run.hours;
        } else {
          
          const closeNonEdit =
            Math.abs(new Date(run.closed_date) - new Date(run.start_date)) /
            36e5;
          return closeNonEdit | 0;
        }
      } else {
        // abierto
        if (run.isEdit) {
          
          const activeEdit =
            Math.abs(new Date() - new Date(run.last_edit)) / 36e5;
          const hoursEdited = activeEdit | 0;
          return run.hours + hoursEdited;
        } else {
          
          const activeNonEdit =
            Math.abs(new Date() - new Date(run.start_date)) / 36e5;
          return activeNonEdit | 0;
        }
      }
    };

    let runHours = calculateHours();

    const currentDate = new Date(run.start_date);
    let month = currentDate.getMonth() + 1;
    let fullMonth = "0";
    month.toString().length < 2
      ? (fullMonth = fullMonth.concat(month))
      : (fullMonth = month);
    const dateFormated =
      "" +
      currentDate.getFullYear() +
      "-" +
      fullMonth +
      "-" +
      currentDate.toString().slice(8, 10) +
      "T" +
      currentDate.toString().slice(16, 21);
    const form = reactive({
      id: run.id,
      number: 0,
      start_date: dateFormated,
      description: run.description,
      hours: runHours,
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

      hasDiferentHours: false,
      last_edit: "",
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
      // to hours
      const date = new Date();
      const dateFormated =
        "" +
        date.getUTCFullYear() +
        "-" +
        (date.getUTCMonth() + 1) +
        "-" +
        date.getUTCDate() +
        " " +
        date.getUTCHours() +
        ":" +
        date.getUTCMinutes() +
        ":" +
        date.getUTCSeconds();

      // to startDate
      const currentUTCDate = new Date(form.start_date);
      const monthUTC = currentUTCDate.getUTCMonth()+1;
      const dayUTC = currentUTCDate.getUTCDate();
      const hoursUTC = currentUTCDate.getUTCHours();
      const minutesUTC = currentUTCDate.getUTCMinutes();
      let fullMonthUTC = '0';
      let fullDayUTC = '0';
      let fullHoursUTC = '0';
      let fullMinutesUTC = '0';
      (monthUTC.toString().length < 2) ? fullMonthUTC = fullMonthUTC.concat(monthUTC) : fullMonthUTC = monthUTC;
      (dayUTC.toString().length < 2) ? fullDayUTC = fullDayUTC.concat(dayUTC) : fullDayUTC = dayUTC;
      (hoursUTC.toString().length < 2) ? fullHoursUTC = fullHoursUTC.concat(hoursUTC) : fullHoursUTC = hoursUTC;
      (minutesUTC.toString().length < 2) ? fullMinutesUTC = fullMinutesUTC.concat(minutesUTC) : fullMinutesUTC = minutesUTC;
      
      const startUTCDate = 
          ''+
          currentUTCDate.getUTCFullYear()+
          '-'+
          fullMonthUTC+
          '-'+
          fullDayUTC+
          'T'+
          fullHoursUTC+
          ':'+
          fullMinutesUTC;

      try {
        if ( startDateChanged && hoursChanged ) {
          form.hasDiferentHours = true;
          form.last_edit = dateFormated;
          form.start_date_edit = startUTCDate;
        } else {
          if ( hoursChanged ) {
            form.hasDiferentHours = true;
            form.last_edit = dateFormated;
            form.start_date_edit = startUTCDate;
          } else {
            if ( startDateChanged ) {
              form.start_date_edit = startUTCDate;
            } else {
              form.start_date_edit = startUTCDate;
            }
          }
        }

        const isFormCorrect = await v$.value.$validate();
        if (!isFormCorrect) return;
        let res;
        
        res = await axios.put(`/run/${form.id}`, form);
        const { ok, value, message } = res.data;
        run.isEdit = value.isEdit;
        if (form.hasDiferentHours) {
          form.hasDiferentHours = false;
        }
        if (ok) {
          
          if ( startDateChanged && !run.isEdit) {
            run.start_date = form.start_date;
            runHours = calculateHours();
            form.hours = runHours;
          }
          startDateChanged = false;
          hoursChanged = false;
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
      startDateListener,
      hoursListener
    };
  },
  
};
</script>
