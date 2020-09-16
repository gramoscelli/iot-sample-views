<template>
    <modal  :title="title"
            :modalId="modalId"
            :close_enabled="close_enabled"
            :close_window="close_window"
            @triggerOk="saveInfo"
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
        </div>

        <div class="form-group">
            <label>Clave Actual:</label>
            <input
                type="password"
                class="form-control"
                v-model="password" />
            <p class="error-label" v-if="pwd_error">Error: La clave ingresada es incorrecta</p>
        </div>

        <div class="form-group">
            <label>Clave Nueva:</label>
            <input
                type="password"
                class="form-control"
                v-model="new_password" />
            <p class="error-label" v-if="errors['password']">Error: {{ errors['password'][0] }}</p>
        </div>

        <div class="form-group">
            <label>Repetir Clave Nueva:</label>
            <input
                type="password"
                class="form-control"
                v-model="new_password_confirm" />
            <p class="error-label" v-if="errors['password_confirm']">Error: {{ errors['password_confirm'][0] }}</p>
        </div>
    </modal>
</template>

<script>
    import Modal from './Modal';
    const bcrypt = require('bcryptjs');

    export default {
        name: "ModalUserPwdChange",
        components: {
            Modal
        },
        props: {
            modalId: {
                type: String,
                default: "0"
            },
            userId: {
                type: Number,
                required: true,
                default: 0
            },
            close_enabled: {
                type: Boolean,
                default: true
            }
        },
        data: function() {
            return {
                title: "Cambiar mi clave",
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                errors: [],
                userData: {},
                serverError: "",
                pwd_error: "",
                password: "",
                new_password: "",
                new_password_confirm: "",
                close_window: false
            }
        },
        methods: {
            /*
             * saveInfo: Saves the new password
             */
            saveInfo: function() {
                // Reset errors
                this.errors = [];
                this.serverError = "";
                this.pwd_error = "";

                // Test 1: Is the actual password correct?

                if (!bcrypt.compareSync(this.password, this.userData.password)) {
                    this.pwd_error = "true";
                    return false;
                }


                // Test 2: Is the new password equal to the actual password?
                if (this.password === this.new_password) {
                    this.errors['password'] = ["Escriba una clave diferente a la actual"];
                    return false;
                }
                /*
                 * Init the POST data to access users.update route
                 */

                // upd = UPload Data
                var upd = {};

                // users.update's uri (e.g. route users.update)
                var uri = '/admin/users/' + this.userId;

                // token needed
                upd._token = this.csrf;

                // input data to update
                upd._method = "PATCH";
                upd.password = this.new_password;
                upd.password_confirm = this.new_password_confirm;
                upd.change_pass = 0;

                // try to post data
                this.$http.post(uri, upd).then(
                    response => {
                        //console.log(response);
                        this.close_window = true;
                        return true;
                    }, response => {
                        if (response.data.errors) {
                            this.errors = response.data.errors;
                        } else {
                            this.serverError = 'Error: No se pudo cambiar la clave';
                        }
                        console.log(response);
                        return false;
                    });
            },

            // initComponent: Init initial state of the component (event show.bs.modal, see below)
            initComponent: function() {
                // Reset errors
                this.errors = [];
                this.serverError = "";
                this.pwd_error = "";

                // Reset inputs
                this.password = "";
                this.new_password = "";
                this.new_password_confirm = "";

                // Reset "close_window"
                this.close_window = false;

                // Reset username
                this.userData.username = "";

                // User ID must be greater than zero
                if (this.userId != 0) {
                    // Route to "my" info (users.show)
                    var uri = '/admin/users/' + this.userId;
                    // Try to get user data
                    this.$http.get(uri).then(
                        response => {
                            this.userData = response.body.data;
                            //console.log(this.userData);
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
                self.initComponent();
            });
        }
    }
</script>

<style lang="stylus">
    .error-label
        color red
</style>
