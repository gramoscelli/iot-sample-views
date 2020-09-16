<template>
    <input type="date" 
           :name="name"
           :data-date="dt"
           v-model="value"
           @change="setLabel"/>
</template>

<script>
    export default {
        name: "InputDate",
        props: {
            name: String,
            initValue: String,
        },
        data() {
            return {
                value: String,
                dt: {
                    type: String,
                    default: "01/01/2020"
                }
            }
        },
        methods: {
            setLabel: function (evt) {
                this.dt = moment(this.value).format('DD/MM/YYYY');
            }
        },
        mounted() {
            this.value = this.initValue;
            this.dt =  moment(this.initValue).format('DD/MM/YYYY');
        }
    }
</script>

<style scoped>
    input[name=date] {
        position: relative;
        width: 150px; height: 30px;
        color: white;
    }

    input[name=date]:before {
        position: absolute;
        top: 3px; left: 3px;
        content: attr(data-date);
        display: inline-block;
        color: black;
    }

    input[name=date]::-webkit-datetime-edit,
    input[name=date]::-webkit-inner-spin-button,
    input[name=date]::-webkit-clear-button {
        display: none;
    }

    input[name=date]::-webkit-calendar-picker-indicator {
        position: absolute;
        top: 3px;
        right: 0;
        color: black;
        opacity: 1;
    }
</style>
