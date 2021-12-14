<template>
  <div class="p-5">
    <form @submit.prevent="saveImage">
      <h1 class="text-2xl text-center font-bold">Take a Picture</h1>

      <!-- <input type="file" accept="image/*" capture="camera" /> -->
      <input
        type="file"
        accept="image/*"
        multiple
        id="image"
        @change="fileChange"
      />
      <div v-if="!url" class="w-full h-64 flex items-center justify-center">No Image</div>
      <img
        v-if="url"
        :src="url"
        alt="image"
        class="w-full h-64 my-5 object-cover"
      />
      <div>
        <textarea
          placeholder="Description"
          v-model="form.description"
          cols="30"
          class="w-full"
        ></textarea>
      </div>
        <div v-if="!loading" class="w-full">
      <button
        @click="saveImage"
        class="
          bg-primary
          w-full
          rounded-sm
          hover:bg-primary-600
          text-white
          font-bold
          p-5
          block
        "
      >
        Save Image
      </button>
        </div>
      <div v-if="loading" class="w-full">
        <button
          class="
            bg-primary
            rounded
            w-full
            py-5
            text-white
            px-3
            mt-2
            hover:bg-primary-600
          "
          disabled
        >
          <div
            className="animate-spin rounded-full h-6 w-6 border-b-2 border-t-2 border-white inline-block"
          ></div>
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import { reactive, ref } from "vue";
import axios from "axios";
import useHelper from "@/composables/useHelper";
export default {
  emits: ["closeModal"],
  props: ["run_id"],
  setup(props, { emit }) {
    const { run_id } = props;
    const form = reactive({
      description: "",
    });
    const loading = ref(false);
    const url = ref(null);
    const image = ref(null);
    const { makeToast } = useHelper();

    const fileChange = (e) => {
      const file = e.target.files[0];
      console.log(file);
      image.value = file;
      url.value = URL.createObjectURL(file);
    };
    const selectImage = () => {
      document.getElementById("image").click();
    };
    const saveImage = async () => {
      loading.value = true;
      const formData = new FormData();
      formData.append("image", image.value);
      formData.append("run", run_id);
      formData.append("description", form.description);
      formData.append("hours", 1);
      formData.append("report", 1);
      const res = await axios.post(`/photo`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });
      const { ok, message, value } = res.data;
      loading.value = false;
      console.log(res.data);
      if (ok) {
        makeToast(message);
        emit("closeModal");
      } else {
        makeToast(message, "error");
      }
    };
    return {
      url,
      image,
      selectImage,
      fileChange,
      saveImage,
      form,
      loading,
    };
  },
};
</script>
