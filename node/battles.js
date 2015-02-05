/**
 * Created by root on 3/13/14.
 */
//Подключение socket.io
var io = require('socket.io').listen(865);
//Настройки конфигурации socket.io
io.set('heartbeat interval', 3000);

//Подключение логера
var log4js = require('log4js');
var logger = log4js.getLogger();
logger.setLevel('info');

//Подключение MySQL
var mysql = require('/var/www/html/node_modules/mysql-utilities/node_modules/mysql');
mysqlUtilities = require('/var/www/html/node_modules/mysql-utilities');

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
var USERS = new Array();
var arena_1x1 = new Array();
var battles = new Array();

//Удар персонажа
function hit(battle,char,lh){
    var timee = new Date();
    var time = timee.getTime();
    //Получаем шанс попадания
    var chance_hit = char['accuracy']/battle['dodge']*100;
    if (chance_hit > 95) chance_hit = 95;
    if (chance_hit >= Math.floor((Math.random()*100)+1)){
        var absorp = battle['def']/(battle['def'] + char['level']*40);
        var absorp_m = battle['resist']/(battle['resist'] + char['level']*40);

        //Проверяем, есть ли доп. магический урон.
        if(char['dmg_mag'] != 0){
            var dmg_mag = char['dmg_mag']*(1 - absorp_m);
        }
        else{
            var dmg_mag = 0;
        }
        var dmg_min = char['dmg_min']*(1 - absorp) + dmg_mag;
        var dmg_max = char['dmg_max']*(1 - absorp) + dmg_mag;
        var dmg = Math.floor((Math.random()*(dmg_max - dmg_min + 1))+dmg_min);
        var strike = undefined;
        if(char['strike'] >= Math.floor((Math.random()*100)+1)){
            strike = 1;
            dmg = dmg * 2;
        }
        else{
            strike = 0;
        }
        if(time - lh < 3000){
            if(time - lh < 1500){
                dmg = Math.round(dmg/10);
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
//Урон моба
function mob_hit(battle,char){
    //Получаем шанс попадания
    var chance_hit = battle['accur']/char['dodge']*100;
    if (chance_hit > 95) chance_hit = 95;
    if (chance_hit >= Math.floor((Math.random()*100)+1)){
        var absorp = char['def']/(char['def'] + battle['lvl']*40);

        var dmg_min = battle['dmg_min']*(1 - absorp);
        var dmg_max = battle['dmg_max']*(1 - absorp);
        var dmg = Math.floor((Math.random()*dmg_max - dmg_min + 1)+dmg_min);
        return dmg;
    }
    else{
        return 'Промах';
    }
}
//Запись в бд при дисконнекте или при завершении боя c мобом
function updateDb(battle,char,last_hit,del,death){
    connection.update('users', {
            last_reboot: last_hit/1000
        },
        { login: char['user'] },
        function(err, affectedRows) {
            console.dir({update:affectedRows});
        });
    if(del == 1){
        connection.delete('battles', { id: battle['id'] }, function(err, affectedRows) {
            console.dir({delete:affectedRows});
        });
    }
    if(death == 1){
        connection.update('characters', {
                health: 0,
                mana: 0,
                loc: 'На сайте',
                battle: 0
            },
            { id: char['id'] },
            function(err, affectedRows) {
                console.dir({update:affectedRows});
            }
        );
    }
    else{
        if(char['health'] != 0){
            connection.update('characters', {
                    health: char['health'],
                    mana: char['mana']
                },
                { id: char['id'] },
                function(err, affectedRows) {
                    console.dir({update:affectedRows});
                }
            );
        }
    }
}
//Запись в бд в бою с персонажем
function updateCharacter(char,last_hit,death){
    connection.update('users', {
            last_reboot: last_hit/1000
        },
        { login: char['user'] },
        function(err, affectedRows) {
            console.dir({update:affectedRows});
        });
    if(death == 1){
        connection.update('characters', {
                health: 1,
                mana: 1,
                loc: 'На сайте',
                battle: 0
            },
            { id: char['id'] },
            function(err, affectedRows) {
                console.dir({update:affectedRows});
            }
        );
    }
    else{
        connection.update('characters', {
                health: char['health'],
                mana: char['mana']
            },
            { id: char['id'] },
            function(err, affectedRows) {
                console.dir({update:affectedRows});
            }
        );
    }
}

//Обработка действий при подключении
io.on('connection', function (socket) {

    //Проверяем, вдруг петух ломиться)
    socket.on('check', function (data) {
        for(var i = 0;i < USERS.length; i++){
            if(USERS[i].nickname == data){
                var temp = USERS[i].s_id;
                io.sockets.connected[temp].disconnect();
//                io.to(temp).emit('close', 'Читеришь, пацан!');
                break;
            }
        }
        USERS[USERS.length] = {nickname:data,s_id:socket.id};
    });

    var battle = undefined;
    var character = undefined;
    var last_hit = 0;
    var mob_a = undefined;
    var battle_type = undefined;

    socket.on('battle_id', function (data,cb) {
//        socket.join(data);
        connection.queryRow(
            'SELECT * FROM battles WHERE id = ?', [data],
            function(err, row){
                battle = row;
                cb(battle['id_att']);
            }
        );
    });
    socket.on('begin_battle_with_mob', function(id_char,callback){
        battle_type = 'with_mob';
        connection.queryRow(
            'SELECT * FROM characters WHERE id = ?', [id_char],
            function(err, row){
                character = row;
                if(battle != undefined){
                    var char_hit = hit(battle,character,last_hit);
                    last_hit = char_hit['last_hit'];
                    if (char_hit['hit'] != 0){
                        battle['hp'] = battle['hp'] - char_hit['dmg'];
                    }
                    var cb = {
                        hit: char_hit['hit'],
                        dmg: char_hit['dmg'],
                        strike: char_hit['strike'],
                        lh: last_hit,
                        mob_hp: battle['hp'],
                        hp: character['health']
                    };
                    if (battle['hp'] <= 0){
                        cb = 'kill';
                        updateDb(battle,character,last_hit,0,0);
                    }
                    callback(cb);
                    //Запускаем моба
                    mob_a = setInterval(function() {
                        var mob_h = mob_hit(battle,character);
                        if(mob_h != 'Промах'){
                            character['health'] = character['health'] - mob_h;
                            if (character['health'] < 0) character['health'] = 0;
                            if (character['health'] <= 0 || battle['hp'] <= 0){
                                clearInterval(mob_a);
                                if(character['health'] <= 0){
                                    updateDb(battle,character,last_hit,1,1);
                                    io.to(socket.id).emit('hit_you','kill');
                                }
                            }
                        }
                        io.to(socket.id).emit('hit_you',{hit: mob_h,
                            hp: character['health']});
                    }, 3000);
                    //Запускаем реген
                    var hp_reg = setInterval(function(){
                        if (character['health'] >= character['health_max']){
                            character['health'] = character['health_max'];
                        }
                        else{
                            if (battle['hp'] <= 0 || character['health'] == 0){
                                clearInterval(hp_reg);
                            }
                            else{
                                character['health'] = character['health'] + character['health_reg'];
                            }
                        }
                    }, 1000);
                }
                else{
                    console.log('Какой то пидарас закликивает!')
                }
            }
        );
    });
    socket.on('hit_mob', function(id_char,callback){
        if(battle != undefined){
            var char_hit = hit(battle,character,last_hit);
            last_hit = char_hit['last_hit'];
            if (char_hit['hit'] != 0){
                battle['hp'] = battle['hp'] - char_hit['dmg'];
            }
            if (battle['hp'] <= 0){
                battle['hp'] = 0;
                var cb = 'kill';
                updateDb(battle,character,last_hit,0,0);
            }
            else{
                var cb = {
                    hit: char_hit['hit'],
                    dmg: char_hit['dmg'],
                    strike: char_hit['strike'],
                    lh: last_hit,
                    mob_hp: battle['hp'],
                    hp: character['health']
                };
            }
            callback(cb);
        }
        else{
            console.log('Какой то пидорас закликивает!')
        }

    });

    //Удар по цели
    socket.on('hit_target',function(hit_data,callback){
        var target_char = undefined;
        for(var i = 0; i < battles[hit_data.battle_name].length; i++){
            if(battles[hit_data.battle_name][i].name == hit_data.target){
                target_char = battles[hit_data.battle_name][i];
                break;
            }
        }
        var char_hit = hit(target_char,character,last_hit);
        last_hit = char_hit['last_hit'];
        if (char_hit['hit'] != 0){
            target_char['health'] = target_char['health'] - char_hit['dmg'];
        }
        if (target_char['health'] <= 0){
            target_char['health'] = 0;
            var cb = 'kill';
            updateCharacter(target_char,last_hit,1);
            io.sockets.connected[target_char.s_id].emit('death',character['name']);
        }
        else{
            var cb = {
                hit: char_hit['hit'],
                dmg: char_hit['dmg'],
                strike: char_hit['strike'],
                lh: last_hit,
                target_hp: target_char['health'],
                hp: character['health']
            };
            io.sockets.connected[target_char.s_id].emit('hit_you',{hit: char_hit['dmg'],
                hp: target_char['health']});
        }
        callback(cb);
        console.log(char_hit);
    });
    //Получение номера персонажа в бою
    socket.on('id_in_battle',function(data){
        character = battles[data.battle_name][data.number];
        battle = data.battle_name;
    });
    //Арена 1x1
    socket.on('1x1',function(id_char){
        battle_type = 'arena_1x1';
        if(arena_1x1[0] != undefined){
            socket.join(socket.id + '1');
            battle = socket.id + '1';
            battles[socket.id + '1'] = 'great';
            io.sockets.connected[arena_1x1[0].id_session].join(socket.id + '1');
            io.sockets.in(socket.id + '1').emit('arena_1x1_preparation', {team_1:id_char,team_2:arena_1x1[0].id_char,battle_id:socket.id + '1'});
            var char_2 = arena_1x1[0].id_char,char_2_s = arena_1x1[0].id_session;
            var timer = setInterval(function(){
                    connection.queryRow(
                        'SELECT * FROM characters WHERE id = ?', [id_char],
                        function(err, row){
                            character = row;
                            character.s_id = socket.id;
                        }
                    );
                    connection.queryRow(
                        'SELECT * FROM characters WHERE id = ?', [char_2],
                        function(err, row){
                            io.to(char_2_s).emit('id_in_battle',1);
                            row.s_id = char_2_s;
                            battles[socket.id + '1'] = [character,row,socket.id + '1'];
                            io.to(socket.id + '1').emit('arena_1x1_start', battles[socket.id + '1']);
                        }
                    );
                    clearInterval(timer);},11000
            );
            arena_1x1.splice(0,1);
        }
        else{
            arena_1x1[arena_1x1.length] = {id_char:id_char,id_session:socket.id};
        }
    });


    socket.on('disconnect',function(){
        logger.info('lol');
        if(battle_type == 'with_mob'){
            if(character != undefined && battle['hp'] > 0){
                updateDb(battle,character,last_hit,0,0);
                clearInterval(mob_a);
            }
        }

//        //Удаляем пользователя из общего массива
        for(var i = 0;i < USERS.length; i++){
            if(USERS[i].s_id == socket.id){
                USERS.splice(i,1);
                break;
            }
        }

        //Очищаем бой
        if(battle_type == 'arena_1x1'){
            if(battles[battle] != undefined){
                battles[battle] = undefined;
                io.sockets.in(battle).emit('arena_1x1_delete', 'хз сколько');
            }
            else{
                io.sockets.in(battle).emit('arena_1x1_delete', 'хз сколько');
            }
            //Очищаем очередь боев
            for(var i = 0;i < arena_1x1.length; i++){
                if(arena_1x1[i].id_session == socket.id){
                    arena_1x1.splice(i,1);
                    break;
                }
            }
        }

    });

});
