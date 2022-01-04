<template>
  <div class="p-5">
    <form @submit.prevent="saveImage">
      <h1 class="text-2xl text-center font-bold">Take a Picture</h1>
      <div
        class="
          bg-primary
          rounded-full
          w-10
          h-10
          flex
          justify-center
          items-center
          cursor-pointer
        "
        @click="selectImage"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink"
          aria-hidden="true"
          role="img"
          width="24"
          height="24"
          preserveAspectRatio="xMidYMid meet"
          viewBox="0 0 1024 1024"
        >
          <path
            d="M864 260H728l-32.4-90.8a32.07 32.07 0 0 0-30.2-21.2H358.6c-13.5 0-25.6 8.5-30.1 21.2L296 260H160c-44.2 0-80 35.8-80 80v456c0 44.2 35.8 80 80 80h704c44.2 0 80-35.8 80-80V340c0-44.2-35.8-80-80-80zM512 716c-88.4 0-160-71.6-160-160s71.6-160 160-160s160 71.6 160 160s-71.6 160-160 160zm-96-160a96 96 0 1 0 192 0a96 96 0 1 0-192 0z"
            fill="#f8f8f8"
          />
        </svg>
      </div>
      <input
        type="file"
        class="hidden"
        accept="image/*"
        multiple
        id="image"
        @change="fileChange"
      />
      <div
        v-if="!url"
        class="w-full h-64 flex items-center justify-center"
        :class="{ 'text-red-500 font-bold transition ease-linear text-lg  delay-75 duration-75': v$.image.$error }"
      >
        Select an Image
      
      </div>
      <img
        v-if="url"
        :src="url"
        alt="image"
        class="w-full h-64 my-5 object-cover"
      />
      <div>
        <label>Name</label>
        <input
          type="text"
          class="w-full"
          v-model="form.name"
          :class="{ 'border-red-500': v$.name.$error }"
        />
        <p
          v-for="error of v$.name.$errors"
          :key="error.$uid"
          class="text-red-400"
        >
          {{ error.$message }}
        </p>
      </div>
      <div>
        <label for="">Date Added</label>
        <input
          type="date"
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
      <div>
        <label class="w-full pb-2 block">Report?</label>
        <input type="radio" value="1" v-model="form.report" id="yes" />
        <label for="yes"> Yes </label>
        <input type="radio" value="0" v-model="form.report" id="no" /><label
          for="no"
        >
          No</label
        >
      </div>
      <div>
        <label>Description </label>
        <textarea
          v-model="form.description"
          cols="30"
          :class="{ 'border-red-500': v$.description.$error }"
          class="w-full"
        ></textarea>
        <p
          v-for="error of v$.description.$errors"
          :key="error.$uid"
          class="text-red-400"
        >
          {{ error.$message }}
        </p>
      </div>
      <div
        v-if="!loading"
        class="w-full flex flex-col md:flex-row justify-between gap-4"
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
import { required } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
export default {
  emits: ["closeModal", "generateDataTable"],
  props: ["run_id", "photosTable"],
  setup(props, { emit }) {
    const { run_id, photosTable } = props;
    const photos = ref(photosTable);
    const form = reactive({
      description: "",
      name: "",
      report: 1,
      // hours: "",
      // startDate: new Date().toISOString().slice(0, 10),
      hours: new Date().toISOString().slice(0, 10),
      image: null,
    });
    const loading = ref(false);
    const url = ref(null);
    const image = ref(null);
    const { makeToast } = useHelper();
    const rules = {
      description: { required },
      hours: { required },
      name: { required },
      image: { required },
    };
    const v$ = useVuelidate(rules, form);

    const fileChange = (e) => {
      const file = e.target.files[0];
      image.value = file;
      form.image = file;
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
        const formData = new FormData();
        formData.append("image", form.image);
        formData.append("run", run_id);
        formData.append("description", form.description);
        formData.append("name", form.name);
        formData.append("hours", form.hours);
        formData.append("report", form.report);
        const res = await axios.post(`/photo`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });
        const { ok, message, value } = res.data;
        loading.value = false;
        if (ok) {
          makeToast(message);
          photos.value.push(value);
          emit("closeModal");
        } else {
          makeToast(message, "error");
        }
      } catch (e) {
        loading.value = false;
        makeToast(e, "error");
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
