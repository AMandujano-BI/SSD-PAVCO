<template>
  <h1 class="text-center font-bold text-2xl">
    {{ form.id == 0 ? "New User" : "Update User" }}
  </h1>
  <form @submit.prevent="submitForm">
    <div class="shadow-lg rounded-md p-3">
      <p class="font-bold">User Details</p>
      <div v-if="form.id ==0">
        <label for="">UserName</label>
        <input
          type="text"
          class="w-full my-2"
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
      <div v-if="form.id !=0" class="shadow rounded-md mb-5 p-2">
        <label for="" class="font-bold text-2xl">UserName</label>
        <p class="font-bold">{{form.username}}</p>

      </div>
      <div>
        <label for="" class="pb-2">Rol</label>
        <multi-select
          :options="rols"
          class="w-full my-2"
          v-model="form.rols"
          :searchable="true"
          mode="tags"
          :close-on-select="true"
          placeholder="Select a Rol"
        />
        <p
          v-for="error of v$.rols.$errors"
          :key="error.$uid"
          class="text-red-400"
        >
          {{ error.$message }}
        </p>
      </div>
      <div>
        <label for="" >FirstName</label>
        <input
          type="text"
          class="w-full my-2"
          autocomplete="off"
          v-model="form.name"
          :class="{ 'border-red-500': v$.name.$error }"
        />
        <p
          v-for="error of v$.name.$errors"
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
          class="w-full my-2"
          autocomplete="off"
          v-model="form.lastname"
        />
      </div>
      <div>
        <label for="">Email Address</label>
        <input
          type="text"
          class="w-full my-2"
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
      <div >
        <label class="mb-2">Company</label>
        <multi-select
          :options="companies"
          class="w-full my-2"
          v-model="form.company_id"
          :searchable="true"
          placeholder="Select Company"
        />
        <p
          v-for="error of v$.company_id.$errors"
          :key="error.$uid"
          class="text-red-400"
        >
          {{ error.$message }}
        </p>
      </div>
      <div v-if="form.id == 0">
          <label for="">Password</label>
          <input
            type="password"
            class="w-full my-2"
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
        <div>
          <label for="">Confirm Password</label>
          <input
            type="password"
            class="w-full my-2"
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
      </div>
      <div class="flex flex-col md:flex-row justify-around w-full gap-4 pt-5">
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
import { useStore } from "vuex";
export default {
  emits: ["closeModal","generateTable"],
  components: {
    multiSelect: Multiselect,
  },
  props: ["companies", "rols"],
  setup(props, { emit }) {
    const store = useStore();
    const { form, submitForm, v$ } = useFormUser(store.state.users.form);
    const closeModal = () => {
      emit("closeModal");
    };

    return {
      form,
      submitForm,
      v$,
      closeModal,
    };
  },
};
</script>

<style>
</style>