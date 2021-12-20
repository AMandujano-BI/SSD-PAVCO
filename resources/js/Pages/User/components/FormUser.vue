<template>
  <h1 class="text-center font-bold text-2xl">New User</h1>
  <form @submit.prevent="submitForm">
    <div class="shadow-lg rounded-md p-3">
      <p class="font-bold">User Details</p>
      <div>
        <label for="">UserName</label>
        <input
          type="text"
          class="w-full"
          autocomplete="off"
          v-model="form.username"
          :class="{ 'border-red-500': v$.username.$error }"
        />
        <p
          v-for="error of v$.username.$errors"
          :key="error.$uid"
          class="text-red-400"
        >
          {{ error.$message }}
        </p>
      </div>
      <div>
        <label for="">FirstName</label>
        <input
          type="text"
          class="w-full"
          autocomplete="off"
          v-model="form.firstname"
          :class="{ 'border-red-500': v$.firstname.$error }"
        />
        <p
          v-for="error of v$.firstname.$errors"
          :key="error.$uid"
          class="text-red-400"
        >
          {{ error.$message }}
        </p>
      </div>
      <div>
        <label for="">LastName</label>
        <input
          type="text"
          class="w-full"
          autocomplete="off"
          v-model="form.lastname"
        />
        <!-- :class="{ 'border-red-500': v$.name.$error }" -->
        <!-- <p
          v-for="error of v$.name.$errors"
          :key="error.$uid"
          class="text-red-400"
        >
          {{ error.$message }}
        </p> -->
      </div>
      <div>
        <label for="">Email Address</label>
        <input
          type="text"
          class="w-full"
          autocomplete="off"
          v-model="form.email"
          :class="{ 'border-red-500': v$.email.$error }"
        />
        <p
          v-for="error of v$.email.$errors"
          :key="error.$uid"
          class="text-red-400"
        >
          {{ error.$message }}
        </p>
      </div>
      <div>
        <label for="">Company</label>
        <multi-select
          :options="companies"
          class="w-full"
          v-model="form.company_id"
          :searchable="true"
          placeholder="Select Company"
        />
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
          v-model="form.confirm_password"
          :class="{ 'border-red-500': v$.confirm_password.$error }"
        />
        <!-- :class="{ 'border-red-500': v$.name.$error }" -->
        <p
          v-for="error of v$.confirm_password.$errors"
          :key="error.$uid"
          class="text-red-400"
        >
          {{ error.$message }}
        </p>
      </div>
      <div class="flex flex-col md:flex-row justify-around w-full gap-4">
        <button
          type="button"
          class="bg-red-600 rounded w-full py-5 text-white px-3 mt-2"
          @click="closeModal"
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
    </div>
  </form>
</template>

<script>
import useFormUser from "../composables/useFormUser";
import Multiselect from "@vueform/multiselect";
export default {
  components: {
    multiSelect: Multiselect,
  },
  props: ["companies"],
  setup() {
    const { form, submitForm, v$ } = useFormUser({
      firstname: "",
      lastname: "",
      username: "",
      email: "",
      password: "",
      confirm_password: "",
      country_id: 0,
    });

    return {
      form,
      submitForm,
      v$,
    };
  },
};
</script>

<style>
</style>