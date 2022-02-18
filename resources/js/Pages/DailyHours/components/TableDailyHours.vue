<template>
    <input type="date" class="w-full">
     <div class="rounded-lg bg-white p-5">
      <table
        id="activeRuns"
        class="display nowrap responsive"
        style="width: 100%; height: 100%"
      >
        <thead  v-if="$page.props.auth.rols[0].id ==1">
          <tr>
            <th data-priority="2">StartDate</th>
            <th data-priority="1">Run #</th>
            <th data-priority="1" class="max-w-[150px]">Customer</th>
            <th>Method</th>
            <th data-priority="2">Status</th>
            <th>Hrs</th>
            <th class="no-sort">Photos</th>
            <th class="no-sort">Results</th>
            <th class="no-sort">Edit</th>
            <th class="no-sort">Delete</th>
            <th class="no-sort">Close</th>
            <th class="no-sort">Re-Open</th>
            <th class="no-sort">Reports</th>
            <th class="no-sort">Email</th>
          </tr>
        </thead>
          <thead  v-if="$page.props.auth.rols[0].id !=1"> 
          <tr>
            <th data-priority="2">StartDate</th>
            <th data-priority="1">Run #</th>
            <th data-priority="1" class="max-w-[150px]">Customer</th>
            <th>Method</th>
            <th data-priority="2">Status</th>
            <th>Hrs</th>
            <th class="no-sort">Photos</th>
            <th class="no-sort">Results</th>
            <th class="no-sort">Reports</th>
            <th class="no-sort">Email</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div> 
</template>

<script>
export default {

}
</script>

<style>

</style>