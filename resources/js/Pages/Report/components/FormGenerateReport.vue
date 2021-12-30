<template>
  <p class="text-center">
    The Report Wizard allows you to create a report based on selected criteria.
  </p>
  <form @submit.prevent="submitForm">
    <div class="bg-white ml-2 mr-8 mt-4 mb-3 p-3 flex justify-around h-104">
      <div class="flex">
        <label class="pr-3 font-bold">Customer</label>
        <multi-select
          :options="customers"
          class="min-w-full flex-1 w-[300px]"
          v-model="form.customer"
          :searchable="true"
          :close-on-select="true"
          placeholder="Select a Customer"
        />
        <p
          v-for="error of v$.customer.$errors"
          :key="error.$uid"
          class="text-red-400"
        >
          {{ error.$message }}
        </p>
      </div>

      <div class="flex">
        <div></div>
        <label class="pr-3 font-bold">Date Range</label>
        <input
          type="date"
          v-model="form.startDate"
          :class="{ 'border-red-400': v$.startDate.$error }"
        />
        <p
          v-for="error of v$.startDate.$errors"
          :key="error.$uid"
          class="text-red-400"
        >
          {{ error.$message }}
        </p>
      </div>

      <div class="flex">
        <label class="pr-3 font-bold">To</label>
        <input
          type="date"
          v-model="form.endDate"
          :class="{ 'border-red-400': v$.endDate.$error }"
        />
        <p
          v-for="error of v$.endDate.$error"
          :key="error.$uid"
          class="text-red-400"
        >
          {{ error.$message }}
        </p>
      </div>
    </div>

    <div class="bg-white ml-2 mr-8 mt-4 mb-3 p-3 h-170">
      <div class="flex">
        <label class="pr-3 font-bold">Plate Type</label>
        <multi-select
          :options="plateTypes"
          class="w-full"
          v-model="form.plate_type"
          :searchable="true"
          placeholder="Select Plate Type"
        />
      </div>

      <div class="flex mt-3">
        <label class="pr-3 font-bold">Thickness</label>
        <div class="flex w-full flex-col md:flex-row gap-2">
            <multi-select
              :options="listThickness"
              class=" w-[150px]"
              v-model="form.thickness"
              :searchable="true"
              placeholder="Select Thickness"
            />
          <div class="flex gap-2">
            <div>
              <input
                type="text"
                class="w-20"
                placeholder="mil"
                v-model="form.thickness_first_ml"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-20"
                placeholder="mil"
                v-model="form.thickness_second_ml"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white ml-2 mr-8 mt-4 mb-3 p-3 h-338">
      <div class="flex w-full flex-col md:flex-row gap-2">
        <div class="w-full"></div>
        <div class="flex gap-2">
          <div>
            <div class="w-[130px] font-bold text-center">Conc%</div>
          </div>
          <div>
            <div class="w-[130px] font-bold text-center">Temp</div>
          </div>
          <div>
            <div class="w-[130px] font-bold text-center">pH</div>
          </div>
          <div>
            <div class="w-[130px] font-bold text-center">Diptime</div>
          </div>
        </div>
      </div>

      <div>
        <div class="flex w-full flex-col md:flex-row gap-2">
          <div class="w-full"></div>
          <div class="flex gap-2">
            <div>
              <div class="w-[60px] text-center">&gt;</div>
            </div>
            <div>
              <div class="w-[60px] text-center">&lt;</div>
            </div>
            <div>
              <div class="w-[60px] text-center">&gt;</div>
            </div>
            <div>
              <div class="w-[60px] text-center">&lt;</div>
            </div>
            <div>
              <div class="w-[60px] text-center">&gt;</div>
            </div>
            <div>
              <div class="w-[60px] text-center">&lt;</div>
            </div>
            <div>
              <div class="w-[60px] text-center">&gt;</div>
            </div>
            <div>
              <div class="w-[60px] text-center">&lt;</div>
            </div>
          </div>
        </div>
      </div>

      <div>
        <label>Chromate</label>
        <div class="flex w-full flex-col md:flex-row gap-2">
          <div class="w-full">
            <multi-select
              :options="chromates"
              class="w-full"
              v-model="form.chromate"
              :searchable="true"
              placeholder="Select Chromate"
            />
          </div>
          <div class="flex gap-2">
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.primaryPer_more_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.primaryPer_less_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.primaryTemp_more_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.primaryTemp_less_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.primaryPH_more_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.primaryPH_less_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.primaryDiptime_more_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.primaryDiptime_less_than"
              />
            </div>
          </div>
        </div>
      </div>

      <div>
        <label>Topcoat</label>
        <div class="flex w-full flex-col md:flex-row gap-2">
          <div class="w-full">
            <multi-select
              :options="topCoats"
              class="w-full"
              v-model="form.top_coat"
              :searchable="true"
              placeholder="Select TopCoat"
            />
          </div>
          <div class="flex gap-2">
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.topCoatPer_more_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.topCoatPer_less_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.topCoatTemp_more_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.topCoatTemp_less_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.topCoatPH_more_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.topCoatPH_less_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.topCoatDiptime_more_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.topCoatDiptime_less_than"
              />
            </div>
          </div>
        </div>
      </div>

      <div>
        <label>Secondary Topcoat</label>
        <div class="flex w-full flex-col md:flex-row gap-2">
          <div class="w-full">
            <multi-select
              :options="secondaryCoats"
              class="w-full"
              v-model="form.coat"
              :searchable="true"
              placeholder="Select Secondary Topcoat"
            />
          </div>
          <div class="flex gap-2">
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.coatPer_more_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.coatPer_less_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.coatTemp_more_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.coatTemp_less_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.coatPH_more_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.coatPH_less_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.coatDiptime_more_than"
              />
            </div>
            <div>
              <input
                type="text"
                class="w-[60px]"
                placeholder="0"
                v-model="form.coatDiptime_less_than"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="ml-2 mr-8 mt-4 mb-3 p-3">
      <div class="flex w-full flex-col md:flex-row gap-2">
        <div class="w-full"></div>
        <div class="flex gap-2">
          <div>
            <!-- <button class="bg-primary rounded w-60 py-2.5 px-1.8 text-white hover:bg-primary-600">Run Summary Report</button> -->
          </div>
          <div>
            <button
              class="
                bg-primary
                rounded
                w-60
                py-2.5
                px-1.8
                text-white
                hover:bg-primary-600
              "
            >
              Run Detailed Report
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>
</template>

<script>
import Multiselect from "@vueform/multiselect";
// import Multiselect from '@vueform/multiselect/src/Multiselect'
import useFormReport from "../composables/useFormReport";
import { ref } from "vue-demi";

// importar composable
export default {
  props: ["customers", "topCoats", "chromates", "plateTypes", "secondaryCoats"],
  components: {
    multiSelect: Multiselect,
  },
  setup() {
    const { form, v$, submitForm } = useFormReport({
      customer: 0,
      startDate: "",
      endDate: "",
      plate_type: 0,
      thickness: 0,
      thickness_first_ml: 0,
      thickness_second_ml: 0,
      chromate: 0,
      primaryPer_more_than: 0,
      primaryPer_less_than: 0,
      primaryTemp_more_than: 0,
      primaryTemp_less_than: 0,
      primaryPH_more_than: 0,
      primaryPH_less_than: 0,
      primaryDiptime_more_than: 0,
      primaryDiptime_less_than: 0,
      top_coat: 0,
      topCoatPer_more_than: 0,
      topCoatPer_less_than: 0,
      topCoatTemp_more_than: 0,
      topCoatTemp_less_than: 0,
      topCoatPH_more_than: 0,
      topCoatPH_less_than: 0,
      topCoatDiptime_more_than: 0,
      topCoatDiptime_less_than: 0,
      coat: 0,
      coatPer_more_than: 0,
      coatPer_less_than: 0,
      coatTemp_more_than: 0,
      coatTemp_less_than: 0,
      coatPH_more_than: 0,
      coatPH_less_than: 0,
      coatDiptime_more_than: 0,
      coatDiptime_less_than: 0,
    });
    const listThickness = ref([
      { value: 0, label: "Select Operator" },
      { value: 1, label: "Less Than" },
      { value: 3, label: "Less Than or equal to" },
      { value: 4, label: "Equal to" },
    ]);

    return {
      form,
      v$,
      submitForm,
      listThickness,
    };
  },
};
</script>

<style>
</style>