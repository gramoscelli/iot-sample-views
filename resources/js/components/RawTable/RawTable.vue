<template>
    <vuetable ref="vuetable"
              pagination-path=""
              :css="css"
              :api-mode="false"
              :data="data"
              :fields="fields"
              :table-height="tableHeight"
              :no-data-template="emptyLabel"
              :cell-style="onCellStyle"
              :row-class="onRowClass"
    >
    </vuetable>
</template>

<script>
    import Vuetable from '../vuetable/Vuetable';

    export default {
        components: {
            Vuetable,
        },
        props: {
            data: Array,
            loaded: Boolean
        },
        data () {
            return {
                css: {
                    tableClass: 'table table-hovered',
                    loadingClass: 'loading',
                    handleIcon: 'fa fa-film',
                },
                fields: [
                    {
                        name: 'date',
                        title: 'Tiempo',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        width: "30%"
                    },
                    {
                        name: 'dIn1',
                        title: 'D1',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        width: "10%"
                    },
                    {
                        name: 'dIn2',
                        title: 'D2',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        width: "10%"
                    },
                    {
                        name: 'dIn3',
                        title: 'D3',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        width: "10%"
                    },
                    {
                        name: 'dIn4',
                        title: 'D4',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        width: "10%"
                    },
                    {
                        name: 'dIn5',
                        title: 'D5',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        width: "10%"
                    },
                    {
                        name: 'dIn6',
                        title: 'D6',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        width: "10%"
                    },
                    {
                        name: 'dIn7',
                        title: 'D7',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        width: "10%"
                    }
                ]
            }
        },
        computed: {
            tableHeight: () => "500px",
            emptyLabel: function() {
                return this.loaded ? "<b>Sin datos del día</b>" : "Cargando datos...";
            }
        },
        methods: {
            onRowClass: function (dataItem, index) {
                return  index % 2 ? "color-white" : "color-green";
            },
            onCellStyle: function (dataItem, field) {
                if (field.name === "dIn"+dataItem.cell) {
                    return dataItem.error ? { backgroundColor: "Red"} : { backgroundColor: "Aqua"};
                }
                return {};
            }
        }
    }
</script>

<style>
    .color-white {
        background-color: white;
    }
    .color-green {
        background-color: #dfd;
    }
</style>
