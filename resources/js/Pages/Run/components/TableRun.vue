<template>
    <div class="container p-9">
        <table id="activeRuns" class="display" style="width:100%">
        <thead>
            <tr>
                <th>StartDate</th>
                <th>Customer</th>
                <th>Run #</th>
                <th>Method</th>
                <th>Status</th>
                <th>Hrs</th>
                <th>Photos</th>
                <th>Results</th>
                <th>Edit</th>
                <th>Delete</th>
                <th>Close</th>
                <th>Re-Open</th>
                <th>Notes</th>
                <th>Reports</th>
                <th>Email</th>
            </tr>
        </thead>
    </table>
    </div>
</template>

<script>
const $  = require( 'jquery' );
import dt from 'datatables.net';

export default {
    props:['data'],
    methods: {
        // generateDataTable() {
        //     $(document).ready(function() {
        //        $('#activeRuns').DataTable({
        //            "ajax": "/run/getAllRuns",
        //             "columns": [
        //                 { data: "startDate" },
        //                 { data: "user_id" },
        //                 { data: "id" },
        //                 { data: "plate_methods_id" },
        //                 { data: "status" },
        //                 { data: "status" },
        //             ]
        //        })
        //     })
        // }
        generateDataTable() {
            $(document).ready(function() {
               $('#activeRuns').DataTable({
                   "ajax": "/run/getAllRuns",
                    "columns": [
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
                            orderable: false
                        },
                        {
                            data: null,
                            orderable: false,
                            render: function (data, type, row) { return "<i class='fas fa-images'></i>" }
                        }
                        // { data: null,
                        //   className: "dt-center editor-edit",
                        //   defaultContent: '<i class="fas fa-images"></i>',
                        //   orderable: false },
                        // { data: null,
                        //   className: "dt-center editor-edit",
                        //   defaultContent: '<i class="fas fa-poll"></i>',
                        //   orderable: false },
                        // { data: null,
                        //   className: "dt-center editor-edit",
                        //   defaultContent: '<i class="far fa-edit"></i>',
                        //   orderable: false },
                        // { data: null,
                        //   className: "dt-center editor-edit",
                        //   defaultContent: '<i class="far fa-trash-alt"></i>',
                        //   orderable: false },
                        // { data: null,
                        //   className: "dt-center editor-edit",
                        //   defaultContent: '<i class="far fa-check-circle"></i>',
                        //   orderable: false },
                        // { data: null,
                        //   className: "dt-center editor-edit",
                        //   defaultContent: '<i class="far fa-folder-open"></i>',
                        //   orderable: false },
                        // { data: null,
                        //   className: "dt-center editor-edit",
                        //   defaultContent: '<i class="fas fa-sticky-note"></i>',
                        //   orderable: false },
                        // { data: null,
                        //   className: "dt-center editor-edit",
                        //   defaultContent: '<i class="far fa-file-alt"></i>',
                        //   orderable: false },
                        
                    ]
               })
            })
        }
    },
    mounted() {
        this.generateDataTable()
    }
}
</script>

<style>

</style>