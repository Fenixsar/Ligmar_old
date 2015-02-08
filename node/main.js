/**
 * Created by root on 6/16/14.
 */
//Подключение socket.io
var io = require('socket.io').listen(852);
//Настройки конфигурации socket.io
io.set('heartbeat interval', 10000);

//Подключение логера
var log4js = require('log4js');
var logger = log4js.getLogger();
logger.setLevel('info');

//Подключение MySQL
var mysql = require('/var/www/node_modules/mysql-utilities/node_modules/mysql');
mysqlUtilities = require('/var/www/node_modules/mysql-utilities');

//Настройки конфигурации mysql
var connection = mysql.createConnection({
    host:   'localhost',
    user:   'root',
    password:   '53Hpontar',
    database:   'game'
});
connection.connect();
mysqlUtilities.upgrade(connection);
mysqlUtilities.introspection(connection);

//Переменные
var users = new Array();
var battles = new Array();
var mobs = new Array();


//Удар
function hit(target,char,lh){
    var timee = new Date();
    var time = timee.getTime();
    //Получаем шанс попадания
    var chance_hit = char['accuracy']/target['dodge']*100;
    if (chance_hit > 95) chance_hit = 95;

    if (chance_hit >= Math.floor((Math.random()*100)+1)){
        var absorp = target['def']/(target['def'] + char['level']*40);
        var absorp_m = target['resist']/(target['resist'] + char['level']*40);

        //Проверяем, есть ли доп. магический урон.
        if(char['dmg_mag'] != 0 && char['dmg_mag'] != undefined){
            var dmg_mag_plus = char['dmg_mag']*(1 - absorp_m);
        }
        else{
            var dmg_mag_plus = 0;
        }

        //Проверяем тип урона у моба
        var ph = char['dmg_type'];
        var mag = 1 - char['dmg_type'];

        var dmg_min = char['dmg_min']*(1 - absorp)*ph + char['dmg_min']*(1 - absorp_m)*mag + dmg_mag_plus;
        var dmg_max = char['dmg_max']*(1 - absorp)*ph + char['dmg_max']*(1 - absorp_m)*mag + dmg_mag_plus;
        var dmg = Math.floor((Math.random()*(dmg_max - dmg_min + 1))+dmg_min);
        var strike = undefined;
        if(char['strike'] >= Math.floor((Math.random()*100)+1)){
            strike = 1;
            dmg = dmg * 2;
        }
        else{
            strike = 0;
        }
        if(time - lh < char['aspeed']*1000){
            if(time - lh < char['aspeed']*500){
                if(time - lh < 500){
                    dmg = 0;
                }
                else{
                    dmg = Math.round(dmg/10);
                }
            }
            else{
                dmg = Math.round(dmg/2);
            }
        }
        if(dmg < 1){
            dmg = 1;
        }
        var cb = {
            hit: 1,
            dmg: dmg,
            strike: strike,
            last_hit: time
        }
        return cb;
    }
    else{
        var cb = {
            hit: 0,
            last_hit: time
        }
        return cb;
    }
}


io.on('connection', function (socket) {
    var name = undefined;
    var hp_reg = null;
    var mp_reg = null;

    var list_group_invate = Array();

    //-----------------------Main-------------------------------\\
    socket.on('name',function(data,callback){
        name = data.nick;
        socket.nickname = data.nick;
        //Добавляем в основной массив пользователя
        if(data.nick != undefined){
            if(users.indexOf(data.nick) == -1){
                users[users.length] = data.nick;
                users[data.nick] = {nick: data.nick,s_id:socket.id,battle:new Object(),main_status:{bottles:new Object(),skills:new Array(),battle:0}};

                connection.query('SELECT * FROM characters WHERE name ="' + [data.nick] + '"',
                    function(err, row){
                        row = row[0];
                        users[data.nick].location = row['loc'];
                        users[data.nick].character = row;

                        var skills = new Array();
                        var re = /\s*,\s*/;
                        skills = row['skills'].split(re);
                        var k = 0;
                        while (skills[k]){
                            var s = new Array();
                            var re = /\s*-\s*/;
                            s = skills[k].split(re);
                            connection.query('SELECT id_skill,lvl,cd,mana FROM skills_lvl WHERE id_skill = ' + s[0] + ' AND lvl = ' + s[1],
                                function(err, row){
                                    users[name].main_status.skills[users[name].main_status.skills.length] = {id:row[0].id_skill,lvl:row[0].lvl,cd:row[0].cd,cd_l:0,mana:row[0].mana};
                                });
                            k++;
                        }

                        users[name].main_status.bottles = {hp:row['hp_b'],mp:row['mp_b'],hp_c:0,mp_c:0};
                        users[name].main_status.aspeed = row['aspeed'];

                        //Получаем данные с аккаунта
                        connection.queryRow('SELECT * FROM users WHERE id = ?', [row['user']],
                            function(err, row){
                                users[data.nick].character['gold'] = row['gold'];
                                users[data.nick].character['box'] = row['box'];
                        });

                        //Подключаемся к чату локации
                        socket.emit('chat',connectToChat());

                        //Устанавливаем время для регена
                        var t = new Date();
                        users[name].character['reg_time'] = t.getTime();

                        //Если отконнектился мертвым
                        if(users[data.nick].character['death'] != null){
                            users[data.nick].death = users[data.nick].character['death'];
                            callback({name: 'death',value:users[name].death});
                        }
                        else{
                            callback({name: 'hp_mp',value:hp_mp(data.nick)
                            });
                        }

                        io.to(users[data.nick].s_id).emit('char_status',users[name].main_status);
                    });

                logger.info('Новый уникальный пользователь: ' + data.nick);
                logger.info('Всего пользователей: ' + users.length + ', а конкретно: ' + users);
            }
            else{
                var s = users[data.nick].s_id;
                if(io.sockets.connected[s]){
                    logger.warn('Пользователь  ' + data.nick + ' пытался открыть второе окно!');
                    io.to(s).emit('double_window',data.nick);
                    //io.sockets.connected[s].disconnect();
                }

                users[data.nick].s_id = socket.id;

                //Подключаемся к чату локации
                socket.emit('chat',connectToChat());

                //Если мертв
                if(users[data.nick].death){
                    callback({name: 'death',value:users[name].death});
                }
                else{
                    var cb = {name: 'hp_mp',
                        value:hp_mp(data.nick),
                            location: users[data.nick].location};
                    if(users[data.nick].main_status.battle){
                        cb.battle = 1;
                        callback(cb);
                    }
                    else{
                        callback(cb);
                    }
                }
                io.to(users[data.nick].s_id).emit('char_status',users[name].main_status);

            }
            io.emit('online',users.length);
        }
        else{
            socket.disconnect();
        }
    });
    //Disconnect
    socket.on('disconnect',function(){
        //Удаляем пользователя из общего массива
        if(name != undefined){
            delete_user(name);
        }
        //Очищаем память
        delete socket.adapter.rooms[socket.id];
    });
    //Ping
    socket.on('ping',function(pong){
        pong();
    });
    //UpdateAllStats
    socket.on('updateAllStats', function(data){
        connection.queryRow('SELECT * FROM characters WHERE name = ?', [name],
            function(err, row){
                users[name].character['level'] = row['level'];
                users[name].character['str_self'] = row['str_self'];
                users[name].character['dex_self'] = row['dex_self'];
                users[name].character['vit_self'] = row['vit_self'];
                users[name].character['int_self'] = row['int_self'];
                users[name].character['str'] = row['str'];
                users[name].character['dex'] = row['dex'];
                users[name].character['vit'] = row['vit'];
                users[name].character['intel'] = row['intel'];
                users[name].character['health_max'] = row['health_max'];
                users[name].character['health_reg'] = row['health_reg'];
                users[name].character['mana_max'] = row['mana_max'];
                users[name].character['mana_reg'] = row['mana_reg'];
                users[name].character['dmg_min'] = row['dmg_min'];
                users[name].character['dmg_max'] = row['dmg_max'];
                users[name].character['dmg_type'] = row['dmg_type'];
                users[name].character['dmg_mag'] = row['dmg_mag'];
                users[name].character['dmg_strike'] = row['dmg_strike'];
                users[name].character['strike'] = row['strike'];
                users[name].character['aspeed'] = row['aspeed'];
                users[name].character['def'] = row['def'];
                users[name].character['resist'] = row['resist'];
                users[name].character['accuracy'] = row['accuracy'];
                users[name].character['dodge'] = row['dodge'];

                if(users[name].character['health'] > users[name].character['health_max'])
                    users[name].character['health'] = users[name].character['health_max'];
                if(users[name].character['mana'] > users[name].character['mana_max'])
                    users[name].character['mana'] = users[name].character['mana_max'];

                io.to(users[name].s_id).emit('hp_mp',hp_mp(name));
            });
    });


    //Группа
    //socket.on('group',function(nickname,callback){
    //    if(name == nickname){
    //        logger.warn(name + ' пригласил сам себя в группу!');
    //        callback(-2);
    //    }
    //    else{
    //        logger.info(name + ' пригласил '+ nickname + ' в группу!');
    //        var time  = new Date();
    //        if(list_group_invate[nickname] == undefined || list_group_invate[nickname] + 30000 < time.getTime()){
    //            var all_users = io.sockets.connected;
    //            var sent = false;
    //            for (var s_id in all_users){
    //                if(all_users[s_id].nickname == nickname){
    //                    io.to(s_id).emit('invite_to_group',name);
    //                    sent = true;
    //                }
    //            }
    //            if(sent){
    //                list_group_invate[nickname] = time.getTime();
    //            }
    //            else{
    //                callback(0);
    //            }
    //        }
    //        else{
    //            logger.info(name + ' пытается пригласить '+ nickname + ' в группу чаще 30 сек!');
    //            callback(-1);
    //        }
    //    }
    //});
    //socket.on('accept_group',function(nickname){
    //    logger.info(name + ' принял приглашение '+ nickname + ' в группу!');
    //    var all_users = io.sockets.connected;
    //    for (var s_id in all_users){
    //        if(all_users[s_id].nickname == nickname){
    //            if(all_users[s_id].group == undefined){
    //                console.log(all_users[s_id]);
    //            }
    //            else{
    //                console.log('222');
    //            }
    //            break;
    //        }
    //    }
    //
    //});
    //socket.on('reject_group',function(nickname){
    //    logger.info(name + ' отклонил приглашение '+ nickname + ' в группу!');
    //    var all_users = io.sockets.connected;
    //    for (var s_id in all_users){
    //        if(all_users[s_id].nickname == nickname){
    //            io.to(s_id).emit('invite_error',{type:'reject',nick:name});
    //        }
    //    }
    //});
    //socket.on('double_invite',function(nickname){
    //    var all_users = io.sockets.connected;
    //    for (var s_id in all_users){
    //        if(all_users[s_id].nickname == nickname){
    //            io.to(s_id).emit('invite_error',{type:'double'});
    //        }
    //    }
    //});



    //---------------------Chat--------------------------------------\\
    socket.on('change_chat',function(location,callback){
        if(location != users[name].location){
            if(location == 'home'){
                if(users[name].character['loc'] != users[name].location){
                    socket.leave(users[name].location);
                    getRoomUser(users[name].location);
                    users[name].location = users[name].character['loc'];
                    socket.join(users[name].character['loc']);
                    callback(getRoomUser(users[name].character['loc']));
                    io.to(users[name].s_id).emit('system_message',{type:'change_chat',par:users[name].character['loc']});
                }
            }
            else{
                socket.leave(users[name].location);
                getRoomUser(users[name].location);
                users[name].location = location;
                socket.join(location);
                callback(getRoomUser(location));
                io.to(users[name].s_id).emit('system_message',{type:'change_chat',par:location});
            }
        }
        else{
            //logger.info('Локация не изменилась!');
        }
    });
    //Получение сообщения от пользователя
    socket.on('message_to_chat',function(data,cb){
        //Проверка на количество символов
        if(data.length > 150){
            logger.warn('Пользователь  ' + name + ' пытался отправить превышающее лимит количество символов!');
            cb({send:'no',message:'Сообщение превышает допустимый лимит символов!'})
        }

        if(data[0] != '/'){
            socket.broadcast.to(users[name].location).emit('message_from_chat', {char: socket.nickname,
                text: data, type: 'stand'});
            cb({send:'ok'});
        }
        else{

            if(data[1] == '@'){
                socket.broadcast.to('square').emit('message_from_chat', {char: socket.nickname,
                    text: data.substring(3), type: 'guild'});
                cb({send:'ok',name:'guild'});
            }
            else {
                if(data[1] == '~'){
                    console.log(data.substring(3));
                    socket.broadcast.to(users[name].location).emit('message_from_chat', {char: socket.nickname,
                        text: data.substring(3), type: 'party'});
                    cb({send:'ok',name:'party'});
                }
                else{
                    if(data[1] == '$'){
                        socket.broadcast.to(users[name].location).emit('message_from_chat', {char: socket.nickname,
                            text: data.substring(3), type: '$'});
                        cb({send:'ok',name:'$'});
                    }
                    else{
                        var nick = '', i = 1;
                        while(data[i] != ' '){
                            nick += data[i];
                            i++;
                        }
                        if(users.indexOf(nick) != -1){
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
                            socket.broadcast.to(users[name].location).emit('message_from_chat', {char: socket.nickname,
                                text: data});
                            cb({send:'ok'});
                        }
                    }
                }
            }

        }

    });
    //Запрос местоположения
    socket.on('get_location',function(data,callback){
        callback({name:users[name].location,count:'0'});
    });

    //------------------------Links-----------------------------------\\
    socket.on('get_character',function(data,callback){
        if(data == 0){
            connection.queryRow('SELECT * FROM storage WHERE id = ?', [users[name].character['id']],
                function(err, row){
                    users[name].character['wood'] = row['wood'];
                    users[name].character['ore'] = row['ore'];
                    users[name].character['thread'] = row['thread'];
                    users[name].character['leather'] = row['leather'];
                    callback(users[name].character);
                })
        }
    });


    //------------------------БОЙ--------------------------------------\\
    socket.on('great_battle_with_mob',function(battle_id,callback){
        if(users[name].main_status.battle == 0){
            checkLife(name);
            if(battle_id != 'last'){
                users[name].battle.last = battle_id;
            }
            else{
                if(users[name].battle.last == undefined){
                    io.to(users[name].s_id).emit('battle_undefined',name);
                    io.sockets.connected[users[name].s_id].disconnect();
                }
            }
            users[name].main_status.battle = 1;
            //Меняем реген в зависимости от состояния персонажа
            io.to(users[name].s_id).emit('hp_mp',hp_mp(name));

            users[name].battle.id = randWDclassic(20);
            battles[users[name].battle.id] = new Object();

            battles[users[name].battle.id].status = 0;
            battles[users[name].battle.id].type = 'mob';
            battles[users[name].battle.id].round_n = 1;
            battles[users[name].battle.id].round = new Array();
            battles[users[name].battle.id].round[0] = new Array();
            battles[users[name].battle.id].round[0][battles[users[name].battle.id].round[0].length] = name;

            battles[users[name].battle.id].round[1] = new Array();

            connection.queryRow(
                'SELECT * FROM battles_with_mobs WHERE id = ?', [users[name].battle.last],
                function(err, row){
                    if(row){
                        battles[users[name].battle.id].rounds = row['rounds'];
                        battles[users[name].battle.id].list_of_mobs = new Array();
                        for(var i = 0;i < row['rounds']; i++){
                            if(row['round' + (i + 1)] != null){
                                battles[users[name].battle.id].list_of_mobs[i] = row['round' + (i + 1)];
                            }
                        }
                        greatRound(battles[users[name].battle.id].list_of_mobs,1);
                    }
                    else{
                        logger.warn('Пользователь ' + name + ' пытался создать недоступный бой!');
                    }
                    callback();
                });
        }
        else{
            logger.warn('Пользователь ' + name + ' пытается создать еще один бой!');
        }

    });
    //---------------------------
    socket.on('get_battle',function(data,callback){
        if(users[name].character && users[name].main_status.battle == 1){
            change_target(battles[users[name].battle.id],users[name].main_status.target.group,-1);
            callback();
        }
    });
    //------------------------Действия---------------------------------\\
    socket.on('hit_target',function(data,callback){
        checkLife(name);
        if(users[name].last_hit == undefined){
            users[name].last_hit = 0;
        }
        if(battles[users[name].battle.id].round){
            if(battles[users[name].battle.id].status == 0){
                battles[users[name].battle.id].status = 1;
                if(battles[users[name].battle.id].type = 'mob'){
                    //Запускаем мобов
                    for(var i = 0; i < battles[users[name].battle.id].round[1].length; i++){
                        battles[users[name].battle.id].round[1][i].target = name;
                        lifeMob(users[name].battle.id,i);
                    }
                }

            }

            var hit_to = hit(battles[users[name].battle.id].round[users[name].main_status.target.group][users[name].main_status.target.number],users[name].character,users[name].last_hit);
            users[name].last_hit = hit_to.last_hit;
            hit_to.last_hit = undefined;

            io.to(socket.id).emit('chat_log',{a:name,target:battles[users[name].battle.id].round[users[name].main_status.target.group][users[name].main_status.target.number].name +
                '[' + battles[users[name].battle.id].round[users[name].main_status.target.group][users[name].main_status.target.number].level + ']',hit:hit_to});

            if(hit_to.hit == 1){
                battles[users[name].battle.id].round[users[name].main_status.target.group] = hp_mob(battles[users[name].battle.id].round[users[name].main_status.target.group]);

                battles[users[name].battle.id].round[users[name].main_status.target.group][users[name].main_status.target.number]['health'] -= hit_to.dmg;
                hit_to.hp_target = battles[users[name].battle.id].round[users[name].main_status.target.group][users[name].main_status.target.number]['health'];

                if(hit_to.hp_target <= 0){
                    hit_to.hp_target = 0;
                    battles[users[name].battle.id].round[users[name].main_status.target.group][users[name].main_status.target.number]['health'] = 0;

                    //Останавливаем моба
                    clearInterval(mobs[battles[users[name].battle.id].round[users[name].main_status.target.group][users[name].main_status.target.number].mob_att]);
                    //Награда
                    io.to(socket.id).emit('chat_drop',{type: 'gold', amount:getGold(battles[users[name].battle.id].round[users[name].main_status.target.group][users[name].main_status.target.number])});
                    ////Опыт
                    io.to(socket.id).emit('chat_drop',{type: 'exp', amount:updateExp(getExpFromMob(battles[users[name].battle.id].round[users[name].main_status.target.group][users[name].main_status.target.number]))});
                    ////Дроп
                    Drop(battles[users[name].battle.id].round[users[name].main_status.target.group][users[name].main_status.target.number],1,name);

                    //Проверяем, есть ли еще живые мобы в данном раунде
                    var check = true;
                    for(var i = 0; i < battles[users[name].battle.id].round[users[name].main_status.target.group].length;i++){
                        if(battles[users[name].battle.id].round[users[name].main_status.target.group][i]['health'] > 0){
                            check = false;
                        }
                    }
                    if(check){
                        //Здесь должна быть функция завершения раунда
                        if(users[name].battle.rounds == users[name].battle.round_n){
                            hit_to.f_b = 1;
                            finishBattle(users[name].battle.id);
                            users[name].battle = {type:'none',last:users[name].battle.last};
                            users[name].main_status.battle = 0;
                            //Меняем реген в зависимости от состояния персонажа
                            io.to(users[name].s_id).emit('hp_mp',hp_mp(name));
                        }
                        else{
                            users[name].battle.status = 0;
                            users[name].battle.target = 0;
                            users[name].battle.round_n++;
                            users[name].battle.round = new Array();

                            hit_to.f_r = 1;

                            greatRound(users[name].battle.list_of_mobs,users[name].battle.round_n,1);
                        }
                    }
                    else{
                        change_target(battles[users[name].battle.id],users[name].main_status.target.group,-1);
                        hit_to.battle = users[name].battle;
                    }

                }
            }
            callback(hit_to);
        }
        else{
            logger.warn('Пользователь ' + name + ' пытается ударить по несуществующему врагу!');
            io.to(users[name].s_id).emit('battle_undefined',name);
        }

    });
    socket.on('change_target',function(data,callback){
        change_target(battles[users[name].battle.id],users[name].main_status.target.group,users[name].main_status.target.number);
        callback();
    });
    socket.on('action_health_bottle',function(data){
        if(users[name].main_status.bottles.hp_c == 0 && users[name].character['health'] != users[name].character['health_max'] && users[name].character['health'] > 0){
            var i = 0;
            var hp_plus = setInterval(function(){
                hp_mp(name);
                if(users[name].character['health'] != 0){
                    var hp = users[name].character['health'] + users[name].character['health_max']/100*12;
                    if (hp > users[name].character['health_max']){
                        users[name].character['health'] = users[name].character['health_max'];
                    }
                    else{
                        users[name].character['health'] = hp;
                    }
                    io.to(users[name].s_id).emit('hp_mp',hp_mp(name));
                }
                else{
                    clearInterval(hp_plus);
                }
                i++;
                if(i == 5){
                    clearInterval(hp_plus);
                }
            },1000);
            users[name].main_status.bottles.hp--;
            users[name].main_status.bottles.hp_c = 60;
            var I = setInterval(function(){
                users[name].main_status.bottles.hp_c--;
                if(users[name].main_status.bottles.hp_c == 0){
                    clearInterval(I);
                    io.to(users[name].s_id).emit('char_status',users[name].main_status);
                }
            },1000);
            io.to(users[name].s_id).emit('char_status',users[name].main_status);
        }
    });
    socket.on('action_mana_bottle',function(data){
        if(users[name].main_status.bottles.mp_c == 0 && users[name].character['mana'] != users[name].character['mana_max'] && users[name].character['health'] > 0){
            var i = 0;
            var mp_plus = setInterval(function(){
                hp_mp(name)
                if(users[name].character['health'] != 0) {
                    var mp = users[name].character['mana'] + users[name].character['mana_max'] / 100 * 12;
                    if (mp > users[name].character['mana_max']) {
                        users[name].character['mana'] = users[name].character['mana_max'];
                    }
                    else {
                        users[name].character['mana'] = mp;
                    }
                    io.to(users[name].s_id).emit('hp_mp', hp_mp(name));
                }
                else{
                    clearInterval(mp_plus);
                }
                i++;
                if(i == 5){
                    clearInterval(mp_plus);
                }
            },1000);
            users[name].main_status.bottles.mp--;
            users[name].main_status.bottles.mp_c = 60;
            var I = setInterval(function(){
                users[name].main_status.bottles.mp_c--;
                if(users[name].main_status.bottles.mp_c == 0){
                    clearInterval(I);
                    io.to(users[name].s_id).emit('char_status',users[name].main_status);
                }
            },1000);
            io.to(users[name].s_id).emit('char_status',users[name].main_status);
        }
    });
    socket.on('action_skill',function(data){
        for(var i = 0; i < users[name].main_status.skills.length;i++){
            if(users[name].main_status.skills[i].id == data){
                var need = i;
            }
        }
        if (need != undefined && !users[name].main_status.skills[need].cd_l && users[name].character['mana'] >= users[name].main_status.skills[need].mana && users[name].character['health'] > 0){
            //Получаем данные скила
            connection.query('SELECT * FROM skills_lvl WHERE id_skill = ' + users[name].main_status.skills[need].id + ' AND lvl = ' + users[name].main_status.skills[need].lvl,
                function(err, row){
                    row = row[0];
                    var timer = setTimeout(function(){

                    },row['active']);
                    var hit_to = hit(users[name].battle.round[users[name].battle.target],users[name].character,0);

                    io.to(socket.id).emit('chat_log',{a:name,target:users[name].battle.round[users[name].battle.target].name +
                    '[' + users[name].battle.round[users[name].battle.target].level + ']',hit:hit_to});
                });
            //Таймер активации

            users[name].main_status.skills[need].cd_l = users[name].main_status.skills[need].cd;
            users[name].character['mana'] -= users[name].main_status.skills[need].mana;
            logger.warn(users[name].main_status);

            io.to(users[name].s_id).emit('hp_mp',hp_mp(name));
            io.to(users[name].s_id).emit('char_status',users[name].main_status);
            var I = setInterval(function(){
                users[name].main_status.skills[need].cd_l--;
                if(users[name].main_status.skills[need].cd_l == 0){
                    clearInterval(I);
                    logger.warn(users[name].main_status);
                    io.to(users[name].s_id).emit('char_status',users[name].main_status);
                }
            },1000);


        }
    });





    socket.on('death_info',function(data,callback){
        var t = new Date();
        callback(users[name].death - t.getTime());
    });
    socket.on('resurrection',function(data,callback){
        if(users[name].character['health'] == 0){
            var t = new Date();
            switch (data){
                case 'self':
                    users[name].main_status.battle = 0;
                    //Меняем реген в зависимости от состояния персонажа
                    io.to(users[name].s_id).emit('hp_mp',hp_mp(name));

                    users[name].death = t.getTime() + users[name].character['level']*1000;
                    var exp = (users[name].character['exp_need']-users[name].character['exp_was'])/100*5;
                    //Опыт
                    io.to(socket.id).emit('chat_drop',{type: 'exp', amount:updateExp(-Math.round(exp))});
                    callback('heaven');
                    break;
                case 'res':
                    if(users[name].death <= t.getTime()){
                        users[name].death = null;
                        users[name].character['health'] = users[name].character['health_max']/10;
                        users[name].character['mana'] = users[name].character['mana_max']/10;
                        users[name].character['reg_time'] = t.getTime();
                        callback(hp_mp(name));
                    }
                    return;

            }
        }

    });
    socket.on('leave_battle',function(type,callback){
        users[name].main_status.battle = 0;
        //Меняем реген в зависимости от состояния персонажа
        io.to(users[name].s_id).emit('hp_mp',hp_mp(name));
        io.to(users[name].s_id).emit('char_status',users[name].main_status);

        var loc = "";
        if(users[name].battle.id != undefined){
            if(battles[users[name].battle.id].round && battles[users[name].battle.id].type == 'mob'){
                loc = battles[users[name].battle.id].round[1][0].loc;
            }
            else{
                loc = '/';
            }
            finishBattle(users[name].battle.id);
            users[name].battle = {type:'none',last:users[name].battle.last};
        }
        callback(loc);
    });//??????????????????????Куча нюансов


    //Chat function
    function connectToChat(){
        socket.join(users[name].location);
        return getRoomUser(users[name].location);
    }
    function getRoomUser(name_room){
        var room_users = Array();
        var cl = socket.adapter.rooms[name_room];
        for (var key in cl) {
            room_users[room_users.length] = io.sockets.connected[key].nickname;
        }
        socket.broadcast.to(name_room).emit('chat_list_update',room_users);
        return room_users;
    }

    //Здоровье и мана персонажа
    function hp_mp(name){
        var time = new Date();

        if(users[name].character['health'] > 0){
            if(users[name].main_status.battle){
                var ex = 1;
            }
            else{
                var ex = 2;
            }

            users[name].character['health'] = users[name].character['health'] + users[name].character['health_reg'] *
            (time.getTime() - users[name].character['reg_time'])/1000*ex;
            if(users[name].character['health'] > users[name].character['health_max']){
                users[name].character['health'] = users[name].character['health_max'];
            }

            users[name].character['mana'] = users[name].character['mana'] + users[name].character['mana_reg'] *
            (time.getTime() - users[name].character['reg_time'])/1000*ex;
            if(users[name].character['mana'] > users[name].character['mana_max']){
                users[name].character['mana'] = users[name].character['mana_max'];
            }
        }

        users[name].character['reg_time'] = time.getTime();

        return {hp:users[name].character['health'],
            hp_max:users[name].character['health_max'],
            hp_reg:users[name].character['health_reg'],
            mp:users[name].character['mana'],
            mp_max:users[name].character['mana_max'],
            mp_reg:users[name].character['mana_reg']}
    }
    //Здоровье моба
    function hp_mob(stats){
        var time = new Date();

        for(var i = 0; i < stats.length; i++){
            if(stats[i]['health'] > 0){
                stats[i]['health'] = stats[i]['health'] + stats[i]['health_reg'] * (time.getTime() - stats[i]['reg_time'])/1000;
                if(stats[i]['health'] > stats[i]['health_max']){
                    stats[i]['health'] = stats[i]['health_max'];
                }
            }

            stats[i]['reg_time'] = time.getTime();

        }
        return stats;

    }
    //Удаление пользователя
    function delete_user(nick){
        var last_chat = users[nick].chat;
        users[nick].chat = undefined;

        var del_timer = null;
        var count = 0;
        var old_s_id = users[nick].s_id;

        del_timer = setInterval(function(){
            count ++;
//            console.log('До отключения пользователя ' + nick+ ' осталось: ' + (11-count) + 'сек!');\
            if(count == 9){
                var all_user = io.sockets.connected;
                var del = true;
                for (var s_id in all_user){
                    if(all_user[s_id].nickname == nick){
                        del = false;
                        break;
                    }
                }
                if(del){
                    //Убираем реген
                    clearInterval(hp_reg);
                    clearInterval(mp_reg);

                    users.splice(users.indexOf(nick),1);
                    logger.info('Пользователь отсоеденился: ' + nick);
                    logger.info('Всего пользователей: ' + users.length + ', а конкретно: ' + users);

                    //Обновление данных персонажа
                    if(users[name].death){
                        var death = users[name].death;
                    }
                    else{
                        var death = null;
                    }
                    connection.update('characters', {
                            health: users[name].character['health'],
                            mana: users[name].character['mana'],
                            hp_b: users[name].character['hp_b'],
                            mp_b: users[name].character['mp_b'],
                            death:death
                        },
                        { name: users[name].character['name'] },
                        function(err, affectedRows) {
                            //sss
                        });

                    //Delete
                    getRoomUser(users[name].location);
                    delete users[nick];
                    io.emit('online',users.length);
                }
                clearInterval(del_timer);
            }
            else{
                if(old_s_id != users[nick].s_id){
                    clearInterval(del_timer);
                    if(last_chat != users[nick].chat){
                        //Обновление чатов
                        var room_users = Array();
                        var cl = socket.adapter.rooms[last_chat];
                        for (var key in cl) {
                            room_users[room_users.length] = io.sockets.connected[key].nickname;
                        }
                        socket.broadcast.to(last_chat).emit('chat_list_update',room_users);
                    }
                }
            }
        },1000);
    };
    //Смена цели
    function change_target(battle,group,exclude){
        if(battle.type == 'mob'){
            var summ = 0;
            for (var i = 0; i < battle.round[group].length;i++){
                if(battle.round[group][i]['health'] > 0){
                    summ++;
                }
            }
            users[name].main_status.target = {number:getTarget(battle.round[group],exclude),group:group,count:summ};
            battles[users[name].battle.id].round[group] = hp_mob(battles[users[name].battle.id].round[group]);
            io.to(socket.id).emit('round',{battle:battles[users[name].battle.id],char:users[name].main_status});
            logger.warn(users[name].main_status);
        }
    }
    //Получение случайной цели
    function getTarget(targets,exclude){
        var i = 0;
        var summ = 0;
        while(targets[i]){
            if(i != exclude && targets[i]['health'] != 0){
                summ += targets[i]['ctbh'];
            }
            i++;
        }
        if(summ == 0 && exclude != -1){
            return exclude;
        }
        var chance = Math.floor((Math.random()*summ)+1);
        summ = 0;
        i = 0;
        while(targets[i]){
            summ += targets[i]['ctbh'];
            if(summ >= chance && i != exclude && targets[i]['health'] != 0){
                break;
            }
            i++;
        }
        return i;
    };
    //Создание раунда в бою с мобом
    function greatRound(list,round_number){
        var mobs = new Array();
        var re = /\s*,\s*/;
        mobs = list[round_number - 1].split(re);
        var k = 0;
        //Перебираем типы мобов
        while(mobs[k]){
            re = /\s*-\s*/;
            var mob = mobs[k].split(re);
            function selectMob(mob_id,mob_amount){
                connection.queryRow(
                    'SELECT * FROM mobs WHERE id = ?', [mob_id],
                    function(err, mob_stats){
                        var count = 0;
                        //Перебираем количество мобов
                        while(count != parseInt(mob_amount)){
                            battles[users[name].battle.id].round[1][battles[users[name].battle.id].round[1].length] = generationMob(mob_stats);
                            var timee = new Date();
                            battles[users[name].battle.id].round[1][battles[users[name].battle.id].round[1].length - 1].reg_time = timee.getTime();
                            count++;
                        }
                        change_target(battles[users[name].battle.id],1,-1);
                    });
            }
            selectMob(mob[0],mob[1]);
            k++;
        }
    };
    //Генерация моба
    function generationMob(mob){
        var chance = Math.floor((Math.random()*100)+1);
        var getMob = {id:mob['id'],name:mob['name'],level:mob['level'],health:mob['health'],health_max:mob['health'],
            health_reg:mob['health_reg'],def:mob['def'],resist:mob['resist'],dmg_min:mob['dmg_min'],dmg_max:mob['dmg_max'],
            dmg_type:mob['dmg_type'],strike:mob['strike'],dodge:mob['dodge'],accuracy:mob['accuracy'],aspeed:mob['aspeed'],
            exp:mob['exp'],gold_min:mob['gold_min'],gold_max:mob['gold_max'],drop_v:mob['drop_v'],drop_add:mob['drop_add'],
            bonus:'',ctbh:mob['ctbh'],loc:mob['loc']};
        if(mob['type'] == 1){
            switch (true){
                case chance <= 5:
                    getMob['name'] = 'Чемпион ' + mob['name'].toLowerCase();
                    getMob['type'] = 3;
                    getMob['health'] = mob['health']*3;
                    getMob['health_max'] = mob['health']*3;
                    getMob['health_reg'] = mob['health_reg']*2;
                    getMob['def'] = mob['def']*2;
                    getMob['resist'] = mob['resist']*2;
                    getMob['dmg_min'] = mob['dmg_min']*2;
                    getMob['dmg_max'] = mob['dmg_max']*2;
                    getMob['accuracy'] = mob['accuracy']*2;
                    getMob['dodge'] = mob['dodge']*2;
                    getMob['exp'] = mob['exp']*8;
                    getMob['gold_min'] = mob['gold_min']*6;
                    getMob['gold_max'] = mob['gold_max']*6;
                    break;
                case chance <= 15:
                    getMob['type'] = 2;
                    getMob['bonus'] = 'Улучшенная защита';
                    getMob['def'] = mob['def']*2;
                    getMob['exp'] = mob['exp']*2;
                    getMob['gold_min'] = mob['gold_min']*2;
                    getMob['gold_max'] = mob['gold_max']*2;
                    break;
                case chance <= 25:
                    getMob['type'] = 2;
                    getMob['bonus'] = 'Улучшенная магическая защита';
                    getMob['resist'] = mob['resist']*2;
                    getMob['exp'] = mob['exp']*2;
                    getMob['gold_min'] = mob['gold_min']*2;
                    getMob['gold_max'] = mob['gold_max']*2;
                    break;
                case chance <= 35:
                    getMob['type'] = 2;
                    getMob['bonus'] = 'Улучшенное здоровье';
                    getMob['health'] = mob['health']*2;
                    getMob['health_max'] = mob['health']*2;
                    getMob['exp'] = mob['exp']*2;
                    getMob['gold_min'] = mob['gold_min']*2;
                    getMob['gold_max'] = mob['gold_max']*2;
                    break;
                case chance <= 45:
                    getMob['type'] = 2;
                    getMob['bonus'] = 'Слабость';
                    getMob['health'] = Math.round(mob['health']/2);
                    getMob['health_max'] = Math.round(mob['health']/2);
                    getMob['exp'] = Math.round(mob['exp']/2);
                    getMob['gold_min'] = Math.round(mob['gold_min']/2);
                    getMob['gold_max'] = Math.round(mob['gold_max']/2);
                    break;
                case chance <= 55:
                    getMob['type'] = 2;
                    getMob['bonus'] = 'Берсерк';
                    getMob['health'] = Math.round(mob['health']/2);
                    getMob['health_max'] = Math.round(mob['health']/2);
                    getMob['exp'] = Math.round(mob['exp']*2);
                    getMob['dmg_min'] = mob['dmg_min']*3;
                    getMob['dmg_max'] = mob['dmg_max']*3;
                    getMob['gold_min'] = Math.round(mob['gold_min']*2);
                    getMob['gold_max'] = Math.round(mob['gold_max']*2);
                    break;
                case chance <= 65:
                    getMob['type'] = 2;
                    getMob['bonus'] = 'Увеличенный урон';
                    getMob['exp'] = Math.round(mob['exp']*2);
                    getMob['dmg_min'] = mob['dmg_min']*2;
                    getMob['dmg_max'] = mob['dmg_max']*2;
                    getMob['gold_min'] = Math.round(mob['gold_min']*2);
                    getMob['gold_max'] = Math.round(mob['gold_max']*2);
                    break;
                case chance <= 75:
                    getMob['type'] = 2;
                    getMob['bonus'] = 'Увеличенная ловкость';
                    getMob['exp'] = Math.round(mob['exp']*2);
                    getMob['accuracy'] = mob['accuracy']*2;
                    getMob['dodge'] = mob['dodge']*2;
                    getMob['gold_min'] = Math.round(mob['gold_min']*2);
                    getMob['gold_max'] = Math.round(mob['gold_max']*2);
                    break;
            }
        }
        return getMob;
    };
    function lifeMob(battle_id,number){
        battles[battle_id].round[1][number].mob_att = null;
        battles[battle_id].round[1][number].mob_att = randWDclassic(20);
        mobs[battles[battle_id].round[1][number].mob_att] = setInterval(function(){
            if(users[battles[battle_id].round[1][number].target] == undefined){//Если пользователь отсоеденился
                clearInterval(mobs[battles[battle_id].round[1][number].mob_att]);
            }
            else{
                if(users[battles[battle_id].round[1][number].target].character.health <= 0 || battles[battle_id].round[1][number]['health'] <= 0 || users[battles[battle_id].round[1][number].target].main_status.battle == 0){
                    clearInterval(mobs[battles[battle_id].round[1][number].mob_att]);
                }
                else{
                    var mob_hit = hit(users[battles[battle_id].round[1][number].target].character,battles[battle_id].round[1][number],0);
                    if(mob_hit.hit == 1){
                        hp_mp(battles[battle_id].round[1][number].target);
                        users[battles[battle_id].round[1][number].target].character['health'] -= mob_hit.dmg;
                        if(users[battles[battle_id].round[1][number].target].character['health'] < 0){
                            users[battles[battle_id].round[1][number].target].character['health'] = 0;
                        }
                    }
                    io.to(users[battles[battle_id].round[1][number].target].s_id).emit('chat_log',{
                        a:battles[battle_id].round[1][number]['name'] + '[' + battles[battle_id].round[1][number]['level'] + ']',
                        target:battles[battle_id].round[1][number].target,hit:mob_hit,
                        target_hp:users[battles[battle_id].round[1][number].target].character['health']});
                }
            }
        },battles[battle_id].round[1][number].aspeed * 1000);
    };
    //Завершение боя
    function finishBattle(battle_id){
        if(battles[battle_id].type == 'mob'){
            for(var i = 0; i < battles[battle_id].round[1].length; i++){
                if(battles[battle_id].round[1][i].health > 0){
                    clearInterval(mobs[battles[battle_id].round[1][i].mob_att]) ;
                    delete mobs[battles[battle_id].round[1][i].mob_att];
                }
            }
        }
        delete battles[users[name].battle.id];
    }
    //Награда
    function getGold(mob){
        var gold_from_mob = Math.round(Math.random()*(mob['gold_max'] - mob['gold_min'] + 1)+mob['gold_min']);
        gold_from_mob = Math.round(gold_from_mob/(Math.pow(users[name].character['level']/mob['level'],2)));
        if(gold_from_mob < 1) gold_from_mob = 1;
        var character_gold = 0;
        connection.queryRow('SELECT gold FROM users WHERE id = ?', [users[name].character['user']],
            function(err, row){
                character_gold = row['gold'];
                connection.update('users', {
                        gold: character_gold + gold_from_mob
                    },
                    { id: users[name].character['user'] },
                    function(err, affectedRows) {
                        //sdfsdf
                    });
            });
        return gold_from_mob;
    };
    //Опыт
    function getExpFromMob(mob){
        var exp = Math.round(mob['exp']/Math.pow(users[name].character['level']/mob['level'],2));
        if(exp < 1){
            exp = 1
        }
        if(exp > mob['exp']*1.2){
            exp = Math.round(mob['exp']*1.2);
        }
        return exp;
    };
    function updateExp(exp){
        users[name].character['exp'] += exp;
        if(users[name].character['exp'] <= users[name].character['exp_was']){
            users[name].character['exp'] = users[name].character['exp_was'];
        }
        if(users[name].character['exp'] >= users[name].character['exp_need']){
            var was = users[name].character['exp_need'];
            users[name].character['level']++;
            users[name].character['free_char'] += 5;
            connection.queryRow('SELECT exp FROM exp WHERE id = ?', [users[name].character['level']],
                function(err, row){
                    users[name].character['exp_need'] = row['exp'];
                    //Отослать информацио об обновлении опыта
                    io.to(socket.id).emit('chat_drop',{type: 'exp', amount:{
                        exp:users[name].character['exp'],
                        exp_was:users[name].character['exp_was'],
                        exp_need:users[name].character['exp_need'],
                        exp_get:exp,
                        lvl_up: users[name].character['level']}
                    });
                    connection.update('characters', {
                            exp: users[name].character['exp'],
                            exp_was: was,
                            exp_need: users[name].character['exp_need'],
                            level: users[name].character['level'],
                            free_char: users[name].character['free_char']
                        },
                        { id: users[name].character['id'] },
                        function(err, affectedRows) {
                            //sss
                        });
                });
        }
        else{
            connection.update('characters', {
                    exp: users[name].character['exp']
                },
                { id: users[name].character['id'] },
                function(err, affectedRows) {
                    //adsfasd
                });
        }
        return {exp:users[name].character['exp'],
            exp_was:users[name].character['exp_was'],
            exp_need:users[name].character['exp_need'],
            exp_get:exp}
    };
    //Дроп
    function Drop(mob,group,name){
        var count_drop = mob['drop_v'];
        connection.queryRow('SELECT lut, k_w, k_b FROM exp WHERE id = ?', [mob['level']],
            function(err, row){
                //Дроп
                var drop = new Array();
                //Изменяем шанс дропа в зависимости от количества членов в группе

                //Изменяем шанс дропа в зависимости от уровня персонажа
                var drop_chance = row['lut']/Math.pow(users[name].character['level']/mob['level'],2);
                if(drop_chance > row['lut']*1.2){
                    drop_chance = Math.round(row['lut']*1.2);
                }
                //Проверяем дополнительный дроп
                if(mob['drop_add'] >= (Math.floor((Math.random()*100)+1))){
                    count_drop++;
                }
                //Цикл подсчета падения предметов
                for(var i = 0;i < count_drop; i++){
                    //Получаем 1 стандартную вещь
                    var standart = getStandartDrop(drop_chance,row['k_w'],row['k_b'],mob['level'],name);
                    if (standart){
                        var check = false;
                        var u = 0;
                        while(drop[u]){
                            if(drop[u].name == standart.name){
                                drop[u].amount++;
                                check = true;
                                break;
                            }
                            u++;
                        }
                        if(check == false){
                            drop[drop.length] = standart;
                        }
                    }
                }

                io.to(socket.id).emit('chat_drop',{type:'drop',drop:drop});

                //Раскладываем дроп по местам)
                for (var i = 0;i < drop.length; i++){
                    switch (drop[i].type){
                        case 'stone':
                            stekCasket(name,drop[i]);
                            break;
                        case 'bootle':
                            main_status.bottles = {hp:users[name].character['hp_b'],mp:users[name].character['mp_b']};
                            io.to(users[data.nick].s_id).emit('char_status',main_status);
                            break;
                        default :
                            putToStorage(name,drop[i]);
                    }
                }
            });
    };
    function getStandartDrop(lut,k_w,k_b,mob,name){
        if(lut >= (Math.floor((Math.random()*100)+1))){
            var drop = new Object();
            //Определяем какой предмет упал.
            var chance = Math.floor((Math.random()*100)+1);
            //Определяем уровень лута
            var lvl = 0;
            switch (true){
                case mob <= 6:
                    lvl = 1;
                    break;
                case mob <= 15:
                    lvl = 2;
                    break;
                case mob <= 25:
                    lvl = 3;
                    break;
                case mob <= 35:
                    lvl = 4;
                    break;
                case mob <= 45:
                    lvl = 5;
                    break;
                case mob <= 55:
                    lvl = 6;
                    break;
                case mob <= 65:
                    lvl = 7;
                    break;
                case mob <= 75:
                    lvl = 8;
                    break;
                case mob <= 85:
                    lvl = 9;
                    break;
                case mob <= 95:
                    lvl = 10;
                    break;
                case mob <= 100:
                    lvl = 11;
                    break;
            }
            //Определяем тип лута
            switch (true){
                case chance <= 25://Упала банка
                    //Определяем тип банки
                    chance = Math.floor((Math.random()*2)+1);
                    if(chance == 1){
                        //Здоровье
                        users[name].character['hp_b']++;
                        drop = {type:'bottle', name: 'Элексир жизни', amount: 1};
                    }
                    else{
                        //Мана
                        users[name].character['mp_b']++;
                        drop = {type:'bottle', name: 'Элексир маны',amount: 1};
                    }
                    return(drop);
                    break;
                case chance <= 27://Упал камень
                    lvl = Math.round(lvl/2);
                    chance = Math.floor((Math.random()*100)+1);
                    switch (true){
                        case chance <= 20:
                            drop = {type:'stone', name: 'Рубиновый камень', amount: 1, lvl: lvl};
                            break;
                        case chance <= 40:
                            drop = {type:'stone', name: 'Сапфировый камень', amount: 1, lvl: lvl};
                            break;
                        case chance <= 60:
                            drop = {type:'stone', name: 'Топазовый камень', amount: 1, lvl: lvl};
                            break;
                        case chance <= 80:
                            drop = {type:'stone', name: 'Янтарный камень', amount: 1, lvl: lvl};
                            break;
                        case chance <= 100:
                            drop = {type:'stone', name: 'Изумрудный камень', amount: 1, lvl: lvl};
                            break;
                    }
                    return(drop);
                    break;
                case chance <= 65://Упал ресурс для крафта
                    var amount = Math.round(lvl/2);
                    amount = Math.floor((Math.random()*(lvl - amount + 1)) + amount);
                    chance = Math.floor((Math.random()*100)+1);
                    switch (true){
                        case chance <= 25:
                            drop = {type: 'wood', name: 'Древесина', amount: amount};
                            break;
                        case chance <= 50:
                            drop = {type: 'ore', name: 'Руда', amount: amount};
                            break;
                        case chance <= 75:
                            drop = {type: 'thread', name: 'Нить', amount: amount};
                            break;
                        case chance <= 100:
                            drop = {type: 'leather', name: 'Кожа', amount: amount};
                            break;
                    }
                    return(drop);
                    break;
                case chance <= 100://Упала вещь
                    //Определяем тип
                    chance = Math.floor((Math.random()*100)+1);
                    var type = undefined;
                    switch (true){
                        case chance <= 15:
                            type = 'weapon';
                            break;
                        case chance <= 25:
                            type = 'head';
                            break;
                        case chance <= 35:
                            type = 'shoulders';
                            break;
                        case chance <= 40:
                            type = 'neck';
                            break;
                        case chance <= 55:
                            type = 'chest';
                            break;
                        case chance <= 65:
                            type = 'hands';
                            break;
                        case chance <= 70:
                            type = 'rings';
                            break;
                        case chance <= 80:
                            type = 'belt';
                            break;
                        case chance <= 90:
                            type = 'legs';
                            break;
                        case chance <= 100:
                            type = 'foot';
                            break;
                    }
                    //Получаем список возможных предметов
                    connection.select('standard_things','*', {main_type: type, lvl: lvl},
                        function(err, row){
                            chance = Math.floor((Math.random()*row.length)+1);
                            var thing = row[chance - 1];
                            var k = 1;
                            thing['char_id'] = users[name].character['id'];

                            //Если это оружие, то сичтаем урон
                            if(thing['type'] == 'Одноручные мечи' || thing['type'] == 'Одноручные топоры' || thing['type'] == 'Одноручные булавы'){
                                thing['self_dmg_min'] = Math.round(thing['self_dmg']*0.8);
                                thing['self_dmg_max'] = Math.round(thing['self_dmg']*1.2);
                            }

                            //Определяем тип шмотки
                            chance = Math.floor((Math.random()*10000)+1);
                            switch (true){
                                case chance <= 9000*k_w:
                                    thing['kind'] = 1;
                                    thing['name'] = '☆ ' + thing['name'];
                                    break;
                                case chance <= ((10000 - 9000*k_w)*0.9*k_b + 9000*k_w):
                                    k = 1.1;
                                    thing['kind'] = 2;
                                    thing['name'] = '☆☆ ' + thing['name'];
                                    thing['cost'] = Math.round(thing['cost']*1.5);
                                    thing['img'] = thing['img'].replace(new RegExp("_1",'g'),"_2");
                                    thing['self_dur'] = Math.round(thing['self_dur']*1.1);

                                    //Обновляем основные статы
                                    switch (true){
                                        case thing['main_type'] == 'weapon' :
                                            thing['self_dmg_min'] = Math.round(thing['self_dmg_min']*1.1);
                                            thing['self_dmg_max'] = Math.round(thing['self_dmg_max']*1.1);
                                            break;
                                        case thing['type'] == 'Тяжелый шлем':
                                            thing['self_hp'] = Math.round(thing['self_hp'] + (25 * (thing['lvl'] / 10)));
                                            break;
                                        case thing['main_type'] == 'shoulders':
                                            thing['self_dodge'] = Math.round(thing['self_dodge'] + (10 * (thing['lvl'] / 10)));
                                            break;
                                        case thing['main_type'] == 'neck':
                                            if(thing['self_def']) thing['self_def'] = Math.round(thing['self_def'] + thing['lvl']);
                                            if(thing['self_resist']) thing['self_resist'] = Math.round(thing['self_resist'] + thing['lvl']);
                                            if(thing['self_dodge']) thing['self_dodge'] = Math.round(thing['self_dodge'] + thing['lvl']);
                                            break;
                                        case thing['type'] == 'Тяжелая броня' || thing['type'] == 'Тяжелые бриджи':
                                            thing['self_def'] = Math.round(thing['self_def'] + (35 * (1 + (thing['lvl'] / 10))));
                                            thing['self_resist'] = Math.round(thing['self_resist'] + (15 * (1 + (thing['lvl'] / 10))));
                                            break;
                                        case thing['type'] == 'Грубые наручи' || thing['type'] == 'Тяжелые ботинки':
                                            thing['self_def'] = Math.round(thing['self_def'] + (20 * (1 + (thing['lvl'] / 10))));
                                            thing['self_resist'] = Math.round(thing['self_resist'] + (10 * (1 + (thing['lvl'] / 10))));
                                            break;
                                        case thing['main_type'] == 'rings':
                                            thing['self_dmg'] = Math.round(thing['self_def'] + (2 * (1 + (thing['lvl']/10))));
                                            break;
                                        case thing['main_type'] == 'belt':
                                            if(thing['self_def']) thing['self_def'] = Math.round(thing['self_def'] + thing['lvl']);
                                            if(thing['self_resist']) thing['self_resist'] = Math.round(thing['self_resist'] + thing['lvl']);
                                            if(thing['self_dodge']) thing['self_dodge'] = Math.round(thing['self_dodge'] + thing['lvl']);
                                            break;
                                    }
                                    break;
                                default :
                                    k = 1.2;
                                    thing['kind'] = 3;
                                    thing['name'] = '☆☆☆ ' + thing['name'];
                                    thing['cost'] = Math.round(thing['cost']*2);
                                    thing['img'] = thing['img'].replace(new RegExp("_1",'g'),"_3");
                                    thing['self_dur'] = Math.round(thing['self_dur']*1.2);

                                    //Обновляем основные статы
                                    switch (true){
                                        case thing['main_type'] == 'weapon' :
                                            thing['self_dmg_min'] = Math.round(thing['self_dmg_min']*1.2);
                                            thing['self_dmg_max'] = Math.round(thing['self_dmg_max']*1.2);
                                            break;
                                        case thing['type'] == 'Тяжелый шлем':
                                            thing['self_hp'] = Math.round(thing['self_hp'] + (30 * (thing['lvl'] / 10)));
                                            break;
                                        case thing['main_type'] == 'shoulders':
                                            thing['self_dodge'] = Math.round(thing['self_dodge'] + (20 * (thing['lvl'] / 10)));
                                            break;
                                        case thing['main_type'] == 'neck':
                                            if(thing['self_def']) thing['self_def'] = Math.round(thing['self_def'] + (thing['lvl'] * 3));
                                            if(thing['self_resist']) thing['self_resist'] = Math.round(thing['self_resist'] + (thing['lvl'] * 3));
                                            if(thing['self_dodge']) thing['self_dodge'] = Math.round(thing['self_dodge'] + (thing['lvl'] * 2));
                                            break;
                                        case thing['type'] == 'Тяжелая броня' || thing['type'] == 'Тяжелые бриджи':
                                            thing['self_def'] = Math.round(thing['self_def'] + (70 * (1 + (thing['lvl'] / 10))));
                                            thing['self_resist'] = Math.round(thing['self_resist'] + (30 * (1 + (thing['lvl'] / 10))));
                                            break;
                                        case thing['type'] == 'Грубые наручи':
                                            thing['self_def'] = Math.round(thing['self_def'] + (30 * (1 + (thing['lvl'] / 10))));
                                            thing['self_resist'] = Math.round(thing['self_resist'] + (20 * (1 + (thing['lvl'] / 10))));
                                            break;
                                        case thing['main_type'] == 'rings':
                                            thing['self_dmg'] = Math.round(thing['self_def'] + (2 * (1 + (thing['lvl']/10))));
                                            break;
                                        case thing['main_type'] == 'belt':
                                            if(thing['self_def']) thing['self_def'] = Math.round(thing['self_def'] + (thing['lvl'] * 3));
                                            if(thing['self_resist']) thing['self_resist'] = Math.round(thing['self_resist'] + (thing['lvl'] * 3));
                                            if(thing['self_dodge']) thing['self_dodge'] = Math.round(thing['self_dodge'] + (thing['lvl'] * 2));
                                            break;
                                        case thing['type'] == 'Тяжелые ботинки':
                                            thing['self_def'] = Math.round(thing['self_def'] + (40 * (1 + (thing['lvl'] / 10))));
                                            thing['self_resist'] = Math.round(thing['self_resist'] + (20 * (1 + (thing['lvl'] / 10))));
                                            break;
                                    }
                                    break;
                            }
                            //Основные статы
                            thing['self_dur_now'] = Math.floor(Math.random()*(thing['self_dur'] - 10*thing['kind'] + 1)+10*thing['kind']);
                            if(thing['socket_1']) thing['socket_1'] = Math.round(thing['socket_1']*k);
                            if(thing['socket_2']) thing['socket_2'] = Math.round(thing['socket_2']*k);
                            if(thing['socket_3']) thing['socket_3'] = Math.round(thing['socket_3']*k);
                            if(thing['socket_4']) thing['socket_4'] = Math.round(thing['socket_4']*k);
                            //Расчитываем количество сокетов
                            if(thing['main_type'] != 'belt' && thing['main_type'] != 'shoulders' && thing['main_type'] != 'neck' && thing['main_type'] != 'rings'){
                                chance = Math.floor((Math.random()*100)+1);
                                if(thing['main_type'] == 'weapon'){
                                    switch (true){
                                        case chance <= thing['socket_1']:
                                            thing['socket'] = 1;
                                            break;
                                        case chance <= thing['socket_1'] + thing['socket_2']:
                                            thing['socket'] = 2;
                                            break;
                                        default :
                                            thing['socket'] = 0;
                                            break;
                                    }
                                }
                                else{
                                    switch (true){
                                        case chance <= thing['socket_1']:
                                            thing['socket'] = 1;
                                            break;
                                        case chance <= thing['socket_1'] + thing['socket_2']:
                                            thing['socket'] = 2;
                                            break;
                                        case chance <= thing['socket_1'] + thing['socket_2'] + thing['socket_3']:
                                            thing['socket'] = 3;
                                            break;
                                        case chance <= thing['socket_1'] + thing['socket_2'] + thing['socket_3'] + thing['socket_4']:
                                            thing['socket'] = 4;
                                            break;
                                        default :
                                            thing['socket'] = 0;
                                            break;
                                    }
                                }
                            }

                            //Дополнительные свойства
                            if(thing['kind'] >= 2){
                                //Определяем количество дополнительных статов
                                var re = /\s*-\s*/;
                                if(thing['kind'] == 2){
                                    var amount_s = thing['stats_b'].split(re);
                                }
                                else{
                                    var amount_s = thing['stats_f'].split(re);
                                }
                                var stats = Math.floor(Math.random()*(parseInt(amount_s[1]) - parseInt(amount_s[0]) + 1) + parseInt(amount_s[0]));

                                //Получаем шансы выпадения статов
                                connection.select('thing_stats','*', {main_type: type, lvl: lvl},
                                    function(err, row){
                                        var u = 0;
                                        while (row[u]){
                                            var re = /\s*,\s*/;
                                            var types = row[u]['type'].split(re);
                                            var t = 0,check = false;
                                            while (types[t]){
                                                if(types[t] == thing['type']){
                                                    check = true;
                                                    break;
                                                }
                                                t++;
                                            }
                                            if(check){
                                                break;
                                            }
                                            u++;
                                        }
                                        var thing_1 = new Object();

                                        //Определяем какой стат выпал
                                        for(var i = 0; i < stats; i++){
                                            var summ = 0;
                                            var k = 1;
                                            var c = true;
                                            chance = Math.floor((Math.random()*100)+1);
                                            for (var stat in thing){
                                                if (thing[stat] != null && 25 < k && k < 53){//всего 27 статов
                                                    summ += row[u][stat];
                                                    if(chance <= summ && c){
                                                        c = false;
                                                        if(thing[stat].indexOf('-') != -1){
                                                            var re = /\s*-\s*/;
                                                            var v = thing[stat].split(re);
                                                            if(thing_1[stat] == undefined){
                                                                thing_1[stat] = Math.floor(Math.random()*(parseInt(v[1]) - parseInt(v[0]) + 1) + parseInt(v[0]));
                                                            }
                                                            else{
                                                                thing_1[stat] += Math.floor(Math.random()*(parseInt(v[1]) - parseInt(v[0]) + 1) + parseInt(v[0]));
                                                            }
                                                        }
                                                        else{
                                                            if(thing_1[stat] == undefined){
                                                                thing_1[stat] = parseInt(thing[stat]);
                                                            }
                                                            else{
                                                                thing_1[stat] += parseInt(thing[stat]);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{
                                                    if(thing[stat] != null) thing_1[stat] = thing[stat];
                                                }
                                                k++;
                                            }
                                        }

                                        //Увеличиваем селф статы за счет дополнительных
                                        if(thing_1['dur']) thing_1['self_dur'] = Math.round(thing_1['self_dur'] * ((thing_1['dur']/100) + 1));
                                        if(thing_1['n_stats']){
                                            thing_1['n_str'] = Math.round(thing_1['n_str'] * (1 - (thing_1['n_stats']/100)));
                                            thing_1['n_dex'] = Math.round(thing_1['n_dex'] * (1 - (thing_1['n_stats']/100)));
                                            thing_1['n_int'] = Math.round(thing_1['n_int'] * (1 - (thing_1['n_stats']/100)));
                                        }
                                        if(thing_1['def_perc']) thing_1['self_def'] = Math.round(thing_1['self_def'] * (thing_1['def_perc']/100 + 1));
                                        if(thing_1['resist_perc']) thing_1['self_resist'] = Math.round(thing_1['self_def'] * (thing_1['resist_perc']/100 + 1));
                                        if(thing_1['main_type'] == 'weapon'){
                                            if(thing_1['dmg']) {
                                                thing_1['self_dmg_min'] += thing_1['dmg'];
                                                thing_1['self_dmg_max'] += thing_1['dmg'];
                                            }
                                            if(thing_1['dmg_perc']) {
                                                thing_1['self_dmg_min'] = Math.round(thing_1['self_dmg_min'] * ((thing_1['dmg_perc'] /100) + 1));
                                                thing_1['self_dmg_max'] = Math.round(thing_1['self_dmg_max'] * ((thing_1['dmg_perc'] /100) + 1));
                                            }
                                        }

                                        delete thing_1['parse'];
                                        delete thing_1['_typeCast'];
                                        delete thing_1['socket_1'];
                                        delete thing_1['socket_2'];
                                        delete thing_1['socket_3'];
                                        delete thing_1['socket_4'];
                                        delete thing_1['stats_b'];
                                        delete thing_1['stats_f'];
                                        delete thing_1['id'];

                                        connection.insert('things', thing_1, function(err, recordId) {
                                            if(users[name].bag_update == undefined){
                                                putToBag(name,recordId,thing['name']);
                                                users[name].bag_update = 'close';
                                            }
                                            else{
                                                var bag = setInterval(function(){
                                                    if(users[name].bag_update == undefined){
                                                        putToBag(name,recordId,thing['name']);
                                                        users[name].bag_update = 'close';
                                                        clearInterval(bag);
                                                    }
                                                },100)
                                            }
                                        });
                                    });
                            }
                            else{
                                delete thing['parse'];
                                delete thing['_typeCast'];
                                delete thing['socket_1'];
                                delete thing['socket_2'];
                                delete thing['socket_3'];
                                delete thing['socket_4'];
                                delete thing['stats_b'];
                                delete thing['stats_f'];
                                delete thing['id'];

                                //Удаляем пустые статы
                                var k = 1;
                                for(var stat in thing){
                                    if(thing[stat] == null  || (18 < k && k < 42)){
                                        delete  thing[stat];
                                    }
                                    k++;
                                }
                                console.log(thing);
                                connection.insert('things', thing, function(err, recordId) {
                                    if(users[name].bag_update == undefined){
                                        putToBag(name,recordId,thing['name']);
                                        users[name].bag_update = 'close';
                                    }
                                    else{
                                        var bag = setInterval(function(){
                                            if(users[name].bag_update == undefined){
                                                putToBag(name,recordId,thing['name']);
                                                users[name].bag_update = 'close';
                                                clearInterval(bag);
                                            }
                                        },100)
                                    }
                                });
                            }
                        });
                    break;
            }
        }
    };
    //Кладем в рюкзак
    function putToBag(name,thing_id,thing_name){
        connection.select('bags','*', {id: users[name].character['id']},
            function(err, row){
                var i = 0;
                while(row[0]['bag' + i] || i < users[name].character['bag']){
                    if(row[0]['bag' + i] == null){
                        var query = new Object();
                        query['bag' + i] = thing_id;
                        connection.update('bags', query,
                            {id: users[name].character['id']},
                            function(err, affectedRows) {
                                users[name].bag_update = undefined;
                                io.to(users[name].s_id).emit('chat_drop',{type: 'item',
                                    name: thing_name,
                                    id: thing_id});
                            });
                        break;
                    }
                    i++;
                }
                if(i == users[name].character['bag']){
                    users[name].bag_update = undefined;
                    //Удаляем последний предмет
                    connection.delete('things', { id: thing_id }, function(err, affectedRows) {
//                        console.dir({delete:affectedRows});
                    });
                    logger.warn('У пользователя ' + name + ' переполнен рюкзак!');
                    //Отправляем информацию о том, что рюкзак полон
                    io.to(users[name].s_id).emit('system_message',{type: 'item',
                        message: 'Рюкзак переполнен!'});
                }
            });
    };
    //Кладем в шкатулку
    function putToCasket(name,stone){
        connection.queryRow('SELECT * FROM casket WHERE id = ?', [users[name].character['id']],
            function(err, row){
                var check = true;
                for(var k = 1; k <= users[name].character['casket'];k++){
                    if(row['c_' + k] != null){
                        var re = /\s*,\s*/;
                        var stone1 = row['c_' + k].split(re);
                        if(stone1[0] == stone.name && stone1[1] == stone.lvl){
                            check = false;
                            var query = new Object();
                            query['c_' + k] = stone1[0] + ',' + stone1[1] + ',' + (stone.amount + parseInt(stone1[2]));
                            connection.update('casket', query,
                                {id: users[name].character['id']},
                                function(err, affectedRows) {
                                    users[name].casket_update = undefined;
                                });
                        }
                    }
                    else{
                        var free = k;
                    }
                }
                if(check){
                    if(free){
                        var query = new Object();
                        query['c_' + free] = stone.name + ',' + stone.lvl + ',' + stone.amount;
                        connection.update('casket', query,
                            {id: users[name].character['id']},
                            function(err, affectedRows) {
                                users[name].casket_update = undefined;
                            });
                    }
                    else{
                        users[name].casket_update = undefined;
                        logger.warn('У пользователя ' + name + ' переполнена шкатулка!');
                        //Отправляем информацию о том, что шкатулка полная
                        io.to(users[name].s_id).emit('system_message',{type: 'item',
                            message: 'Шкатулка переполнена!'});
                    }
                }
        });
    };
    //Очередь в шкатулку
    function stekCasket(name,stone){
        if(users[name].casket_update == undefined){
            users[name].casket_update = 'close';
            putToCasket(name,stone);
        }
        else{
            var bag = setInterval(function(){
                if(users[name].casket_update == undefined){
                    users[name].casket_update = 'close';
                    putToCasket(name,stone);
                    clearInterval(bag);
                }
            },100)
        }
    };
    //Кладем в хранилище
    function putToStorage(name,drop){
        connection.queryRow('SELECT * FROM storage WHERE id = ?', [users[name].character['id']],
            function(err, row){
                var query = new Object();
                query[drop.type] = row[drop.type] + drop.amount;
                connection.update('storage', query,
                    {id: users[name].character['id']},
                    function(err, affectedRows) {
//                        users[name].casket_update = undefined;
                    });
            });
    };
    //Проверка на жизнь
    function checkLife(name){
        if(users[name]){
            if(users[name].character['health'] <= 0){
                logger.warn('Пользователь ' + name + ' пытается доказать, что он живой!');
                io.sockets.connected[users[name].s_id].disconnect();
            }
        }
    }
    //Генерация случайных буквоцифр
    randWDclassic = function(n){  // [ 3 ] random words and digits by the wocabulary
        var s ='', abd ='abcdefghijklmnopqrstuvwxyzQWERTYUIOPASDFGHJKLZXCVBNM0123456789', aL = abd.length;
        while(s.length < n)
            s += abd[Math.random() * aL|0];
        return s;
    }
});

