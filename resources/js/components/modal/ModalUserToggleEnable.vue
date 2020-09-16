<template>
    <modal :title="getTitle"
           :modalId="modalId"
           :close_window="close_window"
           @triggerOk="toggleUserEnable"
           @triggerCancel="close_window = true">
        <div class="form-group" v-if="serverError">
            <div class="alert alert-danger">
                <span>{{ serverError }}</span>
            </div>
        </div>

        <h5 class="text-center" v-if="userData.enabled == false">Confirme la habilitación del usuario: {{ this.userData.username }}</h5><br><br>
        <h5 class="text-center" v-if="userData.enabled == true">Confirme la deshabilitación del usuario: {{ this.userData.username }}</h5><br><br>

    </modal>
</template>

<script>
    import Modal from './Modal';

    export default {
        name: "ModalUserDisable",
        components: {
            Modal
        },
        props: [ 'modalId' ],
        data: () =>({
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            errors: [],
            userData: {},
            serverError: "",
            userId: 0,
            close_window: false,
        }),
        computed: {
            getTitle: function () {
                if (this.userData.enabled)
                  return "Deshabilitar a "+this.userData.username;
                return "Habilitar a "+this.userData.username;
            }
        },
        methods: {
            /*
             * toggleUserEnable: Toggle user enable state
             */
            toggleUserEnable: function () {
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
                var uri = '/admin/users/'+this.userId

                // token needed
                upd._token = this.csrf;

                // input data to update
                upd._method = "PATCH";
                upd.enabled = (this.userData.enabled)?"0":"1";

                // try to post data
                this.$http.post(uri, upd).then(response => {
                    window.location.reload();
                }, response => {
                    this.serverError = 'Error: No se pudo deshabilitar el usuario';
                    console.log(response.data);
                });
            },
            initComponent() {
                // Reset errors
                this.errors=[];
                this.serverError = "";

                // Reset close window
                this.close_window = false;

                // Reset input data
                this.userData.username = "";
                this.userData.enabled = true;

                // User ID must be greater than zero
                if (this.userId != 0) {
                    // Route to "my" info (users.show)
                    var uri = '/admin/users/' + this.userId;
                    // Try to get user data
                    this.$http.get(uri).then(
                        response => {
                            this.userData = response.body.data;
                        }, response => {
                            console.log(response.body); // log error data
                        }
                    );
                }
            }
        },
        //Vue mounted event (e.g. after create and show the component)
        mounted() {
            var self = this;
            // Event show.bs.modal: it triggers when the modal window is showing
            $( '#'+this.modalId ).on('show.bs.modal', function () {
                self.userId = $( '#userid').val();
                self.initComponent();
            });
        }
    }
</script>

<style lang="stylus">
    .error-label
        color red
</style>
