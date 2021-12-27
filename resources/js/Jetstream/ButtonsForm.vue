<template>
  <div class="flex flex-col md:flex-row justify-between w-full gap-4">
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
      @click="closeModalForm"
    >
      Cancel
    </button>
    <div v-if="!loading" class="w-full">
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
  </div>
</template>

<script>
export default {
    emits:["closeModal"],
    props:["loading"],
    setup(props,{emit}){

        return{
            closeModalForm: ()=>emit('closeModal')
        }

    }
};
</script>

<style>
</style>