/**
 * Created by root on 7/6/14.
 */
var battle_round = undefined;
var target_regen = null;
//Функции
function change_target(data){
    $('#round').html(main_status.target.count);
    $('#target_name').html(data.round[main_status.target.group][main_status.target.number].name);
    $('#target_lvl').html(data.round[main_status.target.group][main_status.target.number].level);
    var hp_percent = data.round[main_status.target.group][main_status.target.number].health*100/data.round[main_status.target.group][main_status.target.number].health_max;
    $('#target_hp').html(data.round[main_status.target.group][main_status.target.number].health).css('width',hp_percent + '%').
        attr('now',data.round[main_status.target.group][main_status.target.number].health).attr('reg',data.round[main_status.target.group][main_status.target.number].health_reg).
        attr('max',data.round[main_status.target.group][main_status.target.number].health_max);
    $('#target_bonus').html(data.round[main_status.target.group][main_status.target.number].bonus);
    if(main_status.target.count == 1){
        $('#action_change_target').addClass('disabled');
    }
    else{
        $('#action_change_target').removeClass('disabled');
    }
    regen_target();
};
//Реген цели
function regen_target(){
    //Реген цели
    target_regen = setInterval(function(){
        if(parseInt($('#target_hp').attr('now')) == 0){
            clearInterval(target_regen);
            $('#target_hp').html('');
        }
        else{
            var number = parseFloat($('#target_hp').attr('now')) + parseFloat($('#target_hp').attr('reg'));
            if(number > parseInt($('#target_hp').attr('max'))){
                number = parseInt($('#target_hp').attr('max'));
            }
            var value = number/(parseInt($('#target_hp').attr('max'))/100);
            $('#target_hp').html(parseInt(number)).css('width',value + '%').attr('now',number);
        }
    },1000);
}
function battle(main_socket){
    //Переменные
    var hit_1 = null;
    var hit_value = null;

    function finish_battle(data){
        if(data.type == 1){
            $('#home').html('Город');
            $("#battle_actions").hide();
            $('#battle_').hide();
            $('#finish_battle').show().html('<div class="row" style="padding-bottom: 20px;"><div class="col-xs-0 col-sm-3"></div><div class="col-xs-12 col-sm-6 text-center"' +
                'style="border: limegreen dotted 1px; background-color: forestgreen">Поздравляем!<br>Вы победили!<br>' +
                '</div><div class="col-xs-0 col-sm-3"></div></div>' +
                '<div class="row"><button class="btn-main next_mob">Искать еще!</button></div>');
        }
    };

    //-------------------------------1----------------------------------
    $('#game').on('click', '.great_battle',function(){
        main_socket.emit('great_battle_with_mob', $(this).attr("battle_id"),function(){
            Ajax('../work/battle.php');
            $('#home').html('Покинуть бой');
        });
    });
    $("#game").on('click', '.next_mob',function(){
        $('#finish_battle').hide();
        $('#battle_').hide();
        $('#wait_connect_battle').show();
        $('#timer_text').html('Поиск врага ...');
        main_socket.emit('leave_battle', 0,function(){
             var count = (Math.random()*(3500 - 2000 + 1))+2000;
            setTimeout(function(){
                main_socket.emit('great_battle_with_mob', 'last',function(){
                    $('#home').html('Покинуть бой');
                    $('#prefer_battle_actions').show();
                });
            },count);
        });
    });
    $("#game").on('click', '#start_battle',function(){
        main_socket.emit('hit_target','',function(hit){
            $("#prefer_battle_actions").hide();
            $("#battle_actions").show();
            if(hit.hp_target == 0){
                //Здоровье цели
                $('#target_hp').css('width','0%').html('').attr('now',0);
                //Выводим урон
                var h = '';
                if(hit.strike == 1){
                    h = '- ' + hit.dmg + ' Крит!';
                }
                else{
                    h = '- ' + hit.dmg;
                }
                $('#hit_to_target').html(h);
                //И скрываем его
                var c = 10;
                hit_value = setInterval(function(){
                    $('#hit_to_target').css('opacity',c/10);
                    if(c == 0){
                        clearInterval(hit_value);
                    }
                    c--;
                },100);
                if(hit.f_b == 1){
                    finish_battle({type:1});
                }
                else{
                    change_target(hit.battle);
                }
            }
            else{
                //Отображение полученных данных
                if(hit.hit == 0){
                    $('#hit_to_target').html('Промах!');
                }
                else {
                    var h = '';
                    if (hit.strike == 1) {
                        h = '- ' + hit.dmg + ' Крит!';
                    }
                    else {
                        h = '- ' + hit.dmg;
                    }
                    $('#hit_to_target').html(h);
                    var c = 10;
                    hit_value = setInterval(function () {
                        $('#hit_to_target').css('opacity', c / 10);
                        if (c == 0) {
                            clearInterval(hit_value);
                        }
                        c--;
                    }, 100);
                    var hp_percent = hit.hp_target * 100 / parseInt($('#target_hp').attr('max'));
                    $('#target_hp').css('width', hp_percent + '%').html(parseInt(hit.hp_target)).attr('now', hit.hp_target);
                }
            }
        });
    });
    $("#game").on('click', '#action_hit_target',function(){
        if($(this).hasClass('disabled') == false){
            clearInterval(hit_value);
            $('#action_hit_target').addClass('disabled').after('<div id="cooldown_hit" class="row" style="position: static; width: 100%; background-color: gold;"></div>');
            main_socket.emit('hit_target','',function(hit){
                setTimeout(function(){
                    $('#cooldown_hit').remove();
                    $('#action_hit_target').removeClass('disabled');
                },500);
                //Отображение полученных данных
                if(hit.hit == 0){
                    $('#hit_to_target').html('Промах!');
                }
                else {
                    var h = '';
                    if (hit.strike == 1) {
                        h = '- ' + hit.dmg + ' Крит!';
                    }
                    else {
                        h = '- ' + hit.dmg;
                    }
                    $('#hit_to_target').html(h);
                    var c = 10;
                    hit_value = setInterval(function () {
                        $('#hit_to_target').css('opacity', c / 10);
                        if (c == 0) {
                            clearInterval(hit_value);
                        }
                        c--;
                    }, 100);
                    var hp_percent = hit.hp_target * 100 / parseInt($('#target_hp').attr('max'));
                    $('#target_hp').css('width', hp_percent + '%').html(parseInt(hit.hp_target)).attr('now', hit.hp_target);

                    if(hit.hp_target == 0){
                        //Противник убит
                        if(hit.battle){//Если есть еще цели
                            clearInterval(target_regen);
                            change_target(hit.battle);
                        }
                        else{
                            if(hit.f_r == 1){
                                clearInterval(target_regen);
                                $('#battle_').hide();
                                $('#wait_connect_battle').show();
                                $('#timer_text').html('Загрузка следующего раунда ...');
                                var count = (Math.random()*(3500 - 2000 + 1))+2000;
                                setTimeout(function(){
                                    main_socket.emit('change_target','',function(data){
                                        change_target(data);
                                        $('#battle_').show();
                                        $('#wait_connect_battle').hide();
                                    });
                                },count)
                            }
                            else{
                                var finish = {type:1,last:battle.last};
                                finish_battle(finish);
                            }
                        }
                    }
                }
            });
        }
    });
    $("#game").on('click', '#action_change_target',function(){
        if($(this).hasClass('disabled') == false){
            $('#action_change_target').addClass('disabled').after('<div id="cooldown_hit" class="row" style="position: static; width: 100%; background-color: gold;"></div>');
            main_socket.emit('change_target','',function(battle){
                change_target(battle);
                $('#action_change_target').removeClass('disabled');
            });
        }
    });
    $("#game").on('click', '#action_health_bottle',function(){
        if($(this).hasClass('disabled') == false){
            main_socket.emit('action_health_bottle','',function(){});
        }
    });
    $("#game").on('click', '#action_mana_bottle',function(){
        if($(this).hasClass('disabled') == false){
            main_socket.emit('action_mana_bottle','',function(){});
        }
    });
    $("#game").on('click', '.action_skill',function(){
        if($(this).hasClass('disabled') == false){
            main_socket.emit('action_skill',$(this).attr('id_skill'),function(){});
        }
    });




//    //26,11,2014
//    if($('#battle_place').attr('name') == 'lol'){
//        //Get name of location
//        main_socket.emit('get_location','',function(data){
//            $('#name_of_location').html(data.name + '[<span id="user_value">' + data.count + '</span>]');
//        });
//        //Get battle
//        main_socket.emit('get_battle','',function(battle){
//            if(battle.targets == undefined){
//                window.location = "../index.php";
//            }
//            var ch_t = '';
//            if(battle.targets > 1){
//                ch_t = '<div id="change_target" class="btn btn-link btn-block" ' +
//                    'style="text-align: left; padding: 5px" wait="yes"><img src="../img/vxod.png"> Сменить цель!</div>';
//            }
//            if(battle.status == 0){
//                ch_t += '<div id="search_next_mob" class="btn btn-link btn-block search_next_mob" ' +
//                'style="text-align: left; padding: 5px"><img src="../img/vxod.png"> Искать еще!</div>';
//            }
//            else{
//                ch_t += '<div id="search_next_mob" class="btn btn-link btn-block search_next_mob" ' +
//                    'style="text-align: left; padding: 5px; display:none" ><img src="../img/vxod.png"> Искать еще!</div>';
//            }
//            $('#wait_connect_battle').hide();
//            $('#battle_').on('click', '#hit_target',function(){
//                    $('#search_next_mob').hide();
//                    if($('#hit_target').attr('wait') == 'no'){
//                        clearInterval(hit_1);
//                        clearInterval(hit_value);
//                        $('#hit_target').attr('wait','yes').addClass('disabled');
//                        main_socket.emit('hit_target','',function(data){
//                            var t = 0;
//                            hit_1 = setInterval(function(){
//                                if($('#hit_target').attr('wait') == 'yes'){
//                                    $('#hit_target').attr('wait','no').removeClass('disabled');
//                                }
//                                t++;
//                                switch (true){
//                                    case t == 1:
//                                        $('#hit_target').html('<img src="../img/vxod.png"> Атаковать цель!(10%)');
//                                        break;
//                                    case t >= (battle.char_as*2):
//                                        $('#hit_target').html('<img src="../img/vxod.png"> Атаковать цель!(100%)');
//                                        clearInterval(hit_1)
//                                        break;
//                                    case t >= battle.char_as:
//                                        $('#hit_target').html('<img src="../img/vxod.png"> Атаковать цель!(50%)');
//                                        break;
//                                }
//                            },500);
////                            console.log(data);
//                            //Отображение полученных данных
//                            if(data.hit == 0){
//                                $('#hit_to_target').html('Промах!');
//                            }
//                            else{
//                                var h = '';
//                                if(data.strike == 1){
//                                    h = '- ' + data.dmg + ' Крит!';
//                                }
//                                else{
//                                    h = '- ' + data.dmg;
//                                }
//                                $('#hit_to_target').html(h);
//                                var c = 10;
//                                hit_value = setInterval(function(){
//                                    $('#hit_to_target').css('opacity',c/10);
//                                    if(c == 0){
//                                        clearInterval(hit_value);
//                                    }
//                                    c--;
//                                },100);
//                                var hp_percent = data.hp_target*100/parseInt($('#target_hp').attr('max'));
//                                $('#target_hp').css('width',hp_percent + '%').html(parseInt(data.hp_target)).attr('now',data.hp_target);
//                                if(data.hp_target == 0){
//                                    //Противник убит
//                                    if(data.battle){//Если есть еще цели
//                                        clearInterval(target_regen);
//                                        change_target(data.battle);
//                                    }
//                                    else{
//                                        if(data.f_r == 1){
//                                            clearInterval(target_regen);
//                                            $('#battle_').hide();
//                                            $('#wait_connect_battle').show();
//                                            $('#timer_text').html('Загрузка следующего раунда ...');
//                                        }
//                                        else{
//                                            var finish = {type:1,last:battle.last};
//                                            console.log(finish);
//                                            finish_battle(finish);
//                                        }
//                                    }
//                                }
//                            }
//                        });
//                    }
//                });
//
//            regen_target();
//        });
//    }
//    else{
//        //Перед началом боя с мобами
//        $('#wait_connect_battle').hide();
//        $('#battle').show();
//        $('.click_mob').click(function(){
//            $('#timer_text').html('Загрузка боя ...');
//            $('#wait_connect_battle').show();
//            $('#battle').hide();
//            main_socket.emit('great_battle_with_mob', $(this).attr("battle_id"),function(){
//                window.location = "../../work/battle.php";
//            });
//        });
//    }



    //Инфа о созданном раунде
    main_socket.on('round_ready',function(battle){
        battle_round = battle;
        change_target(battle_round);
    });

    //Ошибки
    main_socket.on('battle_undefined',function(data){
        window.location = "../";
    });


}