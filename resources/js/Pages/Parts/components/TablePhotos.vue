<template>
    <table id="photosTable" class="display" style="width: 100%">
    <thead>
      <tr>
        <th>Name</th>
        <th>Date Added </th>
        <th>Hours</th>
        <th>Description</th>
        <th>View</th>
        <th>Report</th>
        <th>Delete</th>
      </tr>
    </thead>
  </table>

</template>

<script>
export default {
  setup() {
    const generateDataTable = () => {
      $(document).ready(function () {
        $("#photosTable").DataTable({
          ajax: "/run/getAllRuns",
          columns: [
            { data: "startDate" },
            { data: "user_id" },
            { data: "id" },
            { data: "plate_methods_id" },
            { data: "status" },
            { data: "status" },
            {
              data: null,
              className: "dt-center editor-edit",
              defaultContent: '<i class="fa fa-pencil"/>',
              orderable: false,
            },
            {
              data: null,
              orderable: false,
              render: function (data, type, row) {
                return "<i class='fas fa-images'></i>";
              },
            },
          ],
        });
      });
    };
    return {
      generateDataTable,
    };
  },
}
</script>