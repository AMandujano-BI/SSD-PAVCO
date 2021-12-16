<template>
  <table id="tableCompanies" class="display" style="width: 100%; height: 100%">
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

          <tr v-for="company in companiesTable" :key="company.id">
            <td class="text-center">{{company.id}}</td>
            <!-- <td class="text-center">{{company.id}}</td> -->
            <td class="text-center">{{'distributor'}}</td>
            <td class="text-center">{{company.address}}</td>
            <td class="text-center">{{company.city}}</td>
            <td class="text-center">{{company.state}}</td>
            <td class="text-center">{{company.zip}}</td>
            <td class="text-center">{{'country'}}</td>
            <td class="text-center">{{company.phone}}</td>
            <td class="text-center">{{company.fax}}</td>
            <td class="text-center">{{company.customer ==0?'Customer':'Distributor'}}</td>
            <td class="text-center">edit</td>
            <td class="text-center">delete</td>
          </tr>
    </tbody>
  </table>
</template>

<script>
import axios from "axios";
import { ref,nextTick } from "vue";
const $ = require("jquery");

export default {
  setup() {
    const companiesTable = ref([]);

    const getCompanies = async () => {
      await axios.get("companies");
    };
    const gettingData = async (type = 3) => {
      try {
        const res = await axios.get(`/company/getCompanies/${type}`);
        console.log(res.data)
        companiesTable.value = res.data;
        // $("#tableCompanies").DataTable().destroy();
        await generateDataTable();
      } catch (e) {
        console.log(e);
      }
    };
    const generateDataTable = () => {
      nextTick(() => {
        $("#tableCompanies").DataTable({
          scrollY: 350,
          ordering: true,
          bLengthChange: false,
          pageLength: 5,
        });
      });
    };

    gettingData();
    return {
      getCompanies,
      companiesTable,
      
    };
  },
};
</script>

<style>
</style>