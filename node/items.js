
var cookieParser = require("cookie-parser");

module.exports = function(io) {
    io.sockets.on('connection', function(socket){
        var name = undefined;
        var req = {
            "headers": {
                "cookie": socket.request.headers.cookie
            }
        };
        cookieParser()(req, null, function() {});
        name = req.cookies['login'];

        socket.on("get_things",function(data,callback){
            switch (data){
                case "box":
                    //Получаем актуальные данные о рюкзаке
                    connection.queryRow('SELECT * FROM box WHERE id = ?', [users[name].character.user],
                        function(err, row){
                            var cb = new Array();
                            var query = new Array();
                            for (var key in row) {
                                if(row[key] != null && key.indexOf("box") >= 0){
                                    cb[cb.length] = {place:key,id_thing:row[key]}
                                    query[query.length] = row[key];
                                }
                            }
                            if(cb.length){
                                query = query.join(",");
                                connection.select('things', '*', { id: "(" + query + ")" }, { id: 'desc' }, function(err, results) {
                                    //Проверяем, есть ли место в рюкзаке
                                    getCountBag(function(count){
                                        for (var key in results) {
                                            results[key].check = checkNeedStatsThing(results[key]);
                                            results[key].eqip = checkCanCarrierThing(results[key].check);
                                            if(count < users[name].character.bag){
                                                results[key].bag = true;
                                            }
                                            else{
                                                results[key].bag = false;
                                            }
                                        }
                                        callback(results);
                                    });
                                });
                            }
                            else{
                                callback(0);
                            }
                        });
                    break;
                case "bag":
                    //Получаем актуальные данные о рюкзаке
                    connection.queryRow('SELECT * FROM bags WHERE id = ?', [users[name].character.id],
                        function(err, row){
                            var cb = new Array();
                            var query = new Array();
                            for (var key in row) {
                                if(row[key] != null && key.indexOf("bag") >= 0){
                                    cb[cb.length] = {place:key,id_thing:row[key]}
                                    query[query.length] = row[key];
                                }
                            }
                            if(cb.length){
                                query = query.join(",");
                                connection.select('things', '*', { id: "(" + query + ")" }, { id: 'desc' }, function(err, results) {
                                    //Проверяем, есть ли место в сундуке
                                    getCountBox(function(count){
                                        for (var key in results) {
                                            results[key].check = checkNeedStatsThing(results[key]);
                                            results[key].eqip = checkCanCarrierThing(results[key].check);
                                            if(count < users[name].character.box){
                                                results[key].box = true;
                                            }
                                            else{
                                                results[key].box = false;
                                            }
                                        }
                                        callback(results);
                                    });
                                });
                            }
                            else{
                                callback(0);
                            }
                        });
                    break;
                case "eqip":
                    //Получаем актуальные данные о снаряжении
                    connection.queryRow('SELECT weapon, head, shoulders, neck, chest, hands, finger, belt, legs, foot FROM equipment WHERE id = ?', [users[name].character.id],
                        function(err, row){
                            var cb = new Array();
                            var query = new Array();
                            for (var key in row) {
                                cb[cb.length] = {place:key,id_thing:row[key]}
                                query[query.length] = row[key];
                            }
                            query = query.join(",");
                            connection.select('things', '*', { id: "(" + query + ")" }, { id: 'desc' }, function(err, results) {
                                for (var key in results) {
                                    for(var i = 0; i < cb.length; i++){
                                        if(results[key].id == cb[i].id_thing){
                                            cb[i].thing = results[key];
                                        }
                                    }
                                }
                                callback(cb);
                            });

                        });
                    break;
            }
        });

        socket.on("get_thing",function(data,callback){
            checkBag(data,function(cb){
                if(cb){
                    getThing(cb.thing_id,function(thing){
                        thing.place = cb.place;
                        thing.check = checkNeedStatsThing(thing);
                        thing.eqip = checkCanCarrierThing(thing.check);

                        //Проверяем, есть ли место в сундуке
                        getCountBox(function(count){
                            if(count < users[name].character.box){
                                thing.box = true;
                            }
                            else{
                                thing.box = false;
                            }

                            getThingFromEqip(thing.main_type,function(thing_id){
                                if(thing_id){
                                    getThing(thing_id,function(thing_1){
                                        thing_1.check = checkNeedStatsThing(thing_1);
                                        callback({thing_1:thing,thing_2:thing_1});
                                    });
                                }
                                else{
                                    callback({thing_1:thing});
                                }
                            });
                        });
                    });
                }
                else{
                    checkBox(data,function(cb){
                        if(cb){
                            getThing(cb.thing_id,function(thing){
                                thing.place = cb.place;
                                thing.check = checkNeedStatsThing(thing);
                                thing.eqip = checkCanCarrierThing(thing.check);

                                //Проверяем, есть ли место в рюкзаке
                                getCountBag(function(count){
                                    if(count < users[name].character.bag){
                                        thing.bag = true;
                                    }
                                    else{
                                        thing.bag = false;
                                    }

                                    getThingFromEqip(thing.main_type,function(thing_id){
                                        if(thing_id){
                                            getThing(thing_id,function(thing_1){
                                                thing_1.check = checkNeedStatsThing(thing_1);
                                                callback({thing_1:thing,thing_2:thing_1});
                                            });
                                        }
                                        else{
                                            callback({thing_1:thing});
                                        }
                                    });
                                });
                            });
                        }
                        else{
                            checkEqip(data,function(cb){
                                if(cb){
                                    getThing(cb.thing_id,function(thing){
                                        thing.place = cb.place;
                                        thing.check = checkNeedStatsThing(thing);

                                        //Проверяем, есть ли место в рюкзаке
                                        getCountBag(function(count){
                                            if(count < users[name].character.bag){
                                                thing.bag = true;
                                            }
                                            else{
                                                thing.bag = false;
                                            }

                                            //Проверяем, есть ли место в сундуке
                                            getCountBox(function(count){
                                                if(count < users[name].character.box){
                                                    thing.box = true;
                                                }
                                                else{
                                                    thing.box = false;
                                                }
                                                callback({thing_1:thing});
                                            });
                                        });
                                    });
                                }
                                else{
                                    //Если вещь не пренадлежит персонажу
                                }
                            })
                        }
                    })
                }

            });

        });

        socket.on("get_box_bag_stat",function(data,callback){
            getCountBag(function(count){
                var stats = {bag_count:count,bag:users[name].character.bag}
                getCountBox(function(count){
                    stats.box_count = count;
                    stats.box = users[name].character.box;
                    callback(stats);
                });
            });
        });


        //Ping
        socket.on('ping',function(pong){
            pong();
        });
        //Проверка рююкзака на наличие определенной вещи
        function checkBag(thing_id,callback){
            connection.queryRow('SELECT * FROM bags WHERE id = ?', [users[name].character.id],
                function(err, row){
                    var ch = true;
                    for (var key in row) {
                        if(row[key] == thing_id){
                            ch = false;
                            callback({thing_id:thing_id,place:key});
                        }
                    }
                    if(ch){
                        callback(0);
                    }
                });
        }
        //Проверка сундука на наличие определенной вещи
        function checkBox(thing_id,callback){
            connection.queryRow('SELECT * FROM box WHERE id = ?', [users[name].character.user],
                function(err, row){
                    var ch = true;
                    for (var key in row) {
                        if(row[key] == thing_id){
                            ch = false;
                            callback({thing_id:thing_id,place:key});
                        }
                    }
                    if(ch){
                        callback(0);
                    }
                });
        }
        //Проверка снаряжение на наличие определенной вещи
        function checkEqip(thing_id,callback){
            connection.queryRow('SELECT * FROM equipment WHERE id = ?', [users[name].character.user],
                function(err, row){
                    var ch = true;
                    for (var key in row) {
                        if(row[key] == thing_id){
                            ch = false;
                            callback({thing_id:thing_id});
                        }
                    }
                    if(ch){
                        callback(0);
                    }
                });
        }


        //Получение полных данных конкретной вещи
        function getThing(thing_id,callback){
            connection.queryRow('SELECT * FROM things WHERE id = ?', [thing_id],
                function(err, row){
                    if(row){
                        callback(row);
                    }
                    else{
                        callback(0);
                    }
                });
        }
        //Получение id вещи надетой на персонажа
        function getThingFromEqip(thing_type,callback){
            connection.queryRow('SELECT ' + thing_type + ' FROM equipment WHERE id = ?', [users[name].character.id],
                function(err, row){
                    if(row){
                        callback(row[thing_type]);
                    }
                    else{
                        console.log("af");
                        callback(0);
                    }
                });
        }

        //Проверка статов вещи на возможность надеть
        function checkNeedStatsThing(thing){
            var check = new Object();
            if(thing.n_lvl > users[name].character.level){
                check.lvl = false;
            }
            else{
                check.lvl = true;
            }
            if(thing.n_str > users[name].character.str){
                check.str = false;
            }
            else{
                check.str = true;
            }
            if(thing.n_dex > users[name].character.dex){
                check.dex = false;
            }
            else{
                check.dex = true;
            }
            if(thing.n_int > users[name].character.intel){
                check.intel = false;
            }
            else{
                check.intel = true;
            }
            if(thing.class.indexOf(users[name].character.class) >= 0 || thing.class == "Все"){
                check.class = false;
            }
            else{
                check.class = false;
            }
            return check;
        }
        //Проверка можно ли надеть
        function checkCanCarrierThing(check){
            for(var stat in check) {
                if(!check[stat]){
                    return false;
                }
            };
            return true;
        }
        //Проверяем, можно ли положить в сундук
        function checkBoxFreePlace(callback){
            connection.queryRow('SELECT * FROM box WHERE id = ?', [users[name].character.user],
                function(err, row){
                    var ch = true;
                    var i = 0;
                    for (var key in row) {
                        if(row[key] == null && i <= users[name].character.box){
                            ch = false;
                            callback(key);
                            break;
                        }
                        i++;
                    }
                    if(ch){
                        callback(0);
                    }
                });
        }
        //Проверяем, можно ли положить в рюкзак
        function checkBagFreePlace(callback){
            connection.queryRow('SELECT * FROM bags WHERE id = ?', [users[name].character.user],
                function(err, row){
                    var ch = true;
                    var i = 0;
                    for (var key in row) {
                        if(row[key] == null && i <= users[name].character.box){
                            ch = false;
                            callback(key);
                            break;
                        }
                        i++;
                    }
                    if(ch){
                        callback(0);
                    }
                });
        }
        //Количество занятых мест в рюкзаке
        function getCountBag(callback){
            connection.queryRow('SELECT * FROM bags WHERE id = ?', [users[name].character.id],
                function(err, row){
                    var ch = 0;
                    for (var key in row) {
                        //if(row[key] != null  && key.indexOf("bag") >= 0){
                            ch++;
                        //}
                    }
                    callback(ch);
                });
        }
        //Количество занятых мест в сундуке
        function getCountBox(callback){
            connection.queryRow('SELECT * FROM box WHERE id = ?', [users[name].character.user],
                function(err, row){
                    var ch = 0;
                    for (var key in row) {
                        //if(row[key] != null && key.indexOf("box") >= 0){
                            ch++;
                        //}
                    }
                    callback(ch);
                });
        }
    });


};