<template>
<div class="p-5">

  <h1 class="text-2xl text-center font-bold">Take a Picture</h1>

  <!-- <input type="file" accept="image/*" capture="camera" /> -->
  <input type="file" accept="image/*" multiple  id="image" @change="fileChange"/>
  <img v-if="url" :src="url" alt="image" class="w-full h-64 my-5 object-cover">
  <button @click="saveImage" class="bg-primary w-full rounded-sm hover:bg-primary-600 text-white font-bold p-5 block">Save Image</button>

</div>
</template>

<script>
import { ref } from "vue";
import axios from 'axios';
export default {
  props:['run_id'],
  setup(props) {
    const {run_id} = props
    console.log(run_id)
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
     const formData = new FormData() 
     formData.append('image',image.value)
     formData.append('run',run_id)
      const res = await  axios.post(`/photo`,formData,{
        headers:{
          'Content-Type':'multipart/form-data'
        }
      } );
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
