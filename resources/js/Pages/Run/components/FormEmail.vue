<template>
  <form @submit.prevent="submitForm">
    <h1 class="font-bold text-2xl text-center p-5 text-[#3b4559]">
      EMAIL RUN RESULTS
    </h1>
    <p class="pb-4 text-[#3b4559]">
      Please enter a valid email address below. The email address that you enter
      below will receive an email from
      <strong><a href="mailto:ssd@pavco.com">ssd@pavco.com</a></strong
      >.This email will contain the results to the run as well as a link to view
      all of the images for this run.
    </p>
    <!-- appendNewTag="true" -->
    <multi-select
      mode="tags"
      v-model="form.emailSend"
      :appendNewTag="true"
      :createTag="true"
      :close-on-select="true"
      :searchable="true"
      :create-option="true"
      :options="options"
      @tag="addTag"
    ></multi-select>
    <p
      v-for="error of v$.emailSend.$errors"
      :key="error.$uid"
      class="text-red-400"
    >
      {{ error.$message }}
    </p>

    <button
      class="
        bg-primary
        p-5
        rounded-md
        text-center text-white
        mt-5
        w-full
        hover:bg-primary-600
      "
      v-if="!loading"
    >
      Send Email
    </button>
    <button
      :disabled="true"
      class="bg-primary p-5 rounded-md text-center text-white mt-5 w-full"
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
import { required, email, helpers } from "@vuelidate/validators";
import useHelper from "@/composables/useHelper";
import Multiselect from "@vueform/multiselect";
export default {
  props: ["emailSend", "id"],
  emits: ["closeModal"],
  components: {
    multiSelect: Multiselect,
  },
  setup(props, { emit }) {
    const { emailSend, id } = props;
    const { makeToast } = useHelper();
    const options = ref([]);
    const loading = ref(false);
    const value = ref("");
    const currentTimeZone =  `${Intl.DateTimeFormat().resolvedOptions().timeZone}`;
    const form = reactive({
      id: id,
      emailSend: [],
      zone: currentTimeZone
    });
    const rules = {
      emailSend: {
        required,
      },
    };
    const v$ = useVuelidate(rules, form);
    const validateEmail = (email) => {
      return String(email)
        .toLowerCase()
        .match(
          /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        );
    };

    const validateEmails = () => {
      let isCorrect = true;
      form.emailSend.map((text) => {
        if (!validateEmail(text)) {
          makeToast(`Invalid email address ${text}`, "error");
          isCorrect = false;
          return isCorrect;
        }
      });
      return isCorrect;
    };
    const submitForm = async () => {
      try {
        const isFormCorrect = await v$.value.$validate();
        if (!isFormCorrect) return;
        if (validateEmails()) {
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
        }
      } catch (e) {
        loading.value = false;
        console.log(e);
      }
    };
    const addTag = (newTag) => {
      const tag = {
        label: newTag,
        value: newTag,
      };
      options.value.push(tag);
    };
    return {
      v$,
      form,
      submitForm,
      loading,
      addTag,
      options,
      value,
    };
  },
};
</script>

<style>
.multiselect-tags-search:focus {
  --tw-ring-shadow: none !important;
}
</style>