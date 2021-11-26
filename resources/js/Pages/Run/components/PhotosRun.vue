<template>
    <modal :show="isModalPhotos">
      <div class="container mx-auto p-5">
        <button
          @click="closePhotosModal"
          class="bg-red-600 text-white p-2 rounded-md"
        >
          X
        </button>
        <p class="text-xl font-bold text-center">Pavco SSD Photo Viewer</p>
        
        <div class="mt-5" v-if="currentPhotos[0]">
          <swiper
            :modules="modules"
            :slides-per-view="1"
            :space-between="50"
            navigation
            :pagination="{ clickable: true }"
            @swiper="onSwiper"
            @slideChange="onSlideChange"
          >
            <swiper-slide v-for="photo in currentPhotos" :key="photo.id">
              <div>
                <p>Filename: {{ photo.name }}</p>
                <ul>
                  <li><strong>Name: </strong>{{ photo.name }}</li>
                  <li><strong>Date added: </strong>{{ photo.created_at }}</li>
                  <li><strong>Hours: </strong>{{ photo.hours }}</li>
                  <li><strong>Description: </strong>{{ photo.description }}</li>
                </ul>
                <img :src="photo.image" :alt="photo.name" />
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
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

export default {
    props: {
        photos: {
            type: Object,
            default: {}
        }, 
        isModalPhotos: {
            type: Boolean,
            default: false
        }
    },
    components: {
        swiper: Swiper,
        swiperSlide: SwiperSlide,
        modal: Modal,
    },
    emits: ['closeModal','photoEdited'],
    setup(props, {emit}) {
        console.log(props.photos)
        const {photos} = props
        let currentPhotos = ref(photos)
        console.log(currentPhotos);
        const onSwiper = (swiper) => {
            // console.log(swiper);
        };
        const onSlideChange = () => {
            // console.log('slide change');
        };

        const closePhotosModal = () => emit("closeModal");

        const photoEdited = () => emit("photoAdded");

        return {
            closePhotosModal,
            onSwiper,
            onSlideChange,
            closePhotosModal,
            photoEdited,
            currentPhotos,
            modules: [Pagination, Navigation],
        }
    },
    watch: {
      photos() {
        this.currentPhotos = this.photos
      },
    },
}
</script>

<style>

</style>