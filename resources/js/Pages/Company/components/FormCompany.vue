<template>
  <h1 class="text-center font-bold text-2xl text-[#3b4559]">
    {{ form.id == 0 ? "New Company" : "Update Company" }}
  </h1>
  <form @submit.prevent="submitForm">
    <div class="shadow-lg rounded-md p-3">
      <p class="font-bold text-[#3b4559]">General Properties</p>
      <div>
        <label class="text-[#3b4559] font-semibold">Name</label>
        <input
          type="text"
          class="w-full"
          autocomplete="nope"
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
        <label class="w-full pb-2 block text-[#3b4559] font-semibold">Type</label>
        <input
          type="radio"
          value="1"
          v-model="form.customer"
          id="yes"
        />
        <label for="yes" class="text-[#3b4559]"> Customer </label>
        <input
          type="radio"
          value="0"
          v-model="form.customer"
          id="no"
        /><label for="no" class="text-[#3b4559]"> Distributor</label>
      </div>
      <div>
        <label class="text-[#3b4559] font-semibold">Distributor</label>
        <multi-select
          :options="distributors"
          class="w-full"
          :disabled="form.customer == 0 ? true : false"
          v-model="form.company_id"
          :searchable="true"
          placeholder="Select Distributor"
        />
        <!-- <p
          v-for="error of v$.company_id.$errors"
          :key="error.$uid"
          class="text-red-400"
        >
          {{ error.$message }}
        </p> -->
      </div>
    </div>
    <div class="shadow-lg rounded-md p-3">
      <p class="font-bold text-[#3b4559]">Company Details</p>

      <div>
        <label class="text-[#3b4559] font-semibold">Address</label>
        <input
          type="text"
          class="w-full"
          autocomplete="nope"
          v-model="form.address"
        />
      </div>
      <div>
        <label class="text-[#3b4559] font-semibold">City</label>
        <input
          type="text"
          class="w-full"
          autocomplete="nope"
          v-model="form.city"
        />
      </div>
      <div>
        <label class="text-[#3b4559] font-semibold">State</label>
        <input
          type="text"
          class="w-full"
          autocomplete="nope"
          v-model="form.state"
        />
      </div>
      <div>
        <label class="text-[#3b4559] font-semibold">Zip</label>
        <input
          type="text"
          class="w-full"
          autocomplete="nope"
          v-model="form.zip"
        />
         <p
          v-for="error of v$.zip.$errors"
          :key="error.$uid"
          class="text-red-400"
        >
          {{ error.$message }}
        </p>
      </div>

      <div>
        <label class="text-[#3b4559] font-semibold">Country</label>
        <multi-select
          :options="countries"
          class="w-full"
          v-model="form.country_id"
          :searchable="true"
          autocomplete="nope"
          placeholder="Select Country"
        />
        <p
          v-for="error of v$.country_id.$errors"
          :key="error.$uid"
          class="text-red-400"
        >
          {{ error.$message }}
        </p>
      </div>
      <div>
        <label class="text-[#3b4559] font-semibold">Phone</label>
        <input
          type="text"
          class="w-full"
          autocomplete="nope"
          v-model="form.phone"
        />
      </div>
      <div>
        <label class="text-[#3b4559] font-semibold">Fax</label>
        <input
          type="text"
          class="w-full"
          autocomplete="off"
          v-model="form.fax"
        />
      </div>
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
  </form>
</template>

<script>
import Multiselect from "@vueform/multiselect";
import useFormCompany from "../composables/useFormCompany";
import { useStore } from "vuex";
import { computed, ref } from 'vue-demi';
export default {
  emits: ["closeModal", "generateTable"],
  props: ["countries"],
  components: {
    multiSelect: Multiselect,
  },
  setup(props, { emit }) {
    const store = useStore();
    const { form, v$, submitForm } = useFormCompany(store.state.companies.form);
    const distributors = ref(computed(()=>store.state.companies.tableDistributors))
    const closeModal = () => {
      emit("closeModal");
    };

    return {
      form,
      v$,
      submitForm,
      closeModal,
      distributors

    };
  },
};
</script>
