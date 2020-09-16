<template>
    <raw-table
        :data="data"
        :loaded="loaded"
    >

    </raw-table>
</template>

<script>
    import RawTable from "./RawTable";


    export default {
        name: "JsonInfo",
        components: { RawTable },
        props: {
            dateRecord: String,
            deviceId: String,
        },
        data() {
            return {
                digitalData: [],
                data: [],
                loaded: false
            }
        },
        watch: {
            digitalData: function () {
                var self = this;
                this.data = [];
                var d = new Date(this.dateRecord + " 00:00:00"),
                    offset = new Date('1970-01-01 00:00:00').getTime(),
                    ts1 =  d.getTime()-offset;
                d.setDate(d.getDate()+1);
                var ts2 = d.getTime()-offset;

                var max_sensor = 0;
                this.digitalData.forEach(function(element) {
                    if (max_sensor<element.sensor) {
                        max_sensor = element.sensor;
                    }
                });
                var states = [];
                for (var i=0; i<=max_sensor; i++) {
                    states[i] = -1;
                }
                this.digitalData.forEach(function(element) {
                    var error = states[element.sensor] === element.value;
                    states[element.sensor] = element.value;
                    if (self.filterTimestamp(ts1, ts2, element.ts*1000)) {
                        var obj = {};
                        states.reduce(function (accumulator, currentValue, currentIndex) {
                            obj['dIn' + currentIndex] = currentValue;
                        });
                        obj.json_id = element.json_id;
                        obj.date = self.formatDateTime(element.ts * 1000);
                        obj.cell = element.sensor;
                        obj.error = error;
                        self.data.unshift(obj);
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
                var d = new Date(this.dateRecord + " 00:00:00");
                d.setDate(d.getDate() -1);
                this.digitalData = [];
                this.$http.get("/digital/"+this.deviceId+"/date/"+this.formatDate(d)).then(
                    response => {
                        self.digitalData = response.body;
                    }
                );
                this.$http.get("/digital/"+this.deviceId+"/date/"+this.dateRecord).then(
                    response => {
                        self.digitalData = self.digitalData.concat(response.body);
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
        mounted() {
            // init internal model of the state machine
            this.getDataFromAPI();
        }
    }
</script>

<style scoped>

</style>
