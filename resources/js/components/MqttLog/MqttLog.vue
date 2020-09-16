<template>
    <modal
        :title="title"
        :modalId="modalId"
        :close_enabled="true"
        :show_buttons="false"
        @triggerCancel="close"
    ><textarea
        v-model="log"
        rows="10"
        cols="50"
        ></textarea></modal>
</template>

<script>

    import mqtt from '../../mqtt';
    import Modal from '../modal/Modal';

    export default {
        name: 'MqttLog',
        components: {
            Modal
        },
        props: {
            modalId: {
                type: String,
                default: "0"
            },
            title: {
                type: String,
                default: "Log de MQTT",
            }
        },
    	data() {
            return {
                topic: '#',
                host: 'gicsafe.sofse.gob.ar',
                port: 9001,
                user: 'test',
                pass: 'test00',
    		    log: '',
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    		}
    	},
        methods: {
            initComponent () {
                this.close_window = false;
                this.log = "";
                //this.user = $( '#user').val();
                //this.pass = $( '#pass').val();
                //this.topic = $( '#topic').val();
                var myBuffer = [];
                var str = '-----BEGIN CERTIFICATE-----MIIDtTCCAp2gAwIBAgIUf77QuX5ERWvr8SFkCOhA+juZw8wwDQYJKoZIhvcNAQENBQAwajEXMBUGA1UEAwwOQW4gTVFUVCBicm9rZXIxFjAUBgNVBAoMDU93blRyYWNrcy5vcmcxFDASBgNVBAsMC2dlbmVyYXRlLUNBMSEwHwYJKoZIhvcNAQkBFhJub2JvZHlAZXhhbXBsZS5uZXQwHhcNMjAwMzI1MDYxODQyWhcNMzIwMzIyMDYxODQyWjBqMRcwFQYDVQQDDA5BbiBNUVRUIGJyb2tlcjEWMBQGA1UECgwNT3duVHJhY2tzLm9yZzEUMBIGA1UECwwLZ2VuZXJhdGUtQ0ExITAfBgkqhkiG9w0BCQEWEm5vYm9keUBleGFtcGxlLm5ldDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBALfT2JQ+RI04U6WSJ0ys1GT/ELRfE7oACCk9QYMU7WO1qkczBoOuV9NvquOKgeodHxzOaRzG6M2GtkTm2sHPvtrBi2h0rFbn9l5WfN1vQ065wZO5/PP6wllwIQRyhYIoHMFtv6Uo9iLcBxOCs8ZKVknYiC2rTSI1RW4u4dknpIEQRqOHdJehVQHOvuv+biuz/UeQRVU2u/pLL0ppg7iGJ+YAcZMSB5rR9ytil6qKj5cEm2PBc0J/M/OAVu+MCKzhy/yJXywUpXPABko7IfBP2qZM9mlppr4skjIGJn1es2DxCmigdakncytqkiCjd90XhY7QDiKJRGwWS7tmPFFzBCECAwEAAaNTMFEwHQYDVR0OBBYEFO5Q5wnCjlZw2xh68H4b7vZDUtfwMB8GA1UdIwQYMBaAFO5Q5wnCjlZw2xh68H4b7vZDUtfwMA8GA1UdEwEB/wQFMAMBAf8wDQYJKoZIhvcNAQENBQADggEBADLcletn3vIMvKP30B+JKEjo6/xGfb3Tjhf2nimz9G9qDD0QsCY4AtBqGHZABQy/ClS4wli41JuuhwHUhDxxuBznBU0Y6iKe/Hums8AHjnxC51ftFKH6ZXYGsc8GQNs8i27ExUlITppVmWe1fmQE/Qr79r7Gg/T2onO992ecD8hKTJ6SORB/jOCL5Q7flkkNC8zVbHTQeAzt9n1K46KcfqebGmGV7fL63d91VIrgTu/cqOiDWkyaeyUuKDaEAoj0jWbmvHIrygWsrzdsqw5oSbtzSdW025qIY483GJoeulW6dogZQ1tTdZJi07TPdlu7u0EPNkv+YDqg2nmBh0B8YxI=-----END CERTIFICATE-----';
                var buffer = new Buffer(str, 'utf8');
                for (var i = 0; i < buffer.length; i++) {
                    myBuffer.push(buffer[i]);
                }

                var connection = {
                    host: '127.0.0.1',
                    port: this.port,
                    clientId: 'mqttjs_' + Math.random().toString(16).substr(2, 8),
                    rejectUnauthorized: false,
                    username: this.user,
                    password: this.pass,
                    checkServerIdentity: () => { return null; },
                    keepalive: 10,
                    protocolId: 'MQTT',
                    protocolVersion: 4,
                    clean: true,
                    reconnectPeriod: 1000,
                    connectTimeout: 30 * 1000,
                    //key: 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC309iUPkSNOFOlkidMrNRk/xC0XxO6AAgpPUGDFO1jtapHMwaDrlfTb6rjioHqHR8czmkcxujNhrZE5trBz77awYtodKxW5/ZeVnzdb0NOucGTufzz+sJZcCEEcoWCKBzBbb+lKPYi3AcTgrPGSlZJ2Igtq00iNUVuLuHZJ6SBEEajh3SXoVUBzr7r/m4rs/1HkEVVNrv6Sy9KaYO4hifmAHGTEgea0fcrYpeqio+XBJtjwXNCfzPzgFbvjAis4cv8iV8sFKVzwAZKOyHwT9qmTPZpaaa+LJIyBiZ9XrNg8QpooHWpJ3MrapIgo3fdF4WO0A4iiURsFku7ZjxRcwQhAgMBAAECggEACY/idyuaA0KHz1f/5VQkJDDEXK2JaCZyRjrieoAPLR2ZAuUblKPv+5aWbAmbYmMraCMwgoeyOmakeocLoAM/aT+iEcfn4wlj3lpfWmFcPe8Zq6aECujjTSWxAfs3CKMvg7xCuMOdsgiB8bjdwxXA0UyLIN0FQR9GQkn8A4C1ZOUuReQVs+2xc5K0Z/ife0bzLBrA2GBY43SUc4KzPFFCE0n2bNXcV67Rkt4PLrbLo0jfIoGXiD7Z1cbGsPJ2siNUb41kso59OJw+2AId0RIT64SDoxvuojYn39dvcXFIF/D3dBKr6Zb3jD/2pSNwreKueqGVWbGqLQMbcEadsEorMQKBgQDZ2xuLfzztIoxdf3W2StuTfKdTgeTFoZ9joxHVYfJ1br+nGnsdCspb03cXUcoT/PyZPfk6pq1XfhNcrco/FF34BjSfYUrwulVqREHfvZI6y8TItYdmEp0z8xEO5GfsaOKaolwhlkUTnNRp3EkTaBwjAg7+ruHKBsaZdbsAlelJdQKBgQDYA36jyAKtnZ0/WuJsVwNVg0ZKuoY1cPd/poFNifrseTPlXs9zHsYauowzncQi1T5FVFybS9CA3tBLJ8Mqf6gOUfXPVzPB28ZqveKXyveGDR0UuWXp/9UzSqYlRieMhy7/lD542dIkcM92gjmqHviBA9nEotAdTUJyHKmos/bOfQKBgHctkigB90aSu876R17uHKjWbIMWcBxdimBrePoOlyD+8QFtTyRYHtOVH3U2XSIKJ/V9HbakL/fJFLaCrD1N2wov+uGly+VdShTUOmU3w6M+jexuwh9o127v7ckD0oZV6oFFiLknB6rvBJTU2XUwYxQTsDhLW2G1yzdwXFi5qeFxAoGAWX3BJntDWMgWkFvHuFioIfI72dPCrDs73h+OLCCA2x0vedQ4n5ndOs+ULAItcrzjindz0karf+hPZQN5II84gY5FBvw6jhLN9gTq7jYBLAEcziws46/AlPpVMNYKpnaO6wVwiEK9g6lU2eUDnM4t580/l+5Xx/vtDjd/tkuwcUUCgYEAwaGxYN3fs6CCnyZXwsFdX5nZKUHsMPPjVqzwapoxN4tkowSPhflucuwJ0vxe7aSexyiq5cRI0PIEsn4Kq17+K1SJweyg9Ke1p/25eSLNt9DSeq4nHg7z3SkR1vF2EajYoB+lA7R3nirqU5tAvgFWCZgxE4Q8EeLdUv2m6/c8HI4=',
                    ca: {ca: [ { type: 'Buffer', data: myBuffer} ]},
                }
                console.log(connection);
                mqtt.launch(
                    connection,
                    this.topic,
                    (tp, message) => {
                        this.log += 'tópico: '+tp+'\nmensaje:\n'+message+'\n';
                        this.log += '-----------------------------------------------------\n\n';
                    }
                );
            },
            close: function () {
                $( '#'+this.modalId ).modal('hide');
            }
        },
        mounted() {
            this.errors = [];
            var self = this;
            $( '#'+this.modalId ).on('show.bs.modal', function () {
                self.initComponent();
            });
        }
    }
</script>

<style>
</style>
