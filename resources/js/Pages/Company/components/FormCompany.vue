<template>
  <h1 class="text-center font-bold text-2xl">New Company</h1>
  <form @submit.prevent="submitForm">
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
      <label for="">Distributor</label>
      <multi-select
        :options="distributors"
        class="w-full"
        v-model="form.distributor"
        :searchable="true"
        placeholder="Select Distributor"
      />
    </div>

    <div>
      <label for="">Address</label>
      <input
        type="text"
        class="w-full"
        autocomplete="off"
        v-model="form.address"
        :class="{ 'border-red-500': v$.address.$error }"
      />
      <p
        v-for="error of v$.address.$errors"
        :key="error.$uid"
        class="text-red-400"
      >
        {{ error.$message }}
      </p>
    </div>
    <div>
      <label for="">City</label>
      <input
        type="text"
        class="w-full"
        autocomplete="off"
        v-model="form.city"
        :class="{ 'border-red-500': v$.city.$error }"
      />
      <p
        v-for="error of v$.city.$errors"
        :key="error.$uid"
        class="text-red-400"
      >
        {{ error.$message }}
      </p>
    </div>
    <div>
      <label for="">State</label>
      <input
        type="text"
        class="w-full"
        autocomplete="off"
        v-model="form.state"
        :class="{ 'border-red-500': v$.state.$error }"
      />
      <p
        v-for="error of v$.state.$errors"
        :key="error.$uid"
        class="text-red-400"
      >
        {{ error.$message }}
      </p>
    </div>
    <div>
      <label for="">Zip</label>
      <input
        type="text"
        class="w-full"
        autocomplete="off"
        v-model="form.zip"
        :class="{ 'border-red-500': v$.zip.$error }"
      />
      <p v-for="error of v$.zip.$errors" :key="error.$uid" class="text-red-400">
        {{ error.$message }}
      </p>
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
        :class="{ 'border-red-500': v$.phone.$error }"
      />
      <p
        v-for="error of v$.phone.$errors"
        :key="error.$uid"
        class="text-red-400"
      >
        {{ error.$message }}
      </p>
    </div>
    <div>
      <label for="">Fax</label>
      <input
        type="text"
        class="w-full"
        autocomplete="off"
        v-model="form.fax"
        :class="{ 'border-red-500': v$.fax.$error }"
      />
      <p v-for="error of v$.fax.$errors" :key="error.$uid" class="text-red-400">
        {{ error.$message }}
      </p>
    </div>
    <div class="flex justify-around w-full gap-4">
      <button
        type="button"
        class="bg-red-600 rounded w-full py-5 text-white px-3 mt-2"
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
import { reactive, ref } from "vue";
import Multiselect from "@vueform/multiselect";
import useVuelidate from "@vuelidate/core";
import { required, helpers } from "@vuelidate/validators";
export default {
  props: ["countries","distributors"],
  components: {
    multiSelect: Multiselect,
  },
  setup(props) {
    const form = reactive({
      id: 0,
      name: "",
      address: "",
      city: "",
      state: "",
      zip: "",
      phone: "",
      fax: "",
      customer: 0,
      distributor: 0,
      notes: "",
      country_id: 0,
    });
    const errorLength = (text) => {
      return true;
    };
    const rules = {
      name: {
        required,
      },
      address: {
        required,
      },
      city: {
        required,
      },
      state: {
        required,
      },
      zip: {
        required,
      },
      phone: {
        required,
      },
      fax: {
        required,
      },
    };

    const v$ = useVuelidate(rules, form);
    const submitForm = async () => {
      const isFormCorrect = await v$.value.$validate();
      console.log(isFormCorrect);
    };

    return {
      form,
      v$,
      submitForm,
      errorLength,
    };
  },
};
</script>
