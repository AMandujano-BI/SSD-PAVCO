
<template>
  <h1 class="text-center text-[#3b4559] font-semibold text-2xl pb-5">
    Update Hours
  </h1>
  <form @submit.prevent="submitForm">
    <input
      type="date"
      class="w-full my-5 disabled:bg-gray-100"
      v-model="form.date"
      disabled="true"
    />
    <input
      type="text"
      class="w-full"
      placeholder="Set Hours"
      v-model="form.hours"
    />
    <p v-for="error of v$.hours.$errors" :key="error.$uid" class="text-red-400">
      {{ error.$message }}
    </p>
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
  </form>
</template>

<script>
import { reactive, ref } from "vue";
import { required } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import useHelper from "@/composables/useHelper";
import axios from "axios";
export default {
  props: ["hourGet"],
  emits: ["closeModal"],
  setup(props, { emit }) {
    const { hourGet } = props;
    const loading = ref(false);
    const { makeToast } = useHelper();
    const form = reactive({
      id: hourGet.id,
      hours: hourGet.hourNumber,
      date: hourGet.dateChange,
    });
    const rules = {
      hours: { required },
    };
    const v$ = useVuelidate(rules, form);
    const submitForm = async () => {
      //Validate Form
      const isFormCorrect = await v$.value.$validate();
      if (!isFormCorrect) return;

      // Send data to server
      try {
        loading.value = true
        const res = await axios.put(`/dailyHours/${form.id}`, form);
        loading.value = false

        const { ok, message } = res.data;
        if (ok) {
          makeToast(message);
          emit("closeModal", form);
        } else {
        loading.value = false
          makeToast(message, "error");
        }
      } catch (e) {
        loading.value = false
      }
    };

    return {
      loading,
      submitForm,
      form,
      v$,
    };
  },
};
</script>

<style>
</style>