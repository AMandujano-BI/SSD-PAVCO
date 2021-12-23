<template>
  <h1 class="text-center font-bold text-2xl">Reset Password</h1>
  <form @submit.prevent="submitForm">
    <div class="shadow rounded-md py-5 mb-3 px-2">
      <p class="font-bold text-2xl">Username</p>
      <p class="font-bold">{{ username }}</p>
    </div>
    <div>
      <label for="">Password</label>
      <input
        type="password"
        class="w-full"
        autocomplete="off"
        v-model="form.password"
        :class="{ 'border-red-500': v$.password.$error }"
      />
      <!-- :class="{ 'border-red-500': v$.name.$error }" -->
      <p
        v-for="error of v$.password.$errors"
        :key="error.$uid"
        class="text-red-400"
      >
        {{ error.$message }}
      </p>
    </div>
    <div>
      <label for="">Confirm Password</label>
      <input
        type="password"
        class="w-full"
        autocomplete="off"
        v-model="form.resetPassword"
        :class="{ 'border-red-500': v$.resetPassword.$error }"
      />
      <!-- :class="{ 'border-red-500': v$.name.$error }" -->
      <p
        v-for="error of v$.resetPassword.$errors"
        :key="error.$uid"
        class="text-red-400"
      >
        {{ error.$message }}
      </p>
    </div>
    <div class="flex flex-col md:flex-row justify-between w-full gap-4">
      <button
        type="button"
        class="
          bg-red-600
          hover:bg-red-800
          rounded
          w-full
          py-5
          text-white
          px-3
          mt-2
        "
        @click="closeModalForm"
      >
        Cancel
      </button>
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
    </div>
  </form>
</template>

<script>
import { computed, reactive, ref } from "vue";
import useHelper from "@/composables/useHelper";
import useVuelidate from "@vuelidate/core";
import { required, sameAs,minLength } from "@vuelidate/validators";
import ButtonsFormVue from "@/Jetstream/ButtonsForm";
import axios from "axios";
export default {
  components: {
    buttonsForm: ButtonsFormVue,
  },
  props: ["username"],
  emits: ["closeModal"],
  setup(props, { emit }) {
    const username = ref(props.username);
    const loading = ref(false);
    const form = reactive({
      username: username,
      password: "",
      resetPassword: "",
    });
    const { makeToast } = useHelper();
    const rules = computed(() => ({
      password: { required,minLength:minLength(8) },
      resetPassword: { sameAsPassword: sameAs(form.password) },
    }));
    const v$ = useVuelidate(rules, form);

    const submitForm = async () => {
      const isFormCorrect = await v$.value.$validate();
      if (!isFormCorrect) return;
      loading.value = true;
      const res = await axios.post("user/resetPassword", form);
      loading.value = false;
      const { ok, message, value } = res.data;
      if (ok) {
        makeToast(message);
        emit("closeModal");
      } else {
        makeToast(message, "error");
      }
    };

    return {
      v$,
      form,
      closeModalForm: () => emit("closeModal"),
      username,
      submitForm,
      loading,
    };
  },
};
</script>

<style>
</style>