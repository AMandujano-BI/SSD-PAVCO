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
  </table>
</template>

<script>
export default {
  setup() {
    const generateDataTable = () => {
      $(document).ready(function () {
        $("#partsTable").DataTable({
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
};
</script>
