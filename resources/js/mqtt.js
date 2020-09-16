let mqtt = require('mqtt');
let client = {};

process.env.NODE_TLS_REJECT_UNAUTHORIZED = 0;

export default {
    launch(connection, topic, callback) {
        client = mqtt.connect('wss://'+connection.host, connection);
        client.on('connect', function () {
            client.subscribe(topic, { qos: 0 });
        });
        client.on('message', (topic, message) => {
            console.log('tópico: ', topic);
            console.log('mensaje: ', message);
            callback(topic, message)
        });
        client.on('error', (err) => {
            console.log(err);
            client.end();
        });
        client.on('close', function () {
            console.log(connection.clientId + ' disconnected')
        });
    }
}
