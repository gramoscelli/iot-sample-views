<template>
    <div>
        <analogic-chart
            :data="data"
            :loaded="loaded"
            :date="dateRecord"
            :source="source"
            :scales="scales"
            :title="title"
            magnitude="Volts"
        ></analogic-chart>
        <div
            v-if="loaded == true && isMobile"
            class="centering"
        >
            <span class="center">Nivel de Zoom</span>
        </div>
        <div
            v-if="loaded == true && isMobile"
        >
            <zoom-bar
            ></zoom-bar>
        </div>
    </div>
</template>

<script>
    import AnalogicChart from "./AnalogicChart";
    import ZoomBar from "../ZoomSlider/ZoomBar";


    export default {
        name: "AnalogicGraph",
        components: {ZoomBar, AnalogicChart },
        props: {
            title: String,
            dateRecord: String,
            deviceId: String,
            source: Array,
            scales: Array
        },
        data() {
            return {
                analogicData: [],
                data: [],
                loaded: false,
                startTs: 0
            }
        },
        watch: {
            analogicData: function (val) {
                var self = this;
                this.data = [];
                var inter = [];
                var sensors = [];
                var offset = new Date('1970-01-01 00:00:00').getTime();
                this.ts0 = (new Date(this.dateRecord+" 00:00:00") - offset)/1000;

                this.source.forEach(function (element) {
                    sensors.push(element.sensor);
                });

                val.forEach(function(element) {
                    // element = { device_id, fecha, id, json_id, sensor, sl, ts, value }
                    if (sensors.includes(element.sensor)) {
                        var ind = element.ts-self.ts0;
                        if (inter[ind] == null) inter[ind] = [];
                        inter[ind][sensors.indexOf(element.sensor)] = Math.floor(element.value*2048);
                    }
                });
                inter.forEach(function (element, index) {
                    if (element.length === sensors.length) {
                        self.data.push([index/3600, ...element]);
                    } else {
                        console.log('Error: missing '+(sensors.length - element.length)+' values of the sensors set.')
                    }
                });
            },
        },
        methods: {
            filterTimestamp: function (ts1, ts2, ts) {
                return (ts>=ts1) && (ts<=ts2);
            },
            getDataFromAPI: function () {
                var self = this;
                this.analogicData = [];
                this.$http.get("/analogic/"+this.deviceId+"/date/"+this.dateRecord).then(
                    response => {
                        self.analogicData = response.data;
                        self.loaded = true;
                    }
                );
            },
            // Form a data of Date type returns the date in format YYYY-MM-DD
            formatDate: function(date) {
                var d = new Date(date),
                    month = this.formatTwoDigits(d.getMonth() + 1),
                    day = this.formatTwoDigits(d.getDate()),
                    year = d.getFullYear();

                return [year, month, day].join('-');
            },
            // Form a data of Date type returns the date in format YYYY-MM-DD HH:MM:SS
            formatDateTime: function(date) {
                var  offset = new Date('1970-01-01 00:00:00').getTime(),
                    d = new Date(date+offset),
                    month = this.formatTwoDigits(d.getMonth() + 1),
                    day = this.formatTwoDigits(d.getDate()),
                    year = d.getFullYear(),
                    sec = this.formatTwoDigits(d.getSeconds()),
                    min = this.formatTwoDigits(d.getMinutes()),
                    hour = this.formatTwoDigits(d.getHours());

                return [day, month, year].join('/') + ' ' +[hour, min, sec].join(':');
            },
            formatTwoDigits: function(num) {
                return ("0"+num).slice(-2);
            },
            // Form a data of Date type returns the date in format YYYY-MM-DD
            reformat: function(strDate) {
                var d = new Date(strDate),
                    sec = this.formatTwoDigits(d.getSeconds()),
                    min = this.formatTwoDigits(d.getMinutes()),
                    hour = this.formatTwoDigits(d.getHours()),
                    month = this.formatTwoDigits(d.getMonth() + 1),
                    day = this.formatTwoDigits(d.getDate()),
                    year = d.getFullYear();

                return [day, month, year].join('/') + ' ' +[hour, min, sec].join(':');
            },
        },
        computed: {
            isMobile: function() {
                if(/Android|webOS|iPhone||iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                    return true
                } else {
                    return false
                }
            }
        },
        mounted() {
            // init internal model of the state machine
            this.getDataFromAPI();
        }
    }
</script>

<style scoped>

</style>
