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
            <th>White Salts</th>
            <th>Red Rust</th>
            <th>Notes</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="part in runDetail.parts" :key="part.id">
            <td>{{ part.description }}</td>
            <td>{{ part.plate_type.name }}</td>
            <td>{{ part.chromate.name + " - " + part.primaryPer + " % " }}</td>
            <td>{{ part.top_coat.name + " - " + part.topCoatPer + " % " }}</td>
            <td>{{ part.coat.name + " - " + part.coatPer + " % " }}</td>
            <td>
              <input type="checkbox" value="false" />
            </td>
            <td>
              <input type="checkbox" value="false" />
            </td>
            <td>
              <button>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-6 w-6"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                  />
                </svg>
              </button>
            </td>
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
        console.log(runDetail);
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