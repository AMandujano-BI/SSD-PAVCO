<template>
  <form @submit.prevent="submitForm">
    <h1 class="font-bold text-2xl text-center p-5 text-[#3b4559]">EMAIL RUN RESULTS</h1>
    <p class="pb-4 text-[#3b4559]">
      Please enter a valid email address below. The email address that you enter
      below will receive an email from
      <strong><a href="mailto:vayala@pavco.com">vayala@pavco.com</a></strong
      >.This email will contain the results to the run as well as a link to view
      all of the images for this run.
    </p>
    <input
      type="text"
      class="w-full"
      placeholder="Email Address"
      v-model="form.emailSend"
    />
    <p
      v-for="error of v$.emailSend.$errors"
      :key="error.$uid"
      class="text-red-400"
    >
      {{ error.$message }}
    </p>

    <button
      class="bg-primary p-5 rounded-md text-center text-white mt-5 w-full hover:bg-primary-600"
      v-if="!loading"
    >
      Send Email
    </button>
    <button
      class="bg-primary p-5 rounded-md text-center text-white mt-5 w-full "
      v-if="loading"
    >
      <div
        className="animate-spin rounded-full h-6 w-6 border-b-2 border-t-2 border-white inline-block"
      ></div>
    </button>
  </form>
</template>

<script>
import { reactive, ref } from "vue";
import useVuelidate from "@vuelidate/core";
import { required, email } from "@vuelidate/validators";
import useHelper from "@/composables/useHelper";
export default {
  props: ["emailSend", "id"],
  emits: ["closeModal"],
  setup(props, { emit }) {
    const { emailSend, id } = props;
    const { makeToast } = useHelper();
    const loading = ref(false);
    const form = reactive({
      id: id,
      emailSend: emailSend,
    });
    const rules = {
      emailSend: {
        required,
        email,
      },
    };
    const v$ = useVuelidate(rules, form);
    const submitForm = async () => {
      try {
        const isFormCorrect = await v$.value.$validate();
        if (!isFormCorrect) return;
        loading.value = true;
        const res = await axios.post("/email/runResult", form);
        loading.value = false;
        const { ok, message } = res.data;
        if (ok) {
          makeToast(message);
          emit("closeModal");
        } else {
          makeToast(message, "error");
        }
      } catch (e) {
        loading.value = false;
        console.log(e);
      }
    };
    return {
      v$,
      form,
      submitForm,
      loading,
    };
  },
};
</script>

<style>
</style>