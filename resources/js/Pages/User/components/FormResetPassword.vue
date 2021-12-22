<template>
  <h1 class="text-center font-bold text-2xl">Reset Password</h1>
  <form @submit.prevent="submitForm">
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
import { computed, reactive } from "vue";
import { useStore } from "vuex";
import useHelper from "@/composables/useHelper";
import useVuelidate from "@vuelidate/core";
import { required, sameAs } from "@vuelidate/validators";
import ButtonsFormVue from "@/Jetstream/ButtonsForm";
import axios from 'axios';
export default {
  components: {
    buttonsForm: ButtonsFormVue,
  },
  emits: ["closeModal"],
  setup(props, { emit }) {
    const form = reactive({
      username: "",
      password: "",
      resetPassword: "",
    });
    const { makeToast } = useHelper();
    const rules = computed(() => ({
      password: { required },
      resetPassword: { sameAsPassword: sameAs(form.password) },
    }));
    const v$ = useVuelidate(rules, form);

    const submitForm = async () => {
      const isFormCorrect = await v$.value.$validate();
      if (!isFormCorrect) return;
    //   const res = await  axios.post('user/resetPassword')
      console.log(form);
    };

    return {
      v$,
      form,
      closeModalForm: () => emit("closeModal"),
      submitForm,
    };
  },
};
</script>

<style>
</style>