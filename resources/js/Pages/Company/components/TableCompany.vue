<template>
  <table>
    <thead>
      <tr>
        <th>Company</th>
        <th>Distributor</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Zip</th>
        <th>Country</th>
        <th>Phone</th>
        <th>Fax</th>
        <th>Type</th>
        <th>Edit</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
      <tr></tr>
    </tbody>
  </table>
</template>

<script>
import axios from "axios";
import { ref } from "vue";
export default {
  setup() {
    const companiesTable = ref([]);

    const getCompanies = async () => {
      await axios.get("companies");
    };

    return {
      getCompanies,
      companiesTable,
    };
  },
};
</script>

<style>
</style>