/**
 * Created by root on 6/16/14.
 */
//Подключение socket.io
var io = require('socket.io').listen(860);
//Настройки конфигурации socket.io
io.set('heartbeat interval', 5000);

////Подключение MySQL
//var mysql = require('/var/www/html/node_modules/mysql-utilities/node_modules/mysql');
//mysqlUtilities = require('/var/www/html/node_modules/mysql-utilities');
////Настройки конфигурации mysql
//var connection = mysql.createConnection({
//    host:   'localhost',
//    user:   'root',
//    password:   '53Hpontar',
//    database:   'game'
//});
//connection.connect();
//mysqlUtilities.upgrade(connection);
//mysqlUtilities.introspection(connection);

//Переменные
var square_users = new Array();

//Обработка действий при подключении
io.on('connection', function (socket) {
    //Square
    //Подключение
    socket.on('square',function(data,cb){
        //Добавляем в основной массив пользователя
        if(square_users.indexOf(data) == -1){
            square_users[square_users.length] = data;
        }
        socket.join('square');
        socket.nickname = data;
        socket.broadcast.to('square').emit('square_list_update',square_users);
        cb(square_users);
    });
    //Получение сообщения от пользователя
    socket.on('message_to_square',function(data,cb){
        if(data[0] != '/'){
            socket.broadcast.to('square').emit('message_from_square', {char: socket.nickname,
                text: data, type: 'stand'});
            cb({send:'ok'});
        }
        else{

            if(data[1] == '@'){
                socket.broadcast.to('square').emit('message_from_square', {char: socket.nickname,
                    text: data.substring(3), type: 'guild'});
                cb({send:'ok',name:'guild'});
            }
            else {
                if(data[1] == '~'){
                    console.log(data.substring(3));
                    socket.broadcast.to('square').emit('message_from_square', {char: socket.nickname,
                        text: data.substring(3), type: 'party'});
                    cb({send:'ok',name:'party'});
                }
                else{
                    if(data[1] == '$'){
                        socket.broadcast.to('square').emit('message_from_square', {char: socket.nickname,
                            text: data.substring(3), type: '$'});
                        cb({send:'ok',name:'$'});
                    }
                    else{
                        var nick = '', i = 1;
                        while(data[i] != ' '){
                            nick += data[i];
                            i++;
                        }
                        if(square_users.indexOf(nick) != -1){
                            var cl = io.sockets.connected;
                            for (var key in cl) {
                                if(io.sockets.connected[key].nickname == nick){
                                    console.log(io.sockets.connected[key].nickname);
                                    io.to(key).emit('private_message',{char: socket.nickname, text:data.substring(nick.length + 2)});
                                }
                            }
                            cb({send:'ok',name:nick});
                        }
                        else{
                            socket.broadcast.to('square').emit('message_from_square', {char: socket.nickname,
                                text: data});
                            cb({send:'ok'});
                        }
                    }
                }
            }

        }

    });
    //Disconnect
    socket.on('disconnect',function(){
        //Удаляем пользователя из общего массива
        square_users = [];
        var cl = socket.adapter.rooms.square;
        for (var key in cl) {
            if(square_users.indexOf(io.sockets.connected[key].nickname) == -1){
                square_users[square_users.length] = io.sockets.connected[key].nickname;
            }
        }
        io.to('square').emit('square_list_update',square_users);
    });
});