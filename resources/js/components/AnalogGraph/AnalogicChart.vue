<template>
    <div class="row-fluid card">
        <div class="title text-center" v-if="title.length!=0">{{ title }}</div>
        <div class="text-center">{{ labelReady }}</div>
        <GChart ref="chart"
            type="LineChart"
            :data="chartData"
            :options="chartOptions"
            @ready="onChartReady"
        />
    </div>
</template>

<script>
    import { GChart } from 'vue-google-charts'

    export default {
        components: {
            GChart
        },
        name: "AnalogicChart",
        props: {
            title: String,
            data: Array,
            date: String,
            source: Array,
            scales: Array,
            loaded: {
                type: Boolean,
                default: false,
            },
            bgColor: {
                type: String,
                default: '#ffffe0'
            },
            linesColor: Array,
        },
        data () {
            return {
                chartReady: false,
                vmin: 0,
                vmax: 1,
            }
        },
        methods: {
            onChartReady: function (chart, google) {
            }
        },
        computed: {
            labels: function() {
                var result = [];
                this.source.forEach(function (element) {
                    result.push(element.title);
                });
                return result;
            },
            labelReady: function () {
                if (!this.loaded)
                    return 'Cargando...';
                return "";
            },
            chartData: function () {
                if (this.data.length === 0)
                    return [
                        ['Hora'].concat(this.labels),
                        [new Date(this.date+" 00:00:00")].concat(new Array(this.source.length).fill(0)),
                        [new Date(this.date+" 23:59:59")].concat(new Array(this.source.length).fill(0))
                    ];
                var self = this;
                var factors = [1];

                this.source.forEach(function (element) {
                    factors.push(element.factor);
                });

                this.vmin = 0;
                this.vmax = 0;

                var scaled = this.data.map(row => {
                    return row.map((item, index) => {
                        if (index === 0) {
                            var h = Math.floor(item),
                                m = Math.floor((item  - h)*60),
                                s = Math.floor((((item  - h)*60)-m)*60);
                            var strTime = ((h<10)? '0'+h:''+h)+':'+((m<10)? '0'+m:''+m)+':'+((s<10)? '0'+s:''+s);

                            var y = self.date.substring(0, 4),
                                M = self.date.substring(5, 7),
                                d = self.date.substring(8, 10);
                            return {v: new Date(self.date + ' '+strTime), f: d+'/'+M+'/'+y+' '+strTime};
                        } else {
                            item = item * factors[index];
                            if (self.vmin > item) self.vmin = item;
                            if (self.vmax < item) self.vmax = item;
                            return item;
                        }
                    });
                });
                return [['Hora'].concat(this.labels), ...scaled];
            },
            chartOptions: function () {
                var result = {
                    hAxis: {
                        title: 'Hora',
                        format: 'HH:mm'
                    },
                    vAxis: {},
                    backgroundColor: this.bgColor,
                    series: {},
                    explorer: {
                        actions: ['dragToZoom', 'rightClickToReset'],
                        axis: 'horizontal',
                        keepInBounds: true,
                        maxZoomIn: 0.01
                    }
                };

                this.source.forEach(function (element, index) {
                    result.series[""+index] = {targetAxisIndex: element.axis, color: element.color};
                });

                this.scales.forEach(function (element, index) {
                    result.vAxis[""+index] = {viewWindow: { max: element.max, min: element.min}
                    };
                });

                console.log(result);

                return result;

            },
        },
        mounted() {
            var observer = new MutationObserver(function (mutations) {
                mutations.forEach(function (mutation) {
                    mutation.addedNodes.forEach(function (node) {

                        // adjust overlays here
                        console.log('nodes change...');
                    });
                });
            });
            observer.observe(this.$refs.chart.$refs.chart, {
                childList: true,
                subtree: true
            });
        }
    }
</script>

<style scoped>
    .title {
        font-size: 14pt;
        font-weight: bold;
    }
</style>
