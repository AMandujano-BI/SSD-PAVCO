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
      <input type="date" class="w-full" v-model="form.startDate" />
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
      <select class="w-full" v-model="form.plate_methods_id">
        <option value="0" selected>Select plate Method</option>
        <option
          v-for="plateMethod in plateMethods"
          v-bind:key="plateMethod.id"
          :value="plateMethod.id"
        >
          {{ plateMethod.name }}
        </option>
      </select>

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
          <select class="w-full" v-model="form.plate_types_id">
            <option value="0" selected>Select Plate Type</option>
            <option
              v-for="plateType in plateTypes"
              v-bind:key="plateType.id"
              :value="plateType.id"
            >
              {{ plateType.name }}
            </option>
          </select>
          <p
            v-for="error of v$.plate_types_id.$errors"
            :key="error.$uid"
            class="text-red-400"
          >
            {{ error.$message }}
          </p>
        </div>
        <div>
          <input type="text" class="w-[60px]" v-model="form.plateThick" />
          <span class="w-1/2">mil</span>
        </div>
      </div>
    </div>
    <div>
      <label for="">Chromate</label>
      <div class="flex w-full flex-col md:flex-row gap-2">
        <div class="w-full">
          <select class="w-full" v-model="form.primaryCoatId">
            <option value="0" selected>Select Chromate</option>
            <option
              v-for="chromate in chromates"
              v-bind:key="chromate.id"
              :value="chromate.id"
            >
              {{ chromate.name }}
            </option>
          </select>
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
            <input type="text" class="w-[60px]" v-model="form.primaryPer" />
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
            <input type="text" class="w-[60px]" v-model="form.primaryTemp" />
            <span>°F</span>
          </div>
          <div>
            <input type="text" class="w-[60px]" v-model="form.primaryPH" />
            <span>pH</span>
          </div>
          <div>
            <input type="text" class="w-[60px]" v-model="form.primaryDiptime" />
            <span>sec</span>
          </div>
        </div>
      </div>
    </div>
    <div>
      <label for="">TopCoat</label>
      <div class="flex w-full justify-around gap-2">
        <div class="w-full">
          <select class="w-full" v-model="form.topCoatId">
            <option value="0" selected>Select TopCoat</option>
            <option
              v-for="topCoat in topCoats"
              v-bind:key="topCoat.id"
              :value="topCoat.id"
            >
              {{ topCoat.name }}
            </option>
          </select>
          <p
            v-for="error of v$.primaryCoatId.$errors"
            :key="error.$uid"
            class="text-red-400"
          >
            {{ error.$message }}
          </p>
        </div>
        <div class="flex">
          <div>
            <input type="text" class="w-[60px]" v-model="form.topCoatPer" />
            <span>%</span>
          </div>

          <div>
            <input type="text" class="w-[60px]" v-model="form.topCoatTemp" />
            <span>°F</span>
          </div>
          <div>
            <input type="text" class="w-[60px]" v-model="form.topCoatPH" />
            <span>pH</span>
          </div>
          <div>
            <input type="text" class="w-[60px]" v-model="form.topCoatDiptime" />
            <span>sec</span>
          </div>
        </div>
      </div>
    </div>
    <div>
      <label for="">Secondary Topcoat</label>
      <div class="flex w-full justify-around gap-2">
        <div class="w-full">
          <select class="w-full" v-model="form.coatId">
            <option value="0" selected>Select Secondary Topcoat</option>
            <option
              v-for="secondaryCoat in secondaryCoats"
              v-bind:key="secondaryCoat.id"
              :value="secondaryCoat.id"
            >
              {{ secondaryCoat.name }}
            </option>
          </select>
          <p
            v-for="error of v$.coatId.$errors"
            :key="error.$uid"
            class="text-red-400"
          >
            {{ error.$message }}
          </p>
        </div>
        <div class="flex">
          <div>
            <input type="text" class="w-[60px]" v-model="form.coatPer" />
            <span>%</span>
          </div>

          <div>
            <input type="text" class="w-[60px]" v-model="form.coatTemp" />
            <span>°F</span>
          </div>
          <div>
            <input type="text" class="w-[60px]" v-model="form.coatPH" />
            <span>pH</span>
          </div>
          <div>
            <input type="text" class="w-[60px]" v-model="form.coatDiptime" />
            <span>sec</span>
          </div>
        </div>
      </div>
    </div>
    <div class="py-5">
      <label for="" class="pr-5">Parts</label>
      <input type="number" placeholder="Parts" v-model="form.numberParts" />
      <p
        v-for="error of v$.numberParts.$errors"
        :key="error.$uid"
        class="text-red-400"
      >
        {{ error.$message }}
      </p>
    </div>
    <button class="bg-blue-600 rounded w-full py-5 text-white px-3 mt-2">
      Save
    </button>
  </form>
</template>
<script>
import useFormRun from "../composables/useFormRun";

export default {
  props: [
    "plateMethods",
    "topCoats",
    "chromates",
    "secondaryCoats",
    "plateTypes",
  ],
  setup() {
    const { form, v$, submitForm } = useFormRun();

    return {
      v$,
      form,
      submitForm,
    };
  },
};
</script>