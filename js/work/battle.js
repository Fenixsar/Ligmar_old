/**
 * Created by root on 7/6/14.
 */
var battle_round = undefined;
var target_regen = null;
var hit_value = null;
//Функции
function change_target(){
    clearInterval(target_regen);
    $('#round').html(main_status.target.count);
    $('#target_name').html(battle_round.round[main_status.target.group][main_status.target.number].name);
    $('#target_lvl').html(battle_round.round[main_status.target.group][main_status.target.number].level);
    var hp_percent = battle_round.round[main_status.target.group][main_status.target.number].health*100/battle_round.round[main_status.target.group][main_status.target.number].health_max;

    $('#target_hp').html(parseInt(battle_round.round[main_status.target.group][main_status.target.number].health)).css('width',hp_percent + '%').
        attr('now',battle_round.round[main_status.target.group][main_status.target.number].health).attr('reg',battle_round.round[main_status.target.group][main_status.target.number].health_reg).
        attr('max',battle_round.round[main_status.target.group][main_status.target.number].health_max);
    $('#target_bonus').html(battle_round.round[main_status.target.group][main_status.target.number].bonus);

    if(main_status.target.count == 1){
        $('#action_change_target').addClass('disabled');
    }
    else{
        $('#action_change_target').removeClass('disabled');
    }
    regen_target();

    if(battle_round.status){
        $("#prefer_battle_actions").hide();
        $("#battle_actions").show();
    }
    $("#wait_connect_battle").hide();
    $("#battle_").show();
};
function hit_to_target(hit){
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

        if(hit.hp_target == 0){
            //Противник убит
            if(hit.f_r == 1){
                $('#battle_').hide();
                $('#wait_connect_battle').show();
                $('#timer_text').html('Загрузка следующего раунда ...');
                var count = (Math.random()*(3500 - 2000 + 1))+2000;
                setTimeout(function(){
                    main_socket.emit('change_target','',function(data){
                        change_target();
                        $('#battle_').show();
                        $('#wait_connect_battle').hide();
                    });
                },count)
            }
            else if(hit.f_b == 1){
                var finish = {type:1,last:battle.last};
                finish_battle(finish);
            }
        }
        else{
            var hp_percent = hit.hp_target * 100 / parseInt($('#target_hp').attr('max'));
            $('#target_hp').css('width', hp_percent + '%').html(parseInt(hit.hp_target)).attr('now', hit.hp_target);
        }
    }
}
//Реген цели
function regen_target(){
    //Реген цели
    target_regen = setInterval(function(){

        if(parseInt($('#target_hp').attr('now')) == 0 || main_status.battle == 0){
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

function battle(main_socket){
    //-------------------------------1----------------------------------
    $('#game').on('click', '.great_battle',function(){
        var temp_battle_id = $(this).attr("battle_id")
        Ajax('../work/battle.php',undefined,function(){
            $('#home').html('Покинуть бой');
            main_socket.emit('great_battle_with_mob', temp_battle_id,function(){});
        });
    });
    $("#game").on('click', '.next_mob',function(){
        $('#finish_battle').hide();
        $('#battle_').hide();
        $('#wait_connect_battle').show();
        $('#timer_text').html('Поиск врага ...');
        $('#home').html('Покинуть бой');
        main_socket.emit('leave_battle', 0,function(){
             var count = (Math.random()*(2500 - 1000 + 1))+1000;
            setTimeout(function(){
                $('#prefer_battle_actions').show();
                main_socket.emit('great_battle_with_mob', 'last',function(){});
            },count);
        });
    });
    $("#game").on('click', '#start_battle',function(){
        main_socket.emit('hit_target','',function(hit){
            $("#prefer_battle_actions").hide();
            $("#battle_actions").show();
            hit_to_target(hit);
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
                hit_to_target(hit);
            });
        }
    });
    $("#game").on('click', '#action_change_target',function(){
        if($(this).hasClass('disabled') == false){
            $('#action_change_target').addClass('disabled').after('<div id="cooldown_hit" class="row" style="position: static; width: 100%; background-color: gold;"></div>');
            main_socket.emit('change_target','',function(){
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

    //Инфа о созданном раунде
    main_socket.on('round',function(data){
        battle_round = data.battle;
        main_status = data.char;
        change_target();
    });

    //Ошибки
    main_socket.on('battle_undefined',function(data){
        window.location = "../";
    });


}