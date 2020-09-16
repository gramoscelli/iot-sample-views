<template>
    <vuetable ref="vuetable"
              pagination-path=""
              :css="css"
              :api-mode="false"
              :data="data"
              :fields="fields"
              :table-height="tableHeight"
              :row-style="onRowStyle"
              :no-data-template="emptyLabel"
    >
    </vuetable>
</template>

<script>
    import Vuetable from '../vuetable/Vuetable';
    import Vue from 'vue';
    import DropdownTransition from './DropdownTransition';
    Vue.component('dropdown-transition', DropdownTransition);

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
                    ascendingIcon: 'fa fa-chevron-up',
                    descendingIcon: 'fa fa-chevron-down',
                    tableClass: 'table table-hovered',
                    loadingClass: 'loading',
                    handleIcon: 'fa fa-film',
                },
                fields: [
                    {
                        name: '__component:dropdown-transition',   // <----
                        title: 'Fecha',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        width: "15%"
                    },
                    {
                        name: 'state',
                        title: 'Estado',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        sortField: 'state',
                        width: "10%"
                    },
                    {
                        name: 'label',
                        title: 'Etiqueta',
                        titleClass: 'text-center',
                        dataClass: 'text-left',
                        width: "20%"
                    },
                    {
                        name: 'length',
                        title: 'Duración',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        width: "15%"
                    },
                    {
                        name: 'info',
                        title: 'Adicional',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        width: "40%"
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
            onRowStyle: function (dataItem, index) {
                return  { backgroundColor: dataItem.color};
            },
        }
    }
</script>

<style>
</style>
