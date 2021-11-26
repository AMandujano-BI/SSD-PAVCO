<template>
  <table id="photosTable" class="display" style="width: 100%">
    <thead>
      <tr>
        <th>Name</th>
        <th>Date Added</th>
        <th>Hours</th>
        <th>Description</th>
        <th>View</th>
        <th>Report</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="photo in photos" :key="photo.id">
        <td>{{ photo.name }}</td>
        <td>{{ photo.created_at }}</td>
        <td>{{ photo.hours }}</td>
        <td>{{ photo.description }}</td>
        <td>
          <!-- <button @click="showPhotos"> -->
          <button >
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
                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
              />
            </svg>
          </button>
        </td>
        <td>Report</td>
        <td>
          <!-- <button @click="openModalDelete(part.id)"> -->
          <button >
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
      </tr>
    </tbody>
  </table>
</template>

<script>
const $ = require("jquery");
import dt from "datatables.net";
import { nextTick } from "vue";

export default {
  props: ["photos"],
  setup(props) {
    console.log(props.photos);

    const generateDataTable = () => {
      nextTick(() => {
        $("#photosTable").DataTable()
      });
    };

    generateDataTable()
    return {
      generateDataTable,
    };
  },
};
</script>