'use strict';
 var cors = require('cors');
var app = require('express')();
 app.use(cors({origin: '*'}));
var server = require('http').Server(app);
var io = require('socket.io')(server);
require('dotenv').config();

var sock = 0;
io.on('connection', function(socket) {
	console.log('hiiiii');
	sock = socket;
	socket.send('hi');

});


var redisPort = process.env.REDIS_PORT;
var redisHost = process.env.REDIS_HOST;
var ioRedis = require('ioredis');
var redis = new ioRedis(redisPort, redisHost);

redis.subscribe('coded_database_action-channel-one');
redis.subscribe('coded_database_action-channel-two');
redis.subscribe('coded_database_action-channel-three');




redis.on('message', function (channel, message) {
  message  = JSON.parse(message);
  io.emit(channel + ':' + message.event, message.data);

});


var broadcastPort = process.env.BROADCAST_PORT;
server.listen(broadcastPort, function () {
  console.log('Socket server is running.');
});