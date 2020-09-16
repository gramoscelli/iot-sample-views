<template>
    <vuetable ref="vuetable"
              pagination-path=""
              :css="css"
              :api-mode="false"
              :data="data"
              :fields="fields"
              :table-height="tableHeight"
              :row-class="onRowClass"
              :no-data-template="emptyLabel"
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
                        name: 'timestamp',
                        title: 'Hora del Servidor',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        width: "20%"
                    },
                    {
                        name: 'topic',
                        title: 'Tópico MQTT',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                        width: "15%"
                    },
                    {
                        name: 'payload',
                        title: 'Payload (JSON)',
                        titleClass: 'text-center',
                        dataClass: 'text-left',
                        width: "55%"
                    },
                    {
                        name: 'json_id',
                        title: 'JSON Id',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                        width: "10%"
                    },
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
        }
    }
</script>

<style>
    .color-white {
        background-color: white;
    }
    .color-green {
        background-color: #bfb;
    }
</style>
