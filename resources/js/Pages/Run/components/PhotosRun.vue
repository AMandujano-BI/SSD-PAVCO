<template>
  
    
      <p class="text-xl font-bold text-center">Pavco SSD Photo Viewer</p>

      <div class="mt-5" v-if="runDetail[0] && !isLoading">
        <swiper
          :modules="modules"
          :slides-per-view="1"
          :space-between="50"
          navigation
          :pagination="{ clickable: true }"
        >
          <swiper-slide v-for="photo in runDetail" :key="photo.id">
            <div>
              <img
                :src="photo.image"
                :alt="photo.name"
                class="object-contain h-90 w-full"
              />
              <div>
                <ul class="flex flex-row justify-between px-3 pt-3">
                  <li><strong>Name: </strong>{{ photo.name }}</li>
                  <li><strong>Date added: </strong>{{ photo.created_at.slice(0,10) }}</li>
                  <li><strong>Hours: </strong>{{ calculateHours(photo.isEdit, photo.last_edit, photo.created_at, photo.hours) }}</li>
                </ul>
              </div>
              <div class="px-3">
                <strong>Description: </strong>{{ photo.description }}
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
</template>

<script>
import { ref } from "vue";
import { Navigation, Pagination } from "swiper";
import { Swiper, SwiperSlide } from "swiper/vue";
import Modal from "../../../Jetstream/Modal.vue";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import axios from "axios";

export default {
  props: {
    id: {
      type: String,
    },
  },
  components: {
    swiper: Swiper,
    swiperSlide: SwiperSlide,
    modal: Modal,
  },
  setup(props) {
    const { id } = props;
    const runDetail = ref({});
    const isLoading = ref(true);

    const gettingData = async () => {
      try {
        const res = await axios.get(`/run/${id}`);
        console.log(res);
        runDetail.value = res.data.photos;
        isLoading.value = false;
        console.log(isLoading);
      } catch (e) {
        isLoading.value = false;
      }
    }

    const calculateHours = (edit, lastDate, created_date, hours) => {
        if (edit) {
          const hoursEdited = Math.abs(new Date() - new Date(lastDate)) / 36e5;
          const hoursRounded = hoursEdited | 0;
          return Number(hours) + hoursRounded;
        } else {
          const hoursDiff = Math.abs(new Date() - new Date(created_date)) / 36e5;
          return hoursDiff | 0;
        }
    }

    gettingData();

    // const closePhotosModal = () => emit("closeModal");

    // const photoEdited = () => emit("photoAdded");

    return {
      gettingData,
      calculateHours,
      runDetail,
      isLoading,
      modules: [Pagination, Navigation],
    };
  },
  watch: {
    isLoading: function(val){
      console.log(val);
      this.isLoading = val
    }
  }
};
</script>

<style>
</style>