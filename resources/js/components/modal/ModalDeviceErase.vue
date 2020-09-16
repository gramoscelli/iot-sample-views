<template>
    <modal :title="getTitle"
           :modalId="modalId"
           :close_window="close_window"
           @triggerOk="eraseDevice"
           @triggerCancel="close_window = true">
        <div class="form-group" v-if="serverError">
            <div class="alert alert-danger">
                <span>{{ serverError }}</span>
            </div>
        </div>

        <h5 class="text-center" >Confirme la eliminación del dispositivo #{{ this.deviceNumber }}</h5><br><br>

    </modal>
</template>

<script>
    import Modal from './Modal';

    export default {
        name: "ModalDeviceErase",
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
            serverError: "",
            deviceNumber: 0,
            close_window: false,
        }),
        computed: {
            getTitle: function () {
                return "Eliminar el dispositivo #"+this.deviceNumber;
            }
        },
        methods: {
            /*
             *
             */
            eraseDevice: function () {
                // Reset errors
                this.errors = [];

                // Reset close window
                this.close_window = false;

                // upd = UPload Data
                var upd = {};

                // device.delete's uri
                var uri = '/devices/'+this.deviceNumber;

                // token needed
                upd._token = this.csrf;

                // input data to update
                upd._method = "DELETE";

                // try to post data
                this.$http.post(uri, upd).then(
                    response => {
                        window.location.reload();
                    }, response => {
                        this.serverError = 'Error: No se pudo eliminar el dispositivo';
                        console.log(response.data);
                    });
            },
            // Init component data
            initComponent() {
                // Reset errors
                this.errors=[];
                this.serverError = "";

                // Reset close window
                this.close_window = false;
            }
        },
        //Vue mounted event (e.g. after create and show the component)
        mounted() {
            var self = this;
            // Event show.bs.modal: it triggers when the modal window is showing
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
