<template>
  <h1 class="text-center font-bold text-2xl">New Company</h1>
  <form @submit.prevent="submitForm">
    <div class="shadow-lg rounded-md p-3">
      <p class="font-bold">General Properties</p>
      <div>
        <label for="">Name</label>
        <input
          type="text"
          class="w-full"
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
        <label class="w-full pb-2 block">Type</label>
        <input type="radio" value="1" v-model="form.type" id="yes" />
        <label for="yes"> Customer </label>
        <input type="radio" value="0" v-model="form.type" id="no" /><label
          for="no"
        >
          Distributor</label
        >
      </div>
      <div>
        <label for="">Distributor</label>
        <multi-select
          :options="distributors"
          class="w-full"
          :disabled="form.type == 1 ? true : false"
          v-model="form.distributor"
          :searchable="true"
          placeholder="Select Distributor"
        />
        <p
          v-for="error of v$.country_id.$errors"
          :key="error.$uid"
          class="text-red-400"
        >
          {{ error.$message }}
        </p>
      </div>
    </div>
    <div class="shadow-lg rounded-md p-3">
      <p class="font-bold">Company Details</p>

      <div>
        <label for="">Address</label>
        <input
          type="text"
          class="w-full"
          autocomplete="off"
          v-model="form.address"
        />
      </div>
      <div>
        <label for="">City</label>
        <input
          type="text"
          class="w-full"
          autocomplete="off"
          v-model="form.city"
        />
      </div>
      <div>
        <label for="">State</label>
        <input
          type="text"
          class="w-full"
          autocomplete="off"
          v-model="form.state"
        />
      </div>
      <div>
        <label for="">Zip</label>
        <input
          type="text"
          class="w-full"
          autocomplete="off"
          v-model="form.zip"
        />
      </div>

      <div>
        <label for="">Country</label>
        <multi-select
          :options="countries"
          class="w-full"
          v-model="form.country_id"
          :searchable="true"
          placeholder="Select Country"
        />
      </div>
      <div>
        <label for="">Phone</label>
        <input
          type="text"
          class="w-full"
          autocomplete="off"
          v-model="form.phone"
        />
      </div>
      <div>
        <label for="">Fax</label>
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
export default {
  emits: ["closeModal"],
  props: ["countries", "distributors"],
  components: {
    multiSelect: Multiselect,
  },
  setup(props, { emit }) {
    const { form, v$, submitForm } = useFormCompany({
      id: 0,
      name: "",
      address: "",
      city: "",
      type: 1,
      state: "",
      zip: "",
      phone: "",
      fax: "",
      customer: 0,
      distributor: 0,
      notes: "",
      country_id: 0,
    });
    const closeModal = () => {
      emit("closeModal");
    };

    return {
      form,
      v$,
      submitForm,
      closeModal,
    };
  },
};
</script>
