<template>
    <modal :title="getTitle"
           :modalId="modalId"
           :close_window="close_window"
           @triggerOk="updatePwd"
           @triggerCancel="close_window = true">
        <div class="form-group" v-if="serverError">
            <div class="alert alert-danger">
                <span>{{ serverError }}</span>
            </div>
        </div>

        <div class="form-group">
            <label>Usuario:</label>
            <input
                type="text"
                class="form-control"
                v-model="userData.username"
                readonly />
            <p class="error-label" v-if="errors['username']">Error: {{ errors['username'][0] }}</p>
        </div>

        <div class="form-group">
            <label>Clave (temporal):</label>
            <input
                type="text"
                class="form-control"
                v-model="new_password" />
            <p class="error-label" v-if="errors['password']">Error: {{ errors['password'][0] }}</p>
        </div>
    </modal>
</template>

<script>
    import Modal from './Modal';

    export default {
        name: "ModalUserPwdReset",
        components: {
            Modal
        },
        props: {
            title: {
                type: String,
                default: "Cambio de Clave"
            },
            modalId: {
                type: String,
                default: "0"
            }
        },
        data: () =>({
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            errors: [],
            userData: {},
            serverError: "",
            new_password: "",
            userId: 0,
            close_window: false
        }),
        computed: {
            getTitle: function () {
                return "Reiniciar la clave de "+this.userData.username;
            }
        },
        methods: {
            updatePwd: function() {
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
                var uri = '/admin/users/'+this.userId;

                // token needed
                upd._token = this.csrf;

                // input data to update
                upd._method = "PATCH";

                // Load inputs to POST data
                upd.password = this.new_password;
                upd.password_confirm = this.new_password;
                upd.change_pass=1;

                // try to post data
                this.$http.post(uri, upd).then(response => {
                    window.location.reload();
                }, response => {
                    if (response.data.errors) {
                        this.errors = response.data.errors;
                    } else {
                        this.serverError = 'Error: No se pudo crear el usuario';
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
                this.userData.username = "";

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
        mounted() {
            this.errors = [];
            var self = this;
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
