<template>
    <popper
        trigger="hover"
        :options="{placement: 'down' }" >
        <div class="popper" v-html="content"></div>
        <button slot="reference">
            Reference Element
        </button>
    </popper>
</template>

<script>
    import VuePopper from './VuePopper';

    export default {
        name: "Test",
        components: {
            'popper': VuePopper
        },
        data() {
            return {
                content: "Ni idea que poner...",
            }
        },
        mounted() {
            var self = this;
            setTimeout(function(){
                self.content = "sdfsadfsadfasdf<br>sdfsadfsadfasdf<br>sdfsadfsadfasdf<br>sdfsadfsadfasdf<br>sdfsadfsadfasdf<br>sdfsadfsadfasdf";
            }, 3000);
        }
    }
</script>
