<template>
    <modal :title="getTitle"
           :modalId="modalId"
           :close_window="close_window"
           @triggerOk="updateUser"
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
                v-model="userData.username" />
            <p class="error-label" v-if="errors['username']">Error: {{ errors['username'][0] }}</p>
        </div>

        <div class="form-group" v-if="userId === '0'">
            <label>Clave (temporal):</label>
            <input
                type="text"
                class="form-control"
                v-model="userData.password" />
            <p class="error-label" v-if="errors['password']">Error: {{ errors['password'][0] }}</p>
        </div>

        <div class="form-group">
            <label>Nombre:</label>
            <input
                type="text"
                class="form-control"
                v-model="userData.name" />
            <p class="error-label" v-if="errors['name']">Error: {{ errors['name'][0] }}</p>
        </div>

        <div class="form-group">
            <label>e-mail:</label>
            <input
                type="email"
                class="form-control"
                v-model="userData.email" />
            <p class="error-label" v-if="errors['email']">Error: {{ errors['email'][0] }}</p>
        </div>

        <div class="form-group">
            <label>Celular:</label>
            <input
                type="text"
                class="form-control"
                v-model="userData.cellphone" />
            <p class="error-label" v-if="errors['cellphone']">Error: {{ errors['cellphone'][0] }}</p>
        </div>

        <div class="form-group">
            <input
                type="checkbox"
                v-model="userData.enabled"
                true-value="1"
                false-value="0"/> Habilitado
        </div>

    </modal>
</template>

<script>
    import Modal from './Modal';

    export default {
        name: "ModalUserEdit",
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
                if (this.userId !== '0')
                    return "Modificar datos de "+this.userData.username;
                return "Agregar usuario";
            }
        },
        methods: {
            /*
             * updateUser: Update user info
             */
            updateUser: function () {
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

                // Load inputs to POST data
                upd.username = this.userData.username;
                upd.name = this.userData.name;
                upd.email = this.userData.email;
                upd.cellphone = this.userData.cellphone;
                upd.enabled = this.userData.enabled;

                // if user ID = 0 => create user
                if (this.userId == 0) {
                    uri = '/admin/users';
                    upd.password = this.userData.password;
                    upd.password_confirm = this.userData.password;
                    upd.change_pass=1;
                } else {
                    // input data to update
                    upd._method = "PATCH";
                }


                // try to post data
                this.$http.post(uri, upd).then(response => {
                    window.location.reload();
                }, response => {
                    if (response.data.errors) {
                        this.errors = response.data.errors;
                    } else {
                        this.serverError = 'Error: No se pudo editar el usuario';
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
                this.userData.name = "";
                this.userData.username = "";
                this.userData.email = "";
                this.userData.cellphone = "";
                this.userData.password = "";
                this.userData.enabled = 1;

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
