<template>
  <app-layout title="Viewer Photos">
    <div class="container mx-auto pt-5">
      <div class="mt-7" v-if="runDetail[0] && !isLoading">
        <swiper
          :modules="modules"
          :slides-per-view="1"
          :space-between="50"
          :pagination="{ clickable: true }"
        >
          <swiper-slide v-for="photo in runDetail" :key="photo.id">
            <div>
              <div class="h-[550px]">
                <img
                  :src="photo.image"
                  :alt="photo.name"
                  class="object-contain  h-full w-full"
                />
              </div>
              <div>
                <ul class="flex flex-row justify-between px-3 pt-12">
                  <li class="text-[#7e7f82]">
                    <strong class="text-[#1e385e]">Name: </strong
                    >{{ photo.name }}
                  </li>
                  <li class="text-[#7e7f82]">
                    <strong class="text-[#1e385e]">Date added: </strong
                    >{{ photo.created_at.slice(0, 10) }}
                  </li>
                  <li class="text-[#7e7f82]">
                    <strong class="text-[#1e385e]">Hours: </strong
                    >{{
                      calculateHours(
                        photo.isEdit,
                        photo.last_edit,
                        photo.created_at,
                        photo.hours
                      )
                    }}
                  </li>
                </ul>
              </div>

              <div class="px-3 mt-1 md:mt-5 text-[#7e7f82]">
                <strong class="text-[#1e385e]">Description: </strong
                >{{ photo.description }}
              </div>
            </div>
          </swiper-slide>
        </swiper>
      </div>
      <div v-if="!runDetail[0] && !isLoading">
        <p class="text-center my-20">There is no images</p>
      </div>
      <div v-if="isLoading">
        <p class="text-center my-20">Cargando...</p>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import PhotosRun from "./components/PhotosRun.vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { ref } from "vue";
import { Navigation, Pagination } from "swiper";
export default {
  props: ["id"],
  components: {
    AppLayout: AppLayout,
    swiper: Swiper,
    swiperSlide: SwiperSlide,
    PhotosRun,
  },
  setup(props) {
    const { id } = props;
    const isLoading = ref(true);
    const runDetail = ref({});
    const gettingData = async () => {
      try {
        const res = await axios.get(`/run/${id}`);
        runDetail.value = res.data.photos;
        isLoading.value = false;
      } catch (e) {
        isLoading.value = false;
      }
    };
    const calculateHours = (edit, lastDate, created_date, hours) => {
      if (edit) {
        const hoursEdited = Math.abs(new Date() - new Date(lastDate)) / 36e5;
        const hoursRounded = hoursEdited | 0;
        return Number(hours) + hoursRounded;
      } else {
        const hoursDiff = Math.abs(new Date() - new Date(created_date)) / 36e5;
        return hoursDiff | 0;
      }
    };
    gettingData();
    return {
      gettingData,
      calculateHours,
      runDetail,
      isLoading,
      modules: [Pagination, Navigation],
    };
  },
};
</script>

<style>
</style>