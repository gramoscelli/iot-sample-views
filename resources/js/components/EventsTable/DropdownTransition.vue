<template>
    <popper
        trigger="hover"
        :options="{placement: 'down' }"
        @show="loadInfo"
        >
        <span class="popper my_span">
            Timestamp: <b v-html="timestampToDateTime(rowData.ts*1000)"></b> <b>({{ rowData.ts }})</b><br>
            <span v-if="!loaded">Cargando...</span>
            <table class="table" v-if="loaded">
                <tbody>
                <tr>
                    <th class="text-center">Id</th>
                    <th class="text-center">Hora de Llegada</th>
                    <th class="text-left">Payload</th>
                </tr>
                <tr v-for="item in info">
                    <td>
                        <a href="">{{ item.id }}</a>
                    </td>
                    <td>
                        <a href="" v-html="reformat(item.timestamp)"></a>
                    </td>
                    <td class="text-left">
                        <span v-html="paint(item.payload,  rowData.ts)"></span>
                    </td>
                </tr>
                </tbody>
            </table>
        </span>
        <span slot="reference">
            &nbsp;&nbsp;<a :href="jsonRef()" v-html="timestampToDateTime(rowData.ts*1000)"></a>&nbsp;&nbsp;
        </span>
    </popper>
</template>

<script>
    import VuePopper from '../Popper/VuePopper';
    export default {
        name: "DropdownTransition",
        components: {
            'popper': VuePopper
        },
        data() {
            return {
                loaded: false,
                info: {},
            }
        },
        props: {
            rowField: {
                type: Object,
            },
            rowData: {
                type: Object,
            },
            rowIndex: {
                type: Number,
            },
            vuetable: {
                type: Object,
            },
            isHeather: {
                type: Boolean,
            }
        },
        methods: {
            jsonRef: function () {
                return "/nrid/" + this.rowData.json_id;
            },
            paint: function (text, ts) {
                return text.split(ts).join("<b>"+ts+"</b>");
            },
            formatTwoDigits: function(num) {
                return ("0"+num).slice(-2);
            },
            // Form a data of Date type returns the date in format YYYY-MM-DD
            timestampToDateTime: function(ts) {
                var offset = new Date('1970-01-01 00:00:00').getTime(),
                    d = new Date(ts+offset),
                    sec = this.formatTwoDigits(d.getSeconds()),
                    min = this.formatTwoDigits(d.getMinutes()),
                    hour = this.formatTwoDigits(d.getHours()),
                    month = this.formatTwoDigits(d.getMonth() + 1),
                    day = this.formatTwoDigits(d.getDate()),
                    year = d.getFullYear();

                return [day, month, year].join('/') + '&nbsp;' +[hour, min, sec].join(':');
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

                return [day, month, year].join('/') + '&nbsp;' +[hour, min, sec].join(':');
            },
            loadInfo: function() {
                var url = this.jsonRef();
                var self = this;
                this.$http.get(url).then(
                    response => {
                        self.info = response.body;
                        self.loaded = true;
                    },
                    response => {
                        console.log("Error!");
                    }
                );
            }
        },
    }
</script>

<style scoped>
    .my_span {
        visible: none;
        z-index:1000;
        background-color: #DDD;
        padding: 5px;
        border-radius: 4px;
        width: 80%;
        position: absolute;
        transform: translateY(-5px);
        text-align: left;
    }
</style>
