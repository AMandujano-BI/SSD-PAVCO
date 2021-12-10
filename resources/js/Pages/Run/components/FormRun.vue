<template>
  <form @submit.prevent="submitForm">
    <h1 class="text-center">Run Editor</h1>
    <div>
      <label for="">Customer</label>
      <!-- <select v-model="form.type" class="w-full"> -->

      <select class="w-full" v-model="form.user_id">
        <option value="0" selected>Select a Customer</option>
        <option value="1">Plating</option>
        <option value="2">Chromate</option>
        <option value="3">TopCoat</option>
        <option value="4">Secondary TopCoat</option>
      </select>
      <p
        v-for="error of v$.user_id.$errors"
        :key="error.$uid"
        class="text-red-400"
      >
        {{ error.$message }}
      </p>
    </div>
    <div>
      <label for="">Start Date</label>
      <input
        type="date"
        class="w-full"
        v-model="form.startDate"
        :class="{ 'border-red-500': v$.startDate.$error }"
      />
      <p
        v-for="error of v$.startDate.$errors"
        :key="error.$uid"
        class="text-red-400"
      >
        {{ error.$message }}
      </p>
    </div>

    <div>
      <label for="">Plate Method</label>
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
      <label for="">Description</label>
      <textarea
        cols="30"
        rows="5"
        class="w-full"
        :class="{ 'border-red-500': v$.description.$error }"
        v-model="form.description"
      ></textarea>
      <!-- <div v-if="v$.form.description.$error" class="text-red-400">Name field has an error.</div> -->
      <p
        v-for="error of v$.description.$errors"
        :key="error.$uid"
        class="text-red-400"
      >
        {{ error.$message }}
      </p>
    </div>
    <div>
      <label for="">Plate Type</label>
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
            v-model="form.plateThick"
            :class="{ 'border-red-500': v$.plateThick.$error }"
          />
          <span class="w-1/2">mil</span>
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
      <label for="">Chromate</label>
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
              :class="{ 'border-red-500': v$.primaryPer.$error }"
            />
            <span>%</span>
            <p
              v-for="error of v$.primaryPer.$errors"
              :key="error.$uid"
              class="text-red-400"
            >
              {{ error.$message }}
            </p>
          </div>

          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.primaryTemp"
              :class="{ 'border-red-500': v$.primaryTemp.$error }"
            />
            <span>°F</span>
            <p
              v-for="error of v$.primaryTemp.$errors"
              :key="error.$uid"
              class="text-red-400"
            >
              {{ error.$message }}
            </p>
          </div>
          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.primaryPH"
              :class="{ 'border-red-500': v$.primaryPH.$error }"
            />
            <span>pH</span>
            <p
              v-for="error of v$.primaryPH.$errors"
              :key="error.$uid"
              class="text-red-400"
            >
              {{ error.$message }}
            </p>
          </div>
          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.primaryDiptime"
              :class="{ 'border-red-500': v$.primaryDiptime.$error }"
            />
            <span>sec</span>
            <p
              v-for="error of v$.primaryDiptime.$errors"
              :key="error.$uid"
              class="text-red-400"
            >
              {{ error.$message }}
            </p>
          </div>
        </div>
      </div>
    </div>
    <div>
      <label for="">TopCoat</label>
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
                :class="{ 'border-red-500': v$.topCoatPer.$error }"
              />
              <span>%</span>
              <p
                v-for="error of v$.topCoatPer.$errors"
                :key="error.$uid"
                class="text-red-400"
              >
                {{ error.$message }}
              </p>
            </div>

            <div>
              <input
                type="text"
                class="w-[60px]"
                v-model="form.topCoatTemp"
                :class="{ 'border-red-500': v$.topCoatTemp.$error }"
              />
              <span>°F</span>
              <p
                v-for="error of v$.topCoatTemp.$errors"
                :key="error.$uid"
                class="text-red-400"
              >
                {{ error.$message }}
              </p>
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                v-model="form.topCoatPH"
                :class="{ 'border-red-500': v$.topCoatPH.$error }"
              />
              <span>pH</span>
              <p
                v-for="error of v$.topCoatPH.$errors"
                :key="error.$uid"
                class="text-red-400"
              >
                {{ error.$message }}
              </p>
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                :class="{ 'border-red-500': v$.topCoatDiptime.$error }"
                v-model="form.topCoatDiptime"
              />
              <span>sec</span>
              <p
                v-for="error of v$.topCoatDiptime.$errors"
                :key="error.$uid"
                class="text-red-400"
              >
                {{ error.$message }}
              </p>
            </div>
        </div>
      </div>
    </div>
    <div>
      <label for="">Secondary Topcoat</label>
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
              :class="{ 'border-red-500': v$.coatPer.$error }"
            />
            <span>%</span>
            <p
              v-for="error of v$.coatPer.$errors"
              :key="error.$uid"
              class="text-red-400"
            >
              {{ error.$message }}
            </p>
          </div>

          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.coatTemp"
              :class="{ 'border-red-500': v$.coatTemp.$error }"
            />
            <span>°F</span>

            <p
              v-for="error of v$.coatTemp.$errors"
              :key="error.$uid"
              class="text-red-400"
            >
              {{ error.$message }}
            </p>
          </div>
          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.coatPH"
              :class="{ 'border-red-500': v$.coatPH.$error }"
            />
            <span>pH</span>
            <p
              v-for="error of v$.coatPH.$errors"
              :key="error.$uid"
              class="text-red-400"
            >
              {{ error.$message }}
            </p>
          </div>
          <div>
            <input
              type="text"
              class="w-[60px]"
              v-model="form.coatDiptime"
              :class="{ 'border-red-500': v$.coatDiptime.$error }"
            />
            <span>sec</span>

            <p
              v-for="error of v$.coatDiptime.$errors"
              :key="error.$uid"
              class="text-red-400"
            >
              {{ error.$message }}
            </p>
          </div>
        </div>
      </div>
    </div>
    <div class="py-5">
      <label for="" class="pr-5">Parts</label>
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

    <div class="flex justify-between w-full gap-4">
      <button
        type="button"
        class="bg-red-600 rounded w-full py-5 text-white px-3 mt-2"
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
  ],
  components: {
    multiSelect: Multiselect,
  },
  setup(props, { emit }) {
    const { form, v$, submitForm, loading, options, changeDropdown } =
      useFormRun();
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