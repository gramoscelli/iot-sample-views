<template>
    <div
        :id="modalId"
        class="modal"
        :class="fade"
        tabindex="-1"
        role="dialog"
        aria-labelledby="modalTitle"
        aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ title }}</h5>
                    <button
                       class="close"
                       aria-label="Cerrar"
                       v-if="close_enabled"
                       @click="cancel()">
                        <span aria-hidden="true" class="modal-title">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <slot />
                    </div>
                </div>
                <div class="modal-footer" v-if="show_buttons">
                    <div class="panel panel-default">
                        <button
                            type="button"
                            class="btn btn-success"
                            @click="ok()">Aceptar</button>
                        <button
                            type="button"
                            class="btn btn-secondary"
                            v-if="close_enabled"
                            @click="cancel()">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "WindowModal",
        props: {
            title: String,
            modalId: String,
            fade: {
                type: String,
                default: "fade"
            },
            close_enabled: {
                type: Boolean,
                default: true
            },
            show_buttons: {
                type: Boolean,
                default: true
            }
        },
        methods: {
            //
            close_window: function () {
                $( '#'+this.modalId ).modal('hide');
            },
            ok: function () {
                 this.$emit('triggerOk');
            },
            cancel: function () {
                this.$emit('triggerCancel');
            }
        }
    }
</script>

<style lang="stylus">
    .modal-header
        background-color #0f0c29
    .modal-title
        color white
</style>
