<template>
    <vue-slider
        v-model="period"
        :min="0"
        :max="24"
        :min-range="1"
        :height="10"
        :process-dragable="true"
        tooltip-dir="top"
        :marks="true"
        @change="changeRange"
    >
    </vue-slider>

</template>

<script>
    import VueSlider from 'vue-slider-component';
    import 'vue-slider-component/theme/antd.css';

    export default {
        name: "ZoomBar",
        components: {
            VueSlider
        },
        data () {
            return {
                period: [0, 24]
            };
        },
        methods: {
            changeRange: function (val) {
            }
        }
    }
</script>

<style scoped>
    #zoombar {
        font-family: "Avenir", Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #2c3e50;
        margin-top: 60px;
    }
</style>
