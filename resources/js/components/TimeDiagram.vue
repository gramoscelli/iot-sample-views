<template>
    <table class="diagram">
        <tbody>
        <tr>
            <th class="labels">
               Origen de la señal
            </th>
            <th class="title" :colspan="samples_count">Diagrama Temporal de las Entradas</th>
        </tr>
        <tr v-for="arow in rows">
            <td class="label"> {{ arow.title }} </td>
            <td v-for="n in count" class="sample" :key="n">
                {{ arow.rows[n] }}
            </td>

        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        name: "TimeDiagram.vue",
        props: [],
        computed: {
        }
    }
</script>

<style lang="stylus">
    table.diagram
        border 1px solid black
        table-layout fixed
        width 80wh
</style>
