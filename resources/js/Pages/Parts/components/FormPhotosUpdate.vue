
<template>
  <div class="p-5">
    <form @submit.prevent="saveImage">
      <h1 class="text-2xl text-center font-bold text-[#3b4559]">Update Photo</h1>
      <input
        type="file"
        class="hidden"
        accept="image/*"
        multiple
        id="image"
        @change="fileChange"
      />
      <div v-if="!url" class="w-full h-64 flex items-center justify-center">
        Select an Image
      </div>
      <img
        v-if="url"
        :src="url"
        alt="image"
        class="w-full h-64 my-5 object-cover"
      />

      <div class="mb-2">
        <label class="w-full pb-2 block font-semibold text-[#3b4559]">Report?</label>
        <input type="radio" value="1" v-model="form.report" id="yes" />
        <label for="yes">Yes </label>
        <input type="radio" value="0" v-model="form.report" id="no" /><label
          for="no"
        >
          No</label
        >
      </div>
      <div class="mb-2">
        <label class=" text-[#3b4559] font-semibold">Description </label>
        <textarea
          v-model="form.description"
          cols="30"
          class="w-full"
        ></textarea>
      </div>
      <div>
        <label class=" text-[#3b4559] font-semibold">Hours</label>
        <input
          type="number"
          class="w-full"
          v-model="form.hours"
          :class="{ 'border-red-500': v$.hours.$error }"
        />
        <p
          v-for="error of v$.hours.$errors"
          :key="error.$uid"
          class="text-red-400"
        >
          {{ error.$message }}
        </p>
      </div>
      <div
        v-if="!loading"
        class="w-full flex flex-col md:flex-row md:justify-between gap-4"
      >
        <button
          type="button"
          class="
            bg-red-600
            hover:bg-red-800
            rounded
            w-full
            py-5
            text-white
            px-3
            mt-2
          "
          @click="closeModal"
        >
          Cancel
        </button>
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
            mt-2
          "
        >
          Save
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
import { required, minValue } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
export default {
  emits: ["closeModal", "generateDataTable"],
  props: ["run_id", "photosTable", "photoItem"],
  setup(props, { emit }) {
    const { photosTable, photoItem } = props;
    const photos = ref(photosTable); 

    const form = reactive({
      id: photoItem.id,
      description: photoItem.description,
      report: photoItem.report,
      hours: photoItem.hours,
      image: photoItem.image,
    });
    const loading = ref(false);
    const url = ref(photoItem.image);
    const image = ref(photoItem.image);
    const { makeToast } = useHelper();
    const rules = {
      hours: { required, minValue: minValue(0) },
    };
    const v$ = useVuelidate(rules, form);

    const fileChange = (e) => {
      const file = e.target.files[0];
      image.value = file;
      url.value = URL.createObjectURL(file);
    };
    const selectImage = () => {
      document.getElementById("image").click();
    };
    const saveImage = async () => {
      try {
        
        const isFormCorrect = await v$.value.$validate();
        if (!isFormCorrect) return;
        loading.value = true;
        // Prepare Data
        const res = await axios.put(`/photo/${form.id}`, form);
        
        const { ok, message, value } = res.data;
        
        loading.value = false;
        if (ok) {
          
          makeToast(message);
          const index = photos.value.findIndex((item) => item.id == form.id);
                    
          photos.value[index] = {
            ...photos.value[index],
            description: form.description,
            report: form.report,
            hours: form.hours,
          };
          // emit("generateDataTable");
          emit("closeModal");
        } else {
          loading.value = false;
          makeToast(message, "error");
        }
      } catch (e) {
        loading.value = false;
        makeToast("Error", "error");
      }
    };

    return {
      url,
      image,
      selectImage,
      fileChange,
      saveImage,
      form,
      v$,
      loading,
      closeModal: () => emit("closeModal"),
    };
  },
  watch: {
    run() {
      this.photos = this.photosTable;
    },
  },
};
</script>
