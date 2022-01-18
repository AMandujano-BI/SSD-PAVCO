<template>
  <p class="text-center text-[#3b4559]">
    The Report Wizard allows you to create a report based on selected criteria.
  </p>
  <form @submit.prevent="submitForm">
    <!-- FIRST -->
    <div
      class="
        bg-white
        mt-4
        mb-3
        md:pl-[60px]
        pr-8
        py-[32px]
        pl-4
        w-full
        grid grid-cols-2
        gap-2
        md:grid-cols-2
        lg:grid-cols-3
        items-center
      "
    >
      <div
        class="
          flex flex-col
          md:flex-row
          items-center
          flex-1
          w-full
          col-span-2
          lg:col-span-1
        "
      >
        <label
          class="
            pr-3
            font-semibold
            text-[16px]
            md:max-w-[110px]
            text-[#3b4559]
            md:text-right
            text-left
            mb-2
            md:mb-0
            w-full
          "
          >Customer</label
        >
        <div class="w-full">
          <multi-select
            :options="customers"
            class="w-[300px]"
            v-model="form.customer"
            :searchable="true"
            :close-on-select="true"
            @select="changeSelectCustomer"
            placeholder="Select a Customer"
          />
        </div>
      </div>
      <div>
        <div
          class="
            flex flex-col
            md:flex-row
            items-center
            flex-1
            w-full
            mt-4
            lg:mt-0
          "
        >
          <label
            class="
              pr-3
              font-semibold
              text-[16px] text-[#3b4559]
              md:mb-0
              mb-2
              md:text-right
              text-left
              md:max-w-[110px]
              w-full
            "
            >Date Range</label
          >
          <div class="w-full">
            <input
              type="date"
              v-model="form.start_date"
              class="w-full border-[#979797]"
              :class="{ 'border-red-400': v$.start_date.$error }"
            />
          </div>
        </div>
      </div>

      <div
        class="
          flex flex-col
          md:flex-row
          items-center
          flex-1
          w-full
          mt-4
          lg:mt-0
        "
      >
        <label
          class="
            pr-3
            font-semibold
            text-[16px] text-[#3b4559]
            md:text-right md:mb-0
            mb-2
            text-left
            md:max-w-[110px]
            w-full
          "
          >To</label
        >
        <div class="w-full">
          <input
            type="date"
            class="w-full border-[#979797]"
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
    </div>

    <!-- SECOND  -->
    <div
      class="
        bg-white
        mt-4
        mb-3
        py-[32px]
        pr-8
        pl-4
        grid grid-cols-1
        lg:grid-cols-3
        gap-2
        md:pl-[60px]
        w-full
      "
    >
      <div class="flex flex-col md:flex-row md:items-center flex-1 w-full">
        <label
          class="
            pr-3
            font-semibold
            text-[16px]
            md:max-w-[110px]
            text-[#3b4559]
            md:text-right
            text-left
            mb-2
            md:mb-0
            w-full
          "
          >Plate Type</label
        >
        <div class="w-full">
          <multi-select
            :options="plateTypes"
            class="w-[300px]"
            v-model="form.plate_type"
            :searchable="true"
            @select="changeSelectPlateType"
            placeholder="Select Plate Type"
          />
        </div>
      </div>
    </div>

    <!-- THIRD  -->
    <div class="bg-white mt-4 mb-3 md:pl-[60px] py-[32px] pl-4 pr-8">
      <div>
        <div class="flex w-full flex-col lg:flex-row gap-2 items-center mb-4">
          <label
            class="
              pr-3
              font-semibold
              text-[16px]
              md:max-w-[110px]
              text-[#3b4559]
              md:text-right
              text-left
              mb-2
              md:mb-0
              w-full
            "
            >Chromate</label
          >
          <div class="w-full">
            <multi-select
              :options="chromates"
              class="w-full"
              v-model="form.chromate"
              @select="changeSelectChromate"
              :searchable="true"
              placeholder="Select Chromate"
            />
          </div>
          <!-- INPUTS -->
          <div
            class="
              flex
              lg:gap-2
              pl-10
              md:pl-0
              overflow-x-auto
              px-2
              lg:px-0
              md:w-full
              w-[350px]
              gap-24
              pb-10
              justify-between
              items-center
            "
          >
            <div class="w-full md:w-min">
              <p class="text-center font-semibold text-[#3b4559]">Conc %</p>
              <div class="flex gap-10 lg:gap-2">
                <div>
                  <div class="w-full text-center font-bold pb-2 text-[#3b4559]">
                    >
                  </div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{
                      'border-red-400': v$.primaryPer_more_than.$error,
                    }"
                    v-model="form.primaryPer_more_than"
                  />
                  <p
                    v-for="error of v$.primaryPer_more_than.$error"
                    :key="error.$uid"
                    class="text-red-400"
                  >
                    {{ error.$message }}
                  </p>
                </div>

                <div>
                  <div class="w-full text-center font-bold pb-2 text-[#3b4559]">
                    {{ "<" }}
                  </div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{
                      'border-red-400': v$.primaryPer_less_than.$error,
                    }"
                    v-model="form.primaryPer_less_than"
                  />
                  <p
                    v-for="error of v$.primaryPer_less_than.$error"
                    :key="error.$uid"
                    class="text-red-400"
                  >
                    {{ error.$message }}
                  </p>
                </div>
              </div>
            </div>

            <div>
              <p class="text-center font-semibold text-[#3b4559]">Temp</p>
              <div class="flex gap-10 lg:gap-2">
                <div>
                  <div class="w-full text-center font-bold pb-2">></div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    v-model="form.primaryTemp_more_than"
                    :class="{
                      'border-red-400': v$.primaryTemp_more_than.$error,
                    }"
                  />
                  <p
                    v-for="error of v$.primaryTemp_more_than.$error"
                    :key="error.$uid"
                    class="text-red-400"
                  >
                    {{ error.$message }}
                  </p>
                </div>
                <div>
                  <div class="w-full text-center font-bold pb-2">
                    {{ "<" }}
                  </div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{
                      'border-red-400': v$.primaryTemp_less_than.$error,
                    }"
                    v-model="form.primaryTemp_less_than"
                  />
                  <p
                    v-for="error of v$.primaryTemp_less_than.$error"
                    :key="error.$uid"
                    class="text-red-400"
                  >
                    {{ error.$message }}
                  </p>
                </div>
              </div>
            </div>

            <div>
              <p class="text-center font-semibold text-[#3b4559]">pH</p>
              <div class="flex gap-10 lg:gap-2">
                <div>
                  <div class="w-full text-center font-bold pb-2">></div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{ 'border-red-400': v$.primaryPH_more_than.$error }"
                    v-model="form.primaryPH_more_than"
                  />
                </div>
                <div>
                  <div class="w-full text-center font-bold pb-2">
                    {{ "<" }}
                  </div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{ 'border-red-400': v$.primaryPH_less_than.$error }"
                    v-model="form.primaryPH_less_than"
                  />
                </div>
              </div>
            </div>

            <div>
              <p class="text-center font-semibold text-[#3b4559]">Diptime</p>
              <div class="flex gap-10 lg:gap-2">
                <div>
                  <div class="w-full text-center font-bold pb-2">></div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{
                      'border-red-400': v$.primaryDiptime_more_than.$error,
                    }"
                    v-model="form.primaryDiptime_more_than"
                  />
                </div>
                <div>
                  <div class="w-full text-center font-bold pb-2">
                    {{ "<" }}
                  </div>
                  <input
                    type="text"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{
                      'border-red-400': v$.primaryDiptime_less_than.$error,
                    }"
                    v-model="form.primaryDiptime_less_than"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div>
        <div class="flex w-full flex-col lg:flex-row gap-2 items-center mb-4 lg:pb-4 ">
          <label
            class="
              pr-3
              font-semibold
              text-[16px]
              md:max-w-[110px]
              text-[#3b4559]
              md:text-right
              text-left
              mb-2
              md:mb-0
              w-full
            "
            >Topcoat
          </label>
          <multi-select
            :options="topCoats"
            class="w-full"
            @select="changeSelectTopCoat"
            v-model="form.top_coat"
            :searchable="true"
            placeholder="Select TopCoat"
          />
          <!-- INPUTS -->
          <div
            class="
              flex
              lg:gap-2
              pl-10
              md:pl-0
              overflow-x-auto
              px-2
              lg:px-0
              md:w-full
              w-[350px]
              gap-24
              pb-10
              lg:pb-0
              justify-between
              items-center
            "
          >
            <div class="w-full md:w-min">
              <p class="text-center lg:hidden font-semibold text-[#3b4559]">
                Conc %
              </p>
              <div class="flex gap-10 lg:gap-2">
                <div>
                  <div
                    class="
                      w-full
                      text-center
                      font-bold
                      pb-2
                      lg:hidden
                      text-[#3b4559]
                    "
                  >
                    >
                  </div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{
                      'border-red-400': v$.topCoatPer_more_than.$error,
                    }"
                    v-model="form.topCoatPer_more_than"
                  />
                </div>
                <div>
                  <div
                    class="
                      w-full
                      text-center
                      font-bold
                      pb-2
                      lg:hidden
                      text-[#3b4559]
                    "
                  >
                    {{ "<" }}
                  </div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{
                      'border-red-400': v$.topCoatPer_less_than.$error,
                    }"
                    v-model="form.topCoatPer_less_than"
                  />
                </div>
              </div>
            </div>

            <div>
              <p class="text-center font-semibold text-[#3b4559]    lg:hidden">Temp</p>
              <div class="flex gap-10 lg:gap-2">
                <div>
                  <div class="w-full text-center font-bold pb-2    lg:hidden">></div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{
                      'border-red-400': v$.topCoatTemp_more_than.$error,
                    }"
                    v-model="form.topCoatTemp_more_than"
                  />
                </div>
                <div>
                  <div class="w-full text-center font-bold pb-2    lg:hidden">
                    {{ "<" }}
                  </div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{
                      'border-red-400': v$.topCoatTemp_less_than.$error,
                    }"
                    v-model="form.topCoatTemp_less_than"
                  />
                </div>
              </div>
            </div>

            <div>
              <p class="text-center font-semibold text-[#3b4559]     lg:hidden">pH</p>
              <div class="flex gap-10 lg:gap-2">
                <div>
                  <div class="w-full text-center font-bold pb-2    lg:hidden">></div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{ 'border-red-400': v$.topCoatPH_more_than.$error }"
                    v-model="form.topCoatPH_more_than"
                  />
                </div>
                <div>
                  <div class="w-full text-center    lg:hidden font-bold pb-2 ">
                    {{ "<" }}
                  </div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{ 'border-red-400': v$.topCoatPH_less_than.$error }"
                    v-model="form.topCoatPH_less_than"
                  />
                </div>
              </div>
            </div>
            <div>
              <p class="text-center font-semibold text-[#3b4559]  lg:hidden">Diptime</p>
              <div class="flex gap-10 lg:gap-2">
                <div>
                  <div class="w-full text-center font-bold pb-2 text-[#3b4559]    lg:hidden">
                    >
                  </div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{
                      'border-red-400': v$.topCoatDiptime_more_than.$error,
                    }"
                    v-model="form.topCoatDiptime_more_than"
                  />
                </div>
                <div>
                  <div class="w-full text-center font-bold pb-2 text-[#3b4559]    lg:hidden">
                    {{ "<" }}
                  </div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{
                      'border-red-400': v$.topCoatDiptime_less_than.$error,
                    }"
                    v-model="form.topCoatDiptime_less_than"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div>
        <div class="flex w-full flex-col lg:flex-row gap-2 items-center">
          <label
            class="
              pr-3
              font-semibold
              text-[16px]
              md:max-w-[110px]
              text-[#3b4559]
              md:text-right
              text-left
              mb-2
              md:mb-0
              w-full
            "
            >Secondary Topcoat</label
          >
          <div class="w-full">
            <multi-select
              :options="secondaryCoats"
              class="w-full"
              v-model="form.coat"
              @select="changeSelectCoat"
              :searchable="true"
              placeholder="Select Secondary Topcoat"
            />
          </div>
          <!-- INPUTS -->

          <div
            class="
              flex
              lg:gap-2
              pl-10
              md:pl-0
              overflow-x-auto
              px-2
              lg:px-0
              md:w-full
              w-[350px]
              gap-24
              pb-10
              lg:pb-0
              justify-between
              items-center
            "
          >
            <div class="w-full md:w-min">
              <p class="text-center font-semibold text-[#3b4559] lg:hidden">Conc %</p>
              <div class="flex gap-10 lg:gap-2">
                <div>
                  <div class="w-full text-center font-bold pb-2 text-[#3b4559] lg:hidden">
                    >
                  </div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{ 'border-red-400': v$.coatPer_more_than.$error }"
                    v-model="form.coatPer_more_than"
                  />
                </div>
                <div>
                  <div class="w-full text-center font-bold pb-2 text-[#3b4559] lg:hidden">
                    {{ "<" }}
                  </div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{ 'border-red-400': v$.coatPer_less_than.$error }"
                    v-model="form.coatPer_less_than"
                  />
                </div>
              </div>
            </div>

            <div>
              <p class="text-center font-semibold text-[#3b4559] lg:hidden">Temp</p>
              <div class="flex gap-10 lg:gap-2">
                <div>
                  <div class="w-full text-center font-bold pb-2 lg:hidden">></div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{ 'border-red-400': v$.coatTemp_more_than.$error }"
                    v-model="form.coatTemp_more_than"
                  />
                </div>
                <div>
                  <div class="w-full text-center font-bold pb-2 lg:hidden">
                    {{ "<" }}
                  </div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{ 'border-red-400': v$.coatTemp_less_than.$error }"
                    v-model="form.coatTemp_less_than"
                  />
                </div>
              </div>
            </div>

            <div>
              <p class="text-center font-semibold text-[#3b4559] lg:hidden">pH</p>
              <div class="flex gap-10 lg:gap-2">
                <div>
                  <div class="w-full text-center font-bold pb-2 lg:hidden">></div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{ 'border-red-400': v$.coatPH_more_than.$error }"
                    v-model="form.coatPH_more_than"
                  />
                </div>
                <div>
                  <div class="w-full text-center font-bold pb-2 lg:hidden">
                    {{ "<" }}
                  </div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{ 'border-red-400': v$.coatPH_less_than.$error }"
                    v-model="form.coatPH_less_than"
                  />
                </div>
              </div>
            </div>

            <div>
              <p class="text-center font-semibold text-[#3b4559] lg:hidden">Diptime</p>
              <div class="flex gap-10 lg:gap-2">
                <div>
                  <div class="w-full text-center font-bold pb-2 lg:hidden">></div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{
                      'border-red-400': v$.coatDiptime_more_than.$error,
                    }"
                    v-model="form.coatDiptime_more_than"
                  />
                </div>
                <div>
                  <div class="w-full text-center font-bold pb-2 text-[#3b4559] lg:hidden">
                    {{ "<" }}
                  </div>
                  <input
                    type="number"
                    class="w-[120px] lg:w-[60px] text-sm"
                    placeholder="0"
                    :class="{
                      'border-red-400': v$.coatDiptime_less_than.$error,
                    }"
                    v-model="form.coatDiptime_less_than"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="ml-2 mr-8 mt-4 mb-3 p-3">
      <div class="flex w-full flex-col lg:flex-row gap-2">
        <div class="w-full"></div>
        <div class="flex gap-2">
          <div>
            <!-- <button class="bg-primary rounded w-60 py-2.5 px-1.8 text-white hover:bg-primary-600">Run Summary Report</button> -->
          </div>
          <div>
            <button
              v-if="!loading"
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
            <button
              v-if="loading"
              class="
                bg-primary
                rounded
                w-60
                py-2.5
                px-1.8
                text-white
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
      </div>
    </div>
  </form>
</template>

<script>
import Multiselect from "@vueform/multiselect";
import useFormReport from "../composables/useFormReport";
import { ref } from "vue";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import { Navigation, Pagination } from "swiper";
import { Swiper, SwiperSlide } from "swiper/vue";

export default {
  props: ["customers", "topCoats", "chromates", "plateTypes", "secondaryCoats"],
  components: {
    multiSelect: Multiselect,
    swiper: Swiper,
    swiperSlide: SwiperSlide,
  },
  setup() {
    const { form, v$, submitForm, loading } = useFormReport({
      customer: 0,
      customerName: "All Customers",
      chromateName: "All",
      top_coatName: "All",
      coatName: "All",
      plate_typeName: "All",
      start_date: "",
      endDate: "",
      plate_type: 0,
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
      loading,
      changeSelectCustomer: (value, data) => (form.customerName = data.label),
      changeSelectChromate: (value, data) => (form.chromateName = data.label),
      changeSelectTopCoat: (value, data) => (form.top_coatName = data.label),
      changeSelectCoat: (value, data) => (form.coatName = data.label),
      changeSelectPlateType: (value, data) =>
        (form.plate_typeName = data.label),
    };
  },
};
</script>

<style>
</style>