<template>
    <json-table
        :data="data"
        :loaded="loaded"
    >

    </json-table>
</template>

<script>
    import JsonTable from "./JsonTable";


    export default {
        name: "JsonInfo",
        components: { JsonTable },
        props: {
            dateRecord: String,
            deviceId: String,
        },
        data() {
            return {
                jsonData: [],
                data: [],
                loaded: false
            }
        },
        watch: {
            jsonData: function () {
                var self = this;
                this.data = [];
                this.jsonData.forEach(function(element) {
                    var obj = element;
                    obj.timestamp = self.reformat(element.timestamp)+'<br>('+self.toTimestamp(element.timestamp)+')';
                    self.data.push(obj);
                    console.log(obj);
                });
            },
        },
        methods: {
            getJsonsFromAPI: function () {
                var self = this;
                this.$http.get("/json/"+this.deviceId+"/date/"+this.dateRecord).then(
                    response => {
                        self.jsonData = response.body;
                        self.loaded = true;
                    }
                );
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
            toTimestamp: function(strDate) {
                var d = new Date(strDate);
                return Math.floor(d.getTime()/1000);
            }
        },
        mounted() {
            // init internal model of the state machine
            this.getJsonsFromAPI();
        }
    }
</script>

<style scoped>

</style>
