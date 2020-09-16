<template>
    <modal :title="getTitle"
           :modalId="modalId"
           :close_window="close_window"
           @triggerOk="saveTest"
           @triggerCancel="close_window = true">
        <div class="form-group" v-if="serverError">
            <div class="alert alert-danger">
                <span>{{ serverError }}</span>
            </div>
        </div>

        <vue-tiny-tabs
            id="testType"
            :anchor="true"
            :closable="false"
            :hideTitle="false"
            @on-after="onAfter">
                <!--  1st tab & sheet -->
        		<div class="section" id="1">
                    <h3>Sistema Magnético y Corriente de Excitación</h3>
                    <div class="form-group">
                        <label for="deltav">Rampa de Tensión de Excitación [Volt/seg]:</label>
                        <br>
                        <input-number
                            id="deltav"
                            :min="0.1"
                            :max="1"
                            :step="0.1"
                            decimalSeparator=","
                            :defaultValue="0.1"/>
                        <p>(Min: 0,1 [Volt/seg] - Max: 1 [Volt/seg])</p>
                    </div>
                    <div class="form-group">
                        <label for="evmax">Tensión Máxima de Excitación [Volt]:</label>
                        <br>
                        <input-number
                            id="ev"
                            :min="1"
                            :max="30"
                            :step="1"
                            :defaultValue="15"/>
                        <p>(Max: 30 [Volt])</p>
                    </div>
                    <div class="form-group">
                        <label for="isec">Corriente de Seguridad [miliAmperes]:</label>
                        <br>
                        <input-number
                            id="deltav"
                            :min="1"
                            :max="100"
                            :step="1"
                            :defaultValue="30"/>
                        <p>(Min: 1 [mA] - Max: 100 [mA])</p>
                    </div>
                    <div class="form-group">
                        <label for="repeatms">Cantidad de Repeticiones:</label>
                        <br>
                        <input-number
                            id="deltav"
                            :min="1"
                            :max="1000000"
                            :step="1"
                            :defaultValue="25"/>
                        <p>(Típico: 25)</p>
                    </div>
	        		<p class="title">&nbsp;Tipo I&nbsp;</p>
    		    </div>
                <!--  2nd tab & sheet -->
    	    	<div class="section" id="2">
                    <h3>Vida Útil Mecánica</h3>
                    <div class="form-group">
                        <label for="repeatul">Repeticiones:</label>
                        <input-number
                            id="deltav"
                            :min="1"
                            :max="2000000"
                            :step="50000"
                            size="10"
                            :defaultValue="2000000"/>
                        <p>(Minimo: 1 - Máximo: 2.000.000)</p>
                    </div>
                    <hr>
                    <div class="form-group">
                        <input
                            type="checkbox"
                            id="intsmii"
                            v-model="testData.intsmii"
                            />
                        <label for="intsmii">Intercalar Ensayos Adicionales Tipo I</label>
                    </div>
                    <div class="form-group" v-if="testData.intsmii">
                        <div class="intraform">
                            <label for="repeatsmii">Cantidad de Ensayos Adicionales Tipo I:</label>
                            <input-number
                                id="deltav"
                                :min="1"
                                :max="1000000"
                                :step="1"
                                :defaultValue="20"/>
                        </div>
                    </div>
	    	    	<p class="title">Tipo II</p>
    		    </div>
                <!--  3rd tab & sheet -->
    	    	<div class="section" id="3">
                    <h3>Con Carga</h3>
                    <div class="form-group">
                        <label for="percent">Degradación Máxima [%]:</label>
                        <input-number
                            id="deltav"
                            :min="1"
                            :max="100"
                            :step="1"
                            :defaultValue="40"/>
                        <p>(Típico: 40 [%])</p>
                    </div>
                    <div class="form-group">
                        <label for="repeatwl">Repeticiones:</label>
                        <input-number
                            id="deltav"
                            :min="1"
                            :max="10000000"
                            :step="1"
                            size=12
                            :defaultValue="10000000"/>
                        <p>(Minimo: 1 - Máximo: 10.000.000)</p>
                    </div>
                    <hr>
                    <div class="form-group">
                        <input
                            type="checkbox"
                            id="intsmiii"
                            v-model="testData.intsmiii"
                            />
                        <label for="intsmiii">Intercalar Ensayos Adicionales Tipo I</label>
                    </div>
                    <div class="form-group" v-if="testData.intsmiii">
                        <div class="intraform">
                            <label for="repeatsmiii">Cantidad de Ensayos Adicionales Tipo I:</label>
                            <input-number
                                id="deltav"
                                :min="1"
                                :max="1000000"
                                :step="1"
                                :defaultValue="20"/>
                            </div>
                    </div>
	    	    	<p class="title">Tipo IIII</p>
    		    </div>
	    </vue-tiny-tabs>
    </modal>
</template>

<script>
    import Modal from './Modal';
    import VueTinyTabs from 'vue-tiny-tabs';
    import { InputNumber } from 'ant-design-vue';
    import 'ant-design-vue/dist/antd.css';


    export default {
        name: "ModalTestSetup",
        components: {
            Modal,
            InputNumber,
            'vue-tiny-tabs': VueTinyTabs,
        },
        props: [ 'modalId' ],
        data: () =>({
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            errors: [],
            testData: {
                type:    1,
                deltav:  "",
                evmax:   "",
                isec:    "",
                mofiedBy: "",
                percent:  "",
                intsmii: false,
                intsmiii: false,
                repeatms: "",
                repeatul: "",
                repeatsmii: "",
                repeatwl: "",
                repeatsmiii: "",
                dec: ","
            },
            serverError: "",
            close_window: false,
        }),
        computed: {
            getTitle: function () {
                    return "Configuración del Ensayo";

            },
        },
        methods: {
            onAfter: function (id, tab) {
                this.testData.type = id;
                console.log(JSON.stringify(this.testData));
            },
            /*
             * updateUser: Update user info
             */
            saveTest: function () {
                // Reset errors
                this.errors = [];

                // Reset close window
                this.close_window = false;

                /*
                 * Init the POST data to access users.update route
                 */

                // upd = UPload Data
                var upd = {};

                // users.update's uri (e.g. route users.update)

                // token needed
                upd._token = this.csrf;

                // Load inputs to POST data
                upd.title = this.testData.title;
                upd.type = this.testData.type;
                upd.notes = this.testData.notes;

                // uri for insert
                var uri = '/tests';



                // try to post data
                this.$http.post(uri, upd).then(response => {
                    window.location.reload();
                }, response => {
                    if (response.data.errors) {
                        this.errors = response.data.errors;
                    } else {
                        this.serverError = 'Error: No se pudo guardar el ensayo';
                    }
                    console.log(response.data);
                });
            },
            initComponent () {
                // Reset errors
                this.errors=[];
                this.serverError = "";

                // Reset close window
                this.close_window = false;

                // Reset input data
                this.testData.deltav="0,1";
                this.testData.evmax="";
                this.testData.isec="";
                this.testData.mofiedBy="";
                this.testData.percent="";
                this.testData.intsmiii=false;
                this.testData.intsmii=false;
                this.testData.repeatms="";
                this.testData.repeatul="";
                this.testData.repeatsmii="";
                this.testData.repeatwl="";
                this.testData.repeatsmiii="";

            }
        },
        //Vue mounted event (e.g. after create and show the component)
        mounted() {
            var self = this;
            $( '#'+this.modalId ).on('show.bs.modal', function () {
                self.initComponent();
            });
        }
    }
</script>

<style lang="stylus">
    .error-label
        color: red

    .tinytabs .tabs
	    margin-left: 15px
	    display: flex
	    flex-flow: row wrap

    .tinytabs .tabs .tab .close
	    padding-left: 5px

    .tinytabs .tabs .tab
    	margin: 5px 3px 0 0
	    background: #e1e1e1
    	display: block
	    padding: 6px 15px
	    text-decoration: none
	    color: #666
	    font-weight: bold
	    border-radius: 3px 3px 0 0

    .tinytabs .section
	    padding: 6px 15px
	    background: #f1f1f1
	    overflow: hidden
	    clear: both
    	border-radius: 3px

    .tinytabs .tab.sel
    	background: #f1f1f1
	    color: #333
	    text-shadow: none

    .section
        height: 450px
        width: 100%

    .title
        visibility: hidden
        height: 0px

    .intraform
        padding: 15px

    label
        font-weight: bold
</style>
