'use strict';
 var cors = require('cors');
var app = require('express')();
 app.use(cors({origin: '*'}));
var server = require('http').Server(app);
var io = require('socket.io')(server);
require('dotenv').config();

var user  = '';
io.on('connection', function(socket) {
    console.log('heloo');
    user = socket.id;


});

var redisPort = process.env.REDIS_PORT;
var redisHost = process.env.REDIS_HOST;
var ioRedis = require('ioredis');
var redis = new ioRedis(redisPort, redisHost);
redis.subscribe('action-channel-one');
redis.subscribe('action-channel-two');

redis.on('message', function (channel, message) {
  message  = JSON.parse(message);
  console.log(channel);
  console.log(message.event);
  console.log(message.data);
  console.log(user);
   


    //to latest connected socket
     // io.to(user).emit(channel + ':' + message.event, message.data);

     //to all connected clients
  io.broadcast.emit(channel + ':' + message.event, message.data);
});

var broadcastPort = process.env.BROADCAST_PORT;
server.listen(broadcastPort, function () {
  console.log('Socket server is running.');
});