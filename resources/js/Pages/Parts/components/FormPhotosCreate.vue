<template>
  <h1>Take a picture</h1>

  <!-- <input type="file" accept="image/*" capture="camera" /> -->
  <input type="file" accept="image/*" multiple  id="image" @change="fileChange"/>
  <img v-if="url" :src="url" alt="image" class="w-64 h-64">
  <!-- <button @click="selectImage">select image</button> -->
</template>

<script>
import { ref } from "vue";
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
    return {
      url,
      image,
      selectImage,
      fileChange
    };
  },
};
</script>
