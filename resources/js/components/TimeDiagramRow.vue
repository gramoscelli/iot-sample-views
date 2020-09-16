<template>
    <tr>
        <td class="row_title">
            <span>{{rowData.name}}</span>
        </td>
        <td v-for="">
            <label>Gender: </label>
            <span>{{rowData.gender}}</span>
        </td>
    </tr>
</template>

<script>
    export default {
        name: "td_row",
    }
</script>

<style scoped>

</style>
