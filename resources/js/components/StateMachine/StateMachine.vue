<template>
    <div>
        <events-table
            v-if="type=='transitions'"
            :data="stateRows"
            :loaded="loaded"
        >
        </events-table>
        <states-bar-graph
            v-if="type=='bars'"
            :data="stateRows"
            :loaded="loaded"
        >
        </states-bar-graph>
    </div>
</template>

<script>
    import EventsTable from "../EventsTable/EventsTable";
    import StatesBarGraph from "../StatesGraph/StatesBarGraph";

    export default {
        name: "StateMachine",
        components: {StatesBarGraph, EventsTable},
        props: {
            type:String,
            dateRecord: String,
            deviceId: String,
            stateInfo: {
                type: Array,
                default: () => [
                    {state: 0, color: "#CFE6C8", label: "Desocupado + Alto"},
                    {state: 1, color: "#CFE6C8", label: "Ocupado + Alto"},
                    {state: 2, color: "#fff2cc", label: "Bajando"},
                    {state: 3, color: "#f4cccc", label: "Ocupado + Bajo"},
                    {state: 4, color: "#f4cccc", label: "Desocupado + Bajo"},
                    {state: 5, color: "#fff2cc", label: "Subiendo"}
                    ],
            },
            stateMachine: {
                type: Array,
                default: () => [
                    {chg: [{dIn: 3, val: 0}], state: {from: 5, to: 0}},
                    {chg: [{dIn: 1, val: 1}], state: {from: 0, to: 1}},
                    {chg: [{dIn: 3, val: 1}], state: {from: 1, to: 2}},
                    {chg: [{dIn: 2, val: 0}], state: {from: 2, to: 3}},
                    {chg: [{dIn: 1, val: 0}], state: {from: 3, to: 4}},
                    {chg: [{dIn: 2, val: 1}], state: {from: 4, to: 5}}
                    ],
            }
        },
        data() {
            return {
                transitions: [],
                stateInputs: [1, 2, 3],
                errorColor: "#d04040",
                defaultColor: "#b0b0c0",
                tableClass: "table",
                loaded_pages: 0,
                page: []
            }
        },
        computed: {
            loaded: function () {
                var self = this;
                if (this.loaded_pages === 3) {
                    self.transitions = [];
                    this.page.forEach((set) => {
                        self.transitions = self.transitions.concat(set);
                    });
                    return true;
                }
                return false;
            },
            // stateSequence: From a set of transitions find the set of states sequence
            stateSequence: function() {
                var startStates = [];
                var startTime = 0;
                var self = this;
                var result = [];

                if (this.transitions !== []) {
                    this.transitions.forEach(function (transitionInfo, index) {
                        // CHANGE IN PRODUCTION VERSION !!!!
                        // filter repeated MQTT packages
                        if (transitionInfo.ts>=startTime) {
                            var obj = {
                                ts: transitionInfo.ts,
                                length: -1,
                                json_id: transitionInfo.json_id,
                                normal: true
                            };
                            if (index+1<self.transitions.length) {
                                obj.length = self.transitions[index+1].ts - transitionInfo.ts;
                            }
                            var newStates = self.getNewStates(transitionInfo.transition, startStates);
                            // If getNewStates returns an empty set, find the possibles new states using
                            // only the transition
                            if (newStates.length === 0) {
                                obj.normal = false;
                                newStates = self.getReachStates(transitionInfo.transition);
                                if (newStates.length === 0) {
                                    obj.normal = false;
                                    newStates = [-1]; // -1 means "imposible state"
                                }
                            }
                            obj.states = newStates;
                            // CHANGE IN PRODUCTION VERSION !!!!
                            // filter repeated MQTT packages
                            if ((newStates.length !== startStates.length ||
                                    !newStates.every((value, index) => value === startStates[index])) ||
                                        startTime !== transitionInfo.ts) {
                                result.push(obj);
                            }
                            startStates = newStates;
                            startTime = transitionInfo.ts;
                        }
                    });
                }
                return result;
            },
            // stateRows: From the sequence of the (set of) states, build the rows with
            //            the state information (color and label)
            stateRows: function () {
                var result = [];
                var self = this;
                var index = 0;

                this.stateSequence.forEach(function (stateInfo) {
                    if (self.timestampIncluded(stateInfo.ts)) {
                        var obj = {
                            id: index++,
                            ts: stateInfo.ts,
                            json_id: stateInfo.json_id,
                            date: self.timestampToDateTime(stateInfo.ts * 1000),
                            length: self.TimestampToString(stateInfo.length),
                            deviceId: self.deviceId,
                            dateRecord: self.dateRecord
                        };
                        if (stateInfo.states.length === 1) {
                            var state = stateInfo.states[0];
                            var found = false;
                            self.stateInfo.forEach(function (stl) {
                                if (state === stl.state && !found) {
                                    obj.state = "" + state;
                                    obj.label = stl.label;
                                    if (stateInfo.normal) {
                                        obj.color = stl.color;
                                    } else {
                                        obj.color = self.errorColor;
                                    }
                                    result.push(obj);
                                    found = true;
                                }
                            });
                            if (!found) {
                                obj.state = "" + state;
                                obj.label = "State: " + state;
                                if (stateInfo.normal) {
                                    obj.color = self.defaultColor;
                                } else {
                                    obj.color = self.errorColor;
                                }
                                result.push(obj);
                            }
                        } else {
                            obj.state = JSON.stringify(stateInfo.states)
                            obj.label = "Multiple states";
                            obj.color = self.errorColor;
                            result.push(obj);
                        }
                    }
                });

                return result.reverse();
            }
        },
        methods: {
            // equals: compare two sets (arrays) of objects
            equals: (arr1, arr2) => (arr1.every(x1 => arr2.some(x2 => x1.dIn === x2.dIn && x1.val === x2.val))),
            // getNewStates: Starting from the initial set of states and the transition given,
            //               find the set of new states. If the function fails, it returns an empty set.
            getNewStates: function (transition, initialStates) {
                if (initialStates !== []) {
                    var stm = this.stateMachine;
                    var result = [];
                    var self = this;

                    stm.forEach(function (stChg) {
                        if (initialStates.includes(stChg.state.from) && self.equals(transition, stChg.chg)) {
                            result.push(stChg.state.to)
                        }
                    });
                    return result;
                }
                return [];
            },
            // getReachStates:  Finds the set of new states the state machine can reach using
            //                 the transition given
            getReachStates: function (transition) {
                var stm = this.stateMachine;
                var result = [];
                var self = this;

                stm.forEach(function (stChg) {
                    if (self.equals(transition, stChg.chg)) {
                        result.push(stChg.state.to);
                    }
                });
                return result;
            },
            // timestampIncluded: Filter of the timestamp of the date
            timestampIncluded: function(ts) {
                var dt = new Date('1970-01-01 00:00:00');
                ts+= dt / 1000; // local to epoch
                var d1 = new Date(this.dateRecord + " 00:00:00");
                var d2 = d1;
                d1/=1000;
                d2.setDate(d2.getDate() + 1);
                d2/=1000;
                return (ts>=d1) && (ts<d2);
            },
            formatTwoDigits: function(num) {
                return ("0"+num).slice(-2);
            },
            // Form a data of Date type returns the date in format YYYY-MM-DD
            formatDate: function(date) {
                var d = new Date(date),
                    month = this.formatTwoDigits(d.getMonth() + 1),
                    day = this.formatTwoDigits(d.getDate()),
                    year = d.getFullYear();

                return [year, month, day].join('-');
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

                return [day, month, year].join('/') + ' ' +[hour, min, sec].join(':');
            },
            TimestampToString: function(ts) {
                if (ts<60) {
                    return ts+" seg.";
                } else if (ts<3600) {
                    var m = Math.floor(ts/60),
                        s = ts - m * 60;
                    return m + " min. "+s+" seg.";

                }

                var h = Math.floor(ts/3600)
                m = Math.floor(ts/60-h*60),
                    s = ts - m * 60 - h*3600;
                return h + " hs. " + m + " min. "+s+" seg.";
            },
            // Get the transitions set of the date plus the 6 hours of the date before
            getTransitionsFromPage: function () {
                var self = this;
                var d = new Date(this.dateRecord + " 00:00:00");
                this.loaded_pages = 0;

                d.setDate(d.getDate() -1);
                var link1 = "/digital/"+this.deviceId+"/filter/"+this.stateInputs.join("-")+"/date/"+this.formatDate(d);
                this.$http.get(link1).then(
                    response => {
                        self.page[0] = [];
                        response.body.forEach(function (record) {
                            var obj = {};
                            obj.dIn = record.sensor;
                            obj.val = record.value;
                            var trans = {};
                            trans.transition = [obj];
                            trans.ts = record.ts;
                            trans.json_id = record.json_id;
                            self.page[0].push(trans);
                        });
                        self.loaded_pages+=1;
                    }
                );
                var link2="/digital/"+this.deviceId+"/filter/"+this.stateInputs.join("-")+"/date/"+this.dateRecord;
                this.$http.get(link2).then(
                    response => {
                        self.page[1] = [];
                        response.body.forEach(function (record) {
                            var obj = {};
                            obj.dIn = record.sensor;
                            obj.val = record.value;
                            var trans = {};
                            trans.transition = [obj];
                            trans.ts = record.ts;
                            trans.json_id = record.json_id;
                            self.page[1].push(trans);
                        });
                        self.loaded_pages+=1;
                    }
                );
                d.setDate(d.getDate() + 2);
                var link3 = "/digital/"+this.deviceId+"/filter/"+this.stateInputs.join("-")+"/date/"+this.formatDate(d);
                this.$http.get(link3).then(
                    response => {
                        self.page[2] = [];
                        response.body.forEach(function (record) {
                            var obj = {};
                            obj.dIn = record.sensor;
                            obj.val = record.value;
                            var trans = {};
                            trans.transition = [obj];
                            trans.ts = record.ts;
                            trans.json_id = record.json_id;
                            self.page[2].push(trans);
                        });
                        self.loaded_pages+=1;
                    }
                );
            },
        },
        watch: {

        },
        mounted() {
            // init internal model of the state machine
            this.getTransitionsFromPage();
        }
    }
</script>

<style scoped>

</style>
