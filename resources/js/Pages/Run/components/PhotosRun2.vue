<template>
    <modal :show="isModalPhotos" @close="closePhotosModal">
    <div class="container mx-auto p-5 relative">
      <button @click="closePhotosModal" class="absolute right-5">X</button>
    
      <p class="text-xl font-bold text-center">Pavco SSD Photo Viewer</p>

      <div class="mt-5" v-if="currentPhotos[0]">
        <swiper
          :modules="modules"
          :slides-per-view="1"
          :space-between="50"
          navigation
          :pagination="{ clickable: true }"
        >
          <swiper-slide v-for="photo in currentPhotos" :key="photo.id">
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
      <div v-else>
        <p class="text-center my-20">There is no images</p>
      </div>
    </div>
    </modal>
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
    photos: {
      type: Object,
      default: {},
    },
    isModalPhotos: {
      type: Boolean,
      default: false,
    },
  },
  components: {
    swiper: Swiper,
    swiperSlide: SwiperSlide,
    modal: Modal,
  },
  emits: ["closeModal", "photoEdited"],
  setup(props, { emit }) {
    const { photos } = props;
    let currentPhotos = ref(photos);

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

    const closePhotosModal = () => emit("closeModal");
    const photoEdited = () => emit("photoAdded");

    return {
      closePhotosModal,
      closePhotosModal,
      photoEdited,
      calculateHours,
      currentPhotos,
      modules: [Pagination, Navigation],
    };
  },
  watch: {
    photos() {
      this.currentPhotos = this.photos;
    },
  },
};
</script>

<style>
</style>