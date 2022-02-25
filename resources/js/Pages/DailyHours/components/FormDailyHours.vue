<template>
  <h1 class="text-center text-[#3b4559] font-semibold text-2xl pb-5">
    Update Hours
  </h1>
  <form @submit.prevent="submitForm">
    <input
      type="text"
      class="w-full"
      placeholder="Change Hours"
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
  props: ["arrayId"],
  emits: ["closeModal"],
  setup(props, { emit }) {
    const { arrayId } = props;
    const loading = ref(false);
    const { makeToast } = useHelper();
    const form = reactive({
      hours: "",
      arrayId: arrayId
    });
    const rules = {
      hours: { required },
    };
    const v$ = useVuelidate(rules, form);
    const submitForm = async () => {
      const isFormCorrect = await v$.value.$validate();
      if (!isFormCorrect) return;
      try {
        const res = await axios.post("/dailyHours", form);
        const { ok, value, message } = res.data;
        if (ok) {
          makeToast(message);
          emit("closeModal");
        } else {
          makeToast(message);
        }
      } catch (e) {}
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