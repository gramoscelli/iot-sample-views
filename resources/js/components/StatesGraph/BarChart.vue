<template>
    <div class="row-fluid card">
        <div class="title text-center" v-if="title.length!=0">{{ title }}</div>
        <div class="text-center">{{ labelReady }}</div>
        <GChart
            type="ColumnChart"
            :data="chartData"
            :options="chartOptions"
        />
    </div>
</template>

<script>
    import { GChart } from 'vue-google-charts'

    export default {
        components: {
            GChart
        },
        name: "BarStates",
        props: {
            title: {
                type: String,
                default: ""
            },
            data: Array,
            color: String,
            loaded: {
                type: Boolean,
                default: false,
            },
            bgColor: {
                type: String,
                default: '#ffffe0'
            },
        },
        data () {
            return {
                chartReady: false,
                vmin: 0,
                vmax: 1,
            }
        },
        computed: {
            labelReady: function () {
                if (!this.loaded)
                    return 'Cargando...';
                return "";
            },
            chartData: function () {
                console.log(123141);
                if (this.data.length === 0)
                    return [
                        ["Timestamp", "Duración", { role: 'style'}],
                        ['2019', 0, '#000000']
                    ];
                return [["Timestamp", "Duración", { role: 'style'}]].concat(this.data);
            },
            chartOptions: function () {
                return {
                    hAxis: {
                        slantedText: true,
                        slantedTextAngle: 90,
                        showTextEvery:2,
                        title: 'Hora',
                        format: 'dd/MM/YYYY'
                    },
                    vAxis: {
                        title: this.titleY,
                        //ticks: vticks,
                    },
                    backgroundColor: this.bgColor,
                    bar: {
                        groupWidth: 5
                    },
                    series: {
                        0: {color: this.lineColor},
                    },
                    width:'100%',
                    height:400,
                    chartArea: {
                        top: 55,
                        height: '60%'
                    }                }
            }
        }
    }
</script>
<style scoped>

</style>
