var socket = io(socketio_base);

var connected = function () {
    $('#connection_status').text('Connected');
};

var disconnected = function () {
    swal("Not Connected", "Cannot connect to Socket.IO. You will not receive real-time notifications.", "error");
};

socket.on('connect', connected);
socket.on('reconnect', connected);

socket.on('connect_error', disconnected);
socket.on('connect_timeout', disconnected);
socket.on('reconnect_failed', disconnected);

socket.on('reconnecting', function () {
    $('#connection_status').text('Reconnecting');
});

// socket.on('whateverChannel:App\\Events\\WhateverEvent', function () { //action });