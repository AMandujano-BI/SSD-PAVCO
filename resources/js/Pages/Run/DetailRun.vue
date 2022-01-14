<template>
  <app-layout title="Detail Run">
    <div class="container mx-auto pt-14">
      <h1 class="text-center text-2xl p-5 font-bold text-[#3b4559]">
        Run # {{ run.id }}
      </h1>

            <table id="activeRunsDetail" class="display" style="width: 100%">
        <thead>
          <tr>
            <th>Part description</th>
            <th>Plate Type</th>
            <th>Chromate</th>
            <th>Topcoat</th>
            <th>Secondary Topcoat</th>

            <!-- <th class="no-sort">Notes</th> -->
          </tr>
        </thead>
        <tbody>
          <tr v-for="part in run.parts" :key="part.id">
            <td>{{ part.description }}</td>
            <td>{{ part.plate_type.name }}</td>
            <td>{{ part.chromate.name + " - " + part.primaryPer + " % " }}</td>
            <td>{{ part.top_coat.name + " - " + part.topCoatPer + " % " }}</td>
            <td>{{ part.coat.name + " - " + part.coatPer + " % " }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
export default {
  components: {
    AppLayout: AppLayout,
  },
  props: ["run"],
  setup() {},
};
</script>

<style>
</style>