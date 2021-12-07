<template>
  <h1>Take a picture</h1>

  <!-- <input type="file" accept="image/*" capture="camera" /> -->
  <input type="file" accept="image/*" multiple  id="image" @change="fileChange"/>
  <img v-if="url" :src="url" alt="image" class="w-64 h-64">
  <button @click="saveImage" class="bg-red-500 p-5 block">select image</button>
</template>

<script>
import { ref } from "vue";
import axios from 'axios';
export default {
  setup() {
    const url = ref(null);
    const image = ref(null)

    const fileChange = (e) => {
        const file = e.target.files[0]
        console.log(file)
        image.value =file
        url.value =URL.createObjectURL(file)
    };
    const selectImage = () => {
      document.getElementById("image").click();
    };
    const saveImage = async() =>{
      
      const res = await  axios.post(`/photo`,url.value );
      console.log(res.data)
    }
    return {
      url,
      image,
      selectImage,
      fileChange,
      saveImage
    };
  },
};
</script>
