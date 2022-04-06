<template>
    <modal :show="isModalPhotos" @close="closePhotosModal" >
    <div class="container mx-auto p-5 relative bg-[#ebf2fd]" >
      <button @click="closePhotosModal" class="absolute right-5">X</button>
    
      <p class="text-xl font-bold text-center">Pavco SSD Photo Viewer</p>

      <div class="mt-5">
        <swiper
          :modules="modules"
          :slides-per-view="1"
          :space-between="50"
          :pagination="{ clickable: true }"
        >
          <swiper-slide >
            <div>
              <img
                :src="currentPhotos.image"
                :alt="currentPhotos.image"
                class="object-cover object-left-top h-[400px] max-h-[400px] w-full"
              />
              <div>
                <ul class="flex flex-col gap-2 md:flex-row justify-between px-3 pt-3">
                  <li class="text-[#7e7f82]"><strong class="text-[#1e385e]">Date added: </strong>{{ currentPhotos.created_at.slice(0,10) }}</li>
                  <li class="text-[#7e7f82]"><strong class="text-[#1e385e]">Hours: </strong>{{ currentPhotos.hours }}</li>
                </ul>
              </div>
              <div class="px-3 mt-1 md:mt-5 text-[#7e7f82]">
                <strong class="text-[#1e385e]">Description: </strong>{{ currentPhotos.description }}
              </div>
            </div>
          </swiper-slide>
        </swiper>
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

    const closePhotosModal = () => emit("closeModal");
    const photoEdited = () => emit("photoAdded");

    return {
      closePhotosModal,
      closePhotosModal,
      photoEdited,
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