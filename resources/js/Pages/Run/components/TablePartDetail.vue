<template>
  <div class="container mx-auto p-5 relative">
    <p class="text-xl font-bold text-center">Run results</p>
    <p>Enter results for each part in this run</p>
    <hr />
    <div class="shadow-lg rounded-lg p-5">
      <h3 class="text-2xl font-bold">RUN INFORMATION</h3>
      <div></div>
      <ul v-if="runDetail">
        <li><strong>Run: </strong> {{ runDetail.id }}</li>
        <!-- <li><strong>Customer: </strong> {{ runDetail.user_id }}</li> -->
        <li><strong>Customer: </strong> {{ runDetail.company?.name }}</li>
        <li><strong>Start Date: </strong> {{ runDetail.start_date }}</li>
        <li><strong>Description: </strong> {{ runDetail.description }}</li>
      </ul>
    </div>
    <hr />

    <div class="rounded-lg shadow-lg p-5">
      <h3 class="text-2xl font-bold">PARTS DATA</h3>
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
          <tr v-for="part in runDetail.parts" :key="part.id">
            <td>{{ part.description }}</td>
            <td>{{ part.plate_type.name }}</td>
            <td>{{ part.chromate.name + " - " + part.primaryPer + " % " }}</td>
            <td>{{ part.top_coat.name + " - " + part.topCoatPer + " % " }}</td>
            <td>{{ part.coat.name + " - " + part.coatPer + " % " }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { reactive, nextTick, ref } from "vue";

const $ = require("jquery");
export default {
  props: ["id"],
  setup(props) {
    const { id } = props;
    const runDetail = ref({});
    const gettingData = async () => {
      try {
        const res = await axios.get(`/run/${id}`);
        runDetail.value = res.data;
        runDetail.value.start_date = runDetail.value.start_date.slice(0, 10);
        $("#activeRunsDetail").DataTable().destroy();
        await generateDataTableDetail();
      } catch (e) {
        console.log(e);
      }
    };
    const generateDataTableDetail = () => {
      nextTick(() => {
        $("#activeRunsDetail").DataTable({
          scrollY: 300,
          ordering: true,
          bLengthChange: false,
          pageLength: 5,
          language: {
            paginate: {
              next: `<svg class="arrow_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="15" height="14" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><g transform="rotate(270 10 10)"><path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="white"/></g></svg>`, // or '→'
              previous: `<svg class="arrow_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="15" height="14" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><g transform="rotate(90 10 10)"><path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="white"/></g></svg>`, // or '←'
            },
             info: "Showing results _START_ to _END_ from _TOTAL_",
          },
        });
      });
    };
    gettingData();
    return {
      generateDataTableDetail,
      gettingData,
      runDetail,
    };
  },
};
</script>

<style scoped>
.no-sort::after {
  display: none !important;
}
.no-sort {
  pointer-events: none !important;
  cursor: default !important;
  background-image: none !important;
}
</style>