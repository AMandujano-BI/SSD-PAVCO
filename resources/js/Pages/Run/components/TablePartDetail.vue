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
        <li><strong>Customer: </strong> {{ "Testing" }}</li>
        <li><strong>Start Date: </strong> {{ runDetail.startDate }}</li>
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
            <!-- <td class="text-center">
              <button>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5"
                  viewBox="0 0 20 20"
                  fill="#DA9C14"
                >
                  <path
                    fill-rule="evenodd"
                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                    clip-rule="evenodd"
                  />
                </svg>
              </button>
            </td> -->
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
        runDetail.value.startDate = runDetail.value.startDate.slice(0, 10);
        // console.log(runDetail);
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
          // bInfo: false,
          pageLength: 5,
          // paging: false,
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
.no-sort::after { display: none!important; }
.no-sort { pointer-events: none!important; cursor: default!important; background-image: none !important }
</style>