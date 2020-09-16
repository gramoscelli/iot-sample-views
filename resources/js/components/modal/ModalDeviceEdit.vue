<template>
    <modal :title="getTitle"
           :modalId="modalId"
           :close_window="close_window"
           @triggerOk="updateDevice"
           @triggerCancel="close_window = true">
        <div class="form-group" v-if="serverError">
            <div class="alert alert-danger">
                <span>{{ serverError }}</span>
            </div>
        </div>

        <div class="form-group">
            <label>Dispositivo:</label>
            <input
                type="text"
                class="form-control"
                list="devices"
                v-model="deviceData.device_number"
                v-if="newDevice"/>
            <datalist v-if="newDevice" id="devices">
                <option v-for="devnum in deviceList">{{ devnum }}</option>
            </datalist>
            <p class="info-label" v-if="newDevice && (deviceList !== [])">(hay otros dispositivos conectados)</p>
            <input
                type="text"
                class="form-control"
                v-model="deviceData.device_number"
                readonly
                v-else/>
            <p class="error-labelº" v-if="errors['device_number']">Error: {{ errors['device_number'][0] }}</p>
        </div>

        <div class="form-group">
            <label>Línea:</label>
            <input
                type="text"
                class="form-control"
                v-model="deviceData.line" />
            <p class="error-label" v-if="errors['line']">Error: {{ errors['line'][0] }}</p>
        </div>

        <div class="form-group">
            <label>Paso a Nivel:</label>
            <input
                type="text"
                class="form-control"
                v-model="deviceData.name" />
            <p class="error-label" v-if="errors['name']">Error: {{ errors['name'][0] }}</p>
        </div>

        <div class="form-group">
            <label>Ciudad:</label>
            <input
                type="text"
                class="form-control"
                v-model="deviceData.city" />
            <p class="error-label" v-if="errors['city']">Error: {{ errors['city'][0] }}</p>
        </div>

        <div class="form-group">
            <label>Provincia:</label>
            <input
                type="province"
                class="form-control"
                v-model="deviceData.province" />
            <p class="error-label" v-if="errors['province']">Error: {{ errors['province'][0] }}</p>
        </div>

        <div class="form-group">
            <label>Prestadora:</label>
            <input
                type="text"
                class="form-control"
                v-model="deviceData.cell_company" />
            <p class="error-label" v-if="errors['cell_company']">Error: {{ errors['cell_company'][0] }}</p>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-sm-6 text-left">
                    <label>Clave (acceso MQTT):</label>
                </div>
                <div class="col-sm-6 text-right">
                    <button @click="makePass">Generar Clave</button>
                </div>
            </div>
            <input
                type="text"
                class="form-control"
                v-model="deviceData.password" />
            <p class="error-label" v-if="errors['password']">Error: {{ errors['password'][0] }}</p>
        </div>

        <div class="form-group">
            <label>Nota:</label>
            <textarea
                class="form-control"
                v-model="deviceData.notes" />
            <p class="error-label" v-if="errors['notes']">Error: {{ errors['notes'][0] }}</p>
        </div>

        <div class="form-group">
            <input
                type="checkbox"
                v-model="deviceData.enabled"
                true-value="1"
                false-value="0"/> Habilitado
        </div>

    </modal>
</template>

<script>
    import Modal from './Modal';

    export default {
        name: "ModalDeviceEdit",
        components: {
            Modal
        },
        props: [
            'modalId',
            'dataInput'
        ],
        data: () =>({
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            errors: [],
            deviceData: {},
            serverError: "",
            deviceNumber: 0,
            newDevice: false,
            close_window: false,
            deviceList: [],
        }),
        computed: {
            getTitle: function () {
                if (!this.newDevice)
                    return "Modificar datos del Monitor #"+this.deviceData.device_number;
                return "Agregar dispositivo";
            }
        },
        methods: {
            makePass: function () {
                var passwordPattern = /[a-zA-Z0-9_@#!?]/;
                var length = 32;
                var _getRandomByte = function()
                {
                    // http://caniuse.com/#feat=getrandomvalues
                    if(window.crypto && window.crypto.getRandomValues)
                    {
                        var result = new Uint8Array(1);
                        window.crypto.getRandomValues(result);
                        return result[0];
                    }
                    else if(window.msCrypto && window.msCrypto.getRandomValues)
                    {
                        var result = new Uint8Array(1);
                        window.msCrypto.getRandomValues(result);
                        return result[0];
                    }
                    else
                    {
                        return Math.floor(Math.random() * 256);
                    }
                };
                this.deviceData.password =  Array.apply(null, {'length': length})
                    .map(function()
                    {
                        var result;
                        while(true)
                        {
                            result = String.fromCharCode(_getRandomByte());
                            if(passwordPattern.test(result))
                            {
                                return result;
                            }
                        }
                    }, this)
                    .join('');
            },
            greaterThanZero: function(value) {
                return ((value !== "") && (value > 0));
            },
            /*
             * updateDevice: Update device info
             */
            updateDevice: function () {
                // Reset errors
                this.errors = [];

                // Reset close window
                this.close_window = false;

                /*
                 * Init the POST data to access devices.update route
                 */

                // upd = UPload Data
                var upd = {};


                // token needed
                upd._token = this.csrf;

                // Load inputs to POST data
                upd.device_number = this.deviceData.device_number;
                upd.name = this.deviceData.name;
                upd.line = this.deviceData.line;
                upd.city = this.deviceData.city;
                upd.province = this.deviceData.province;
                upd.cell_company = this.deviceData.cell_company;
                upd.password = this.deviceData.password;
                upd.notes = this.deviceData.notes;
                upd.enabled = this.deviceData.enabled;

                var uri;
                // if new device => create device (route device.store)
                if (this.newDevice) {
                    uri = '/devices';
                } else {
                    // devices.update's uri (e.g. route devices.update)
                    uri = '/devices/'+this.deviceNumber;
                    // input data to update
                    upd._method = "PUT";
                }

                // try to post data
                this.$http.post(uri, upd).then(
                    response => {
                        window.location.reload();
                    }, response => {
                        if (response.data.errors) {
                            this.errors = response.data.errors;
                        } else {
                            this.serverError = 'Error: No se pudo editar el dispositivo';
                        }
                        console.log(response.data);
                    });
            },
            initComponent () {
                // Reset errors
                this.errors = [];
                this.serverError = "";

                // Reset close window
                this.close_window = false;

                // Reset input data
                this.deviceData.device_number="";
                this.deviceData.name="";
                this.deviceData.line="";
                this.deviceData.city="";
                this.deviceData.province="";
                this.deviceData.cell_company="";
                this.deviceData.password="";
                this.deviceData.notes="";
                this.deviceData.enabled = 1;

                // Device ID must be greater than zero
                if (this.greaterThanZero(this.deviceNumber)) {
                    var uri = '/devices/' + this.deviceNumber;
                    // Try to get device data
                    this.$http.get(uri).then(
                        response => {
                            this.deviceData = response.body.data;
                            this.newDevice = false;
                        }, response => {
                            console.log(response.body); // log error data
                        }
                    );
                } else {
                    var uri = '/devices/missing';
                    // Try to get device data
                    this.$http.get(uri).then(
                        response => {
                            this.deviceList = response.body;
                        }, response => {
                            console.log(response.body); // log error data
                        }
                    );
                    this.newDevice = true;
                }
            }
        },
        //Vue mounted event (e.g. after create and show the component)
        mounted() {
            var self = this;
            $( '#'+this.modalId ).on('show.bs.modal', function () {
                self.deviceNumber = $( '#'+self.dataInput).val();
                self.initComponent();
            });
        }
    }
</script>

<style lang="stylus">
    .error-label
        color red
</style>
