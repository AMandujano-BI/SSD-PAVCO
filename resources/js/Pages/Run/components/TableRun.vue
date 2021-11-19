<template>
    <div class="container p-9">
        <table id="activeRuns" class="display" style="width:100%">
        <thead>
            <tr>
                <th>StartDate</th>
                <th>Run #</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in data" :key="item.id">
                <td>{{item.id}}</td>
                <td>{{item.startDate}}</td>
                <td>{{item.status}}</td>
            </tr>
        </tbody>
    </table>
    </div>
</template>

<script>
const $  = require( 'jquery' );
import dt from 'datatables.net';

export default {
    props:['data'],
    methods: {
        generateDataTable() {
            $(document).ready(function() {
               $('#activeRuns').DataTable( )
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