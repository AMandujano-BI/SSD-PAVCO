<template>
  <form @submit.prevent="submitForm">
    <h1 class="text-center text-[#3b4559] font-semibold text-2xl">Run Editor</h1>
    <div>
      <label  class="pb-2 text-[#3b4559] font-semibold ">Customer</label>

      <multi-select
        :options="customers"
        class="w-full mt-4"
        v-model="form.company_id"
        :searchable="true"
        placeholder="Select Customer"
      />
      <p
        v-for="error of v$.company_id.$errors"
        :key="error.$uid"
        class="text-red-400"
      >
        {{ error.$message }}
      </p>
    </div>
    <div>
      <label for="" class="text-[#3b4559] font-semibold">Start Date</label>
      <input
        type="datetime-local"
        class="w-full"
        v-model="form.start_date"
        :class="{ 'border-red-500': v$.start_date.$error }"
      />
      <p
        v-for="error of v$.start_date.$errors"
        :key="error.$uid"
        class="text-red-400"
      >
        {{ error.$message }}
      </p>
    </div>

    <div>
      <label for="" class="text-[#3b4559] font-semibold">Plate Method</label>
      <multi-select
        :options="plateMethods"
        class="w-full"
        v-model="form.plate_methods_id"
        :searchable="true"
        placeholder="Select plate Method"
      />
      <p
        v-for="error of v$.plate_methods_id.$errors"
        :key="error.$uid"
        class="text-red-400"
      >
        {{ error.$message }}
      </p>
    </div>
    <div>
      <label for="" class="text-[#3b4559] font-semibold">Description</label>
      <textarea
        cols="30"
        rows="5"
        class="w-full"
        v-model="form.description"
      ></textarea>
    </div>
    <div>
      <label class="text-[#3b4559] font-semibold">Plate Type</label>
      <div class="flex w-full justify-between gap-2">
        <div class="w-full">
          <multi-select
            :options="plateTypes"
            class="w-full"
            v-model="form.plate_types_id"
            :searchable="true"
            placeholder="Select Plate Type"
          />
          <!-- :class="{ 'error-dropdown': v$.plate_types_id.$error }" -->
          <p
            v-for="error of v$.plate_types_id.$errors"
            :key="error.$uid"
            class="text-red-400"
          >
            {{ error.$message }}
          </p>
        </div>
        <div>
          <input
            type="number"
            class="w-[60px]"
            step=".01"
            v-model="form.plateThick"
            :class="{ 'border-red-500': v$.plateThick.$error }"
          />
          <span class="text-center block pt-1 text-[#3b4559]">mils</span>
          <p
            v-for="error of v$.plateThick.$errors"
            :key="error.$uid"
            class="text-red-400"
          >
            {{ error.$message }}
          </p>
        </div>
      </div>
    </div>
    <div>
      <label class=" text-[#3b4559] font-semibold">Chromate</label>
      <div class="flex w-full flex-col md:flex-row gap-2">
        <div class="w-full">
          <multi-select
            :options="chromates"
            class="w-full"
            v-model="form.primaryCoatId"
            :searchable="true"
            placeholder="Select Chromates "
          />
          <p
            v-for="error of v$.primaryCoatId.$errors"
            :key="error.$uid"
            class="text-red-400"
          >
            {{ error.$message }}
          </p>
        </div>
        <div class="flex gap-2">
          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.primaryPer"
            />
            <span  class="text-center block pt-1 text-[#3b4559]">%</span>
   
          </div>

          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.primaryTemp"
            />
            <span  class="text-center block pt-1 text-[#3b4559]">°F</span>
    
          </div>
          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.primaryPH"
            />
            <span class="text-center block pt-1 text-[#3b4559]">pH</span>
      
          </div>
          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.primaryDiptime"
            />
            <span  class="text-center block pt-1 text-[#3b4559]">sec</span>
 
          </div>
        </div>
      </div>
    </div>
    <div>
      <label class="text-[#3b4559] font-semibold">TopCoat</label>
      <div class="flex w-full flex-col md:flex-row justify-around gap-2">
        <div class="w-full">
          <multi-select
            :options="topCoats"
            class="w-full"
            v-model="form.topCoatId"
            :searchable="true"
            placeholder="Select TopCoat"
          />
          <p
            v-for="error of v$.primaryCoatId.$errors"
            :key="error.$uid"
            class="text-red-400"
          >
            {{ error.$message }}
          </p>
        </div>
        <div class="flex gap-2">
          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.topCoatPer"
            />
            <span  class="text-center block pt-1 text-[#3b4559]">%</span>
     
          </div>

          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.topCoatTemp"
            />
            <span class="text-center block pt-1 text-[#3b4559]">°F</span>
    
          </div>
          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.topCoatPH"
            />
            <span  class="text-center block pt-1 text-[#3b4559]">pH</span>
    
          </div>
          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.topCoatDiptime"
            />
            <span  class="text-center block pt-1 text-[#3b4559]">sec</span>
      
          </div>
        </div>
      </div>
    </div>
    <div>
      <label class="text-[#3b4559] font-semibold">Secondary Topcoat</label>
      <div class="flex w-full flex-col md:flex-row justify-around gap-2">
        <div class="w-full">
          <multi-select
            :options="secondaryCoats"
            class="w-full"
            v-model="form.coatId"
            :searchable="true"
            placeholder="Select Secondary TopCoat"
          />
          <p
            v-for="error of v$.coatId.$errors"
            :key="error.$uid"
            class="text-red-400"
          >
            {{ error.$message }}
          </p>
        </div>
        <div class="flex gap-2">
          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.coatPer"
            />
            <span class="text-center block pt-1 text-[#3b4559]">%</span>
      
          </div>

          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.coatTemp"
            />
            <span class="text-center block pt-1 text-[#3b4559]">°F</span>

       
          </div>
          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.coatPH"
            />
            <span class="text-center block pt-1 text-[#3b4559]">pH</span>
          </div>
          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.coatDiptime"
            />
            <span class="text-center block pt-1 text-[#3b4559]">sec</span>

       
          </div>
        </div>
      </div>
    </div>
    <div class="py-5">
      <label  class=" text-[#3b4559] font-semibold pr-5">Parts</label>
      <input
        type="number"
        placeholder="Parts"
        v-model="form.numberParts"
        :class="{ 'border-red-500': v$.numberParts.$error }"
      />
      <p
        v-for="error of v$.numberParts.$errors"
        :key="error.$uid"
        class="text-red-400"
      >
        {{ error.$message }}
      </p>
    </div>

    <div class="flex flex-col md:flex-row justify-between w-full gap-4">
      <button
        type="button"
        class="bg-red-600  hover:bg-red-800 rounded w-full py-5 text-white px-3 mt-2"
        @click="closeModal"
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
import useFormRun from "../composables/useFormRun";
import Multiselect from "@vueform/multiselect";
export default {
  emits: "closeModal",
  props: [
    "plateMethods",
    "topCoats",
    "chromates",
    "secondaryCoats",
    "plateTypes",
    "customers",
  ],
  components: {
    multiSelect: Multiselect,
  },
  setup(props, { emit }) {
    const { form, v$, submitForm, loading, options, changeDropdown } = useFormRun();
    const closeModal = () => emit("closeModal");

    return {
      v$,
      form,
      submitForm,
      loading,
      closeModal,
      options,
      changeDropdown,
    };
  },
};
</script>