<template>
  <table id="partsTable" class="display" style="width: 100%">
    <thead>
      <tr>
        <th>Part Description</th>
        <th>Plate Type</th>
        <th>Chromate</th>
        <th>Topcoat</th>
        <th>Secondary TopCoat</th>
        <th>Edit</th>
        <th>Delete</th>
        <th>Notes</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="part in parts" :key="part.id">
        <td>{{ part.description }}</td>
        <td>{{ part.id }}</td>
        <td>{{ part.id }}</td>
        <td>{{ part.id }}</td>
        <td>{{ part.id }}</td>
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
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
              />
            </svg>
          </button>
        </td>
        <td>
          <button>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="#E71F2A"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
              />
            </svg>
          </button>
        </td>
        <td>
          <button>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path
                fill-rule="evenodd"
                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                clip-rule="evenodd"
              />
            </svg>
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
const $ = require("jquery");
import dt from "datatables.net";
export default {
  props: ["parts"],
  setup() {
    const generateDataTable = () => {
      $("#partsTable").DataTable();
      // this.$nextTick(() => {
      // });
    };
    const getData = async () => {
      await axios
        .get("/part/8")
        .then((resp) => {
          console.log(resp);
          this.runs = resp.data.data;
          console.log(this.runs);
          this.generateDataTable();
        })
        .catch((err) => console.log(err));
    };

    //Initial get
    // getData();
    generateDataTable();

    return {
      generateDataTable,
      $,
      dt,
    };
  },
};
</script>
