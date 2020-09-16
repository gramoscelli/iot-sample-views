<template>
    <div>
        <bar-chart
            :data="data"
            :loaded="loaded"
            :date="dateRecord"
            :title="title"
        >
        </bar-chart>
    </div>
</template>

<script>
    import BarChart from "./BarChart";


    export default {
        name: "StatesBarGraph",
        components: { BarChart },
        props: {
            dateRecord: String,
            title: String,
            colors: Array,
            states: Array,
        },
        data() {
            return {
                timeData: [],
                data: [],
                loaded: false
            }
        },
        watch: {
            timeData: function (val) {
                var self = this;
                this.data = [];
                var ts_before = [];
                this.sensors.forEach(function (element) {
                    ts_before[element] = 0;
                });
                var offset = new Date('1970-01-01 00:00:00').getTime();
                this.ts0 = (new Date(this.dateRecord+" 00:00:00") - offset)/1000;
                val.forEach(function(element) {
                    // element = { id, device_id, sl, sensor, ts, value}
                    if (self.sensors.includes(element.sensor)) {
                        if (ts_before[element.sensor] > 0) {
                            if (element.ts-ts_before[element.sensor] <= self.filterTime) {
                                self.data.push([
                                    self.timestampToString(element.ts - self.ts0),
                                    element.ts - ts_before[element.sensor],
                                    self.colors[self.sensors.indexOf(element.sensor)]
                                ]);
                            }
                        }
                        ts_before[element.sensor] = element.ts;
                    }
                });
            },

        },
        methods: {
            formatTwoDigits: function(num) {
                return ("0"+num).slice(-2);
            },
            // Form a data of Date type returns the date in format YYYY-MM-DD
            timestampToString: function(ts) {
                var offset = new Date('1970-01-01 00:00:00').getTime(),
                    d = new Date(ts+offset),
                    sec = this.formatTwoDigits(d.getSeconds()),
                    min = this.formatTwoDigits(d.getMinutes()),
                    hour = this.formatTwoDigits(d.getHours()),
                    month = this.formatTwoDigits(d.getMonth() + 1),
                    day = this.formatTwoDigits(d.getDate()),
                    year = d.getFullYear();

                return [day, month, year].join('/') + ' ' +[hour, min, sec].join(':');
            },
            getDataFromAPI: function () {
                // Get the transitions set of the date plus the 6 hours of the date before
                var self = this;
                var d = new Date(this.dateRecord + " 00:00:00");
                d.setDate(d.getDate() - 1);
                var result=[];
                var link1 = "/digital/"+this.deviceId+"/filter/"+this.sensors.join("-")+"/date/"+this.formatDate(d)+'/period/4';
                this.$http.get(link1).then(
                    response => {
                        response.body.forEach(function (record) {
                            result.push(record);
                        });
                    }
                );
                var link2="/digital/"+this.deviceId+"/filter/"+this.sensors.join("-")+"/date/"+this.dateRecord;
                this.$http.get(link2).then(
                    response => {
                        response.body.forEach(function (record) {
                            result.push(record);
                        });
                        self.timeData = result;
                        self.loaded = true;
                    });
            },
            // Form a data of Date type returns the date in format YYYY-MM-DD
            formatDate: function(date) {
                var d = new Date(date),
                    month = this.formatTwoDigits(d.getMonth() + 1),
                    day = this.formatTwoDigits(d.getDate()),
                    year = d.getFullYear();

                return [year, month, day].join('-');
            },
        },
        mounted() {
            // init internal model of the state machine
            this.getDataFromAPI();
        }
    }
</script>

<style scoped>

</style>
