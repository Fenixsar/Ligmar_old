/**
 * Created by root on 6/17/14.
 */


//Подключение
var main_socket = io.connect('http://ligmar.ru:852', {
//var main_socket = io.connect('http://localhost:852', {
//var main_socket = io.connect('192.168.0.103:852', {
    'reconnect': false,
    'reconnectionDelay': 1000,
    'reconnectionDelayMax': 5000
});
var main_status = new Object();
var reg_hp = null;
var reg_mp = null;
//Функции
function hp_mp(data){
    clearInterval(reg_hp);
    clearInterval(reg_mp);
    $('#health').attr('max',data.hp_max).attr('reg',data.hp_reg);
    $('#mana').attr('max',data.mp_max).attr('reg',data.mp_reg);
    var value = data.hp/(parseInt($('#health').attr('max'))/100);
    $('#health').html(parseInt(data.hp)).css('width',value + '%').attr('now',(data.hp));
    value = data.mp/(parseInt($('#mana').attr('max'))/100);
    $('#mana').html(parseInt(data.mp)).css('width',value + '%').attr('now',(data.mp));
    reg_hp = setInterval(function(){
        if(parseInt($('#health').attr('now')) == 0){
            clearInterval(reg_hp);
            clearInterval(reg_mp);
            $('#mana').html('').css('width','0%').attr('now',0);
            $('#health').html('');
            $('#battle_actions').hide();
            if($('.main').attr('location') != 'Небеса'){
                $('#notification_blood_text').html('Вы погибли!<br>' +
                    '<div class="col-xs-6"><div id="res_heaven" class="btn btn-link btn-block" style="text-align: center;' +
                    ' padding: 5px">Воскреснуть</div></div><div class="col-xs-6"><div id="res_prist" ' +
                    'class="btn btn-link btn-block" style="text-align: center; padding: 5px" disabled>Подняться</div></div>');

                $('#notification_blood').show();
                $('#res_heaven').click(function(){
                    main_socket.emit('resurrection','self',function(cb){
                        Ajax('../work/heaven.php','death');
                        $('#home').html('Город');
                        main_socket.emit('change_chat','Небеса',function(data){
                            updateChat(data);
                        });
                        $('#notification_blood_text').html('');
                        $('#notification_blood').hide();
                    })
                })
            }
        }
        else{
            if(main_status.battle){
                var number = parseFloat($('#health').attr('now')) + parseFloat($('#health').attr('reg'));
            }
            else{
                var number = parseFloat($('#health').attr('now')) + parseFloat($('#health').attr('reg'))*2;
            }

            if(number > parseInt($('#health').attr('max'))){
                number = parseInt($('#health').attr('max'));
            }
            value = number/(parseInt($('#health').attr('max'))/100);
            $('#health').html(parseInt(number)).css('width',value + '%').attr('now',number);
        }

    },1000);
    reg_mp = setInterval(function(){
        if(main_status.battle){
            var number = parseFloat($('#mana').attr('now')) + parseFloat($('#mana').attr('reg'));
        }
        else{
            var number = parseFloat($('#mana').attr('now')) + parseFloat($('#mana').attr('reg'))*2;
        }

        if(number > parseInt($('#mana').attr('max'))){
            number = parseInt($('#mana').attr('max'));
        }
        value = number/(parseInt($('#mana').attr('max'))/100);
        $('#mana').html(parseInt(number)).css('width',value + '%').attr('now',number);
    },1000);
};

$(document).ready(function(){
    //Переменные
    var list_invite_time = Array();//Массив пригласивших игроков
    var enter_invite = null;
    var cd = new Array();//Массив кд

    //При удачном соединении
    main_socket.on('connect', function(){
        //Обработчики боя
        battle(main_socket);
        //Обработчик чата
        chat(main_socket);
        //Обработчик переходов
        links(main_socket);
        main_socket.emit('name', {nick:$("#name").html()},function(data){
            if(data.name == 'hp_mp'){
                hp_mp(data.value);
                if(data.battle){
                    Ajax('../work/battle.php',undefined,undefined,function(){
                        $('#home').html('Покинуть бой').removeClass('active');
                        main_socket.emit('get_battle',0,function(){});
                    });
                }
            }
            else if(data.name == 'death'){
                Ajax('../work/heaven.php','death');
            }
        });
        ping();

    });
    //При дисконнекте
    main_socket.on('disconnect', function(){
        window.location = "../";
    });
    //Получение актуальных данных о персонаже
    main_socket.on('char_status', function(data){
        for(var i = 0; i < cd.length;i++){
            clearInterval(cd[i]);
        }
        cd = new Array();
        main_status = data;
        //Запускаем кулдауны
        if(main_status.bottles.hp_c > 0){
            var h = main_status.bottles.hp_c/60*55;
            $('#action_health_bottle').addClass('disabled').next().css('height',h).css('margin-top',-h);
            var hp_c = cd.length;
            cd[hp_c] = setInterval(function(){
                main_status.bottles.hp_c--;
                h = main_status.bottles.hp_c/60*55;
                $('#action_health_bottle').addClass('disabled').next().css('height',h).css('margin-top',-h);
                if(main_status.bottles.hp_c == 0){
                    $('#action_health_bottle').removeClass('disabled');
                    clearInterval(cd[hp_c]);
                }
            },1000);
        }
        else{
            $('#action_health_bottle').removeClass('disabled');
        }
        if(main_status.bottles.mp_c > 0){
            var mp = main_status.bottles.mp_c/60*55;
            $('#action_mana_bottle').addClass('disabled').next().css('height',mp).css('margin-top',-mp);
            var mp_c = cd.length;
            cd[mp_c] = setInterval(function(){
                main_status.bottles.mp_c--;
                mp = main_status.bottles.mp_c/60*55;
                $('#action_mana_bottle').addClass('disabled').next().css('height',mp).css('margin-top',-mp);
                if(main_status.bottles.mp_c == 0){
                    $('#action_mana_bottle').removeClass('disabled');
                    clearInterval(cd[mp_c]);
                }
            },1000);
        }
        else{
            $('#action_mana_bottle').removeClass('disabled');
        };
        function Skills_cd(main_status,i){
            main_status.skills[i].cd_l--;
            var px = main_status.skills[i].cd_l/main_status.skills[i].cd*55;
            $('#action_skills .action_skill[id_skill="' + main_status.skills[i].id + '"] ').addClass('disabled').next().css('height',px).css('margin-top',-px);
            var skill_c = cd.length;
            cd[skill_c] = setInterval(function(){
                main_status.skills[i].cd_l -= 0.1;
                px = main_status.skills[i].cd_l/main_status.skills[i].cd*55;
                $('#action_skills .action_skill[id_skill="' + main_status.skills[i].id + '"] ').addClass('disabled').next().css('height',px).css('margin-top',-px);
                if(main_status.skills[i].cd_l <= 0.1){
                    clearInterval(cd[skill_c]);
                }
            },100);
        }
        for (var y = 0; y < main_status.skills.length; y++){
            if(main_status.skills[y].cd_l){
                Skills_cd(main_status,y)
            }
            else{
                $('#action_skills .action_skill[id_skill="' + main_status.skills[y].id + '"] ').removeClass('disabled');
            }
        };

    });
    main_socket.on('hp_mp',function(data){
        hp_mp(data);
    });








    //Приглашение в группу в через чат
    $('#user_list').on('click','.invite_to_group',function(){
        main_socket.emit('group',$(this).parent().prev().html(),function(callback){
            if(callback == -1){
                $('#notification_window').prepend("<div class='notification_window_1' style='margin-top: " + 30 + "px'>" +
                    "Невозможно приглашать чаще, чем раз в 30 сек!<br><button role='button' class='btn btn-link btn-block'" +
                    ">Ок</button></div> ").show();

            }
        });


    });
    //Входящее приглашение в группу
    main_socket.on('invite_to_group',function(nick){
        var time  = new Date();
        if(list_invite_time[nick] == undefined || list_invite_time[nick] + 30000 < time.getTime()){
            list_invite_time[nick] = time.getTime();
            var value = parseInt($('#notification_small').attr('value')) + 1;
            if(value > 3){
                $('.notification_window_2').last().remove();
                value = 3;
            }
            $('#notification_small').attr('value',value).html('Приглос!');
            $('#notification_window').prepend("<div class='notification_window_2' style='margin-top: " + 30 + "px'>" +
                nick + " приглашает Вас в группу!<br><button role='button' class='btn btn-link btn-block accept' type='group' name='" + nick + "'>Принять</button>" +
                "<button role='button' class='btn btn-link btn-block reject' type='group' name='" + nick + "'>Отклонить</button></div> ");
            enter_invite = setTimeout(function(){
                $('#notification_window').html('');
                $('#notification_small').attr('value',0).html('');
            },30000);
        }
        else{
            main_socket.emit('double_invite',nick);
        }
    });
    //При нажатии на кнопку уведомления
    $('#notification_small').click(function(){
        $('#notification_window').show();
    });
    //Ответ на инвайт
    $('#notification_window').on('click','.accept',function(){
        main_socket.emit('accept_' + $(this).attr('type') ,$(this).attr('name'));
        var value = parseInt($('#notification_small').attr('value'));
        $('#notification_small').attr('value',value - 1);
        $(this).parent().remove();
        if(value == 1){
            $('#notification_window').hide();
            $('#notification_small').html('');
        }
    }).on('click','.reject',function(){
        main_socket.emit('reject_' + $(this).attr('type') ,$(this).attr('name'));
        var value = parseInt($('#notification_small').attr('value'));
        $('#notification_small').attr('value',value - 1);
        $(this).parent().remove();
        if(value == 1){
            $('#notification_window').hide();
            $('#notification_small').html('');
        }
    });
    //Ошибка приглашения
    main_socket.on('invite_error',function(data){
        switch (data.type){
            case 'double':
                $('#notification_window').prepend("<div class='notification_window_1' style='margin-top: " + 30 + "px'>" +
                    "Невозможно приглашать чаще, чем раз в 30 сек!<br><button role='button' class='btn btn-link btn-block'" +
                    ">Ок</button></div> ").show();
                break;
            case 'reject':
                $('#notification_window').prepend("<div class='notification_window_1' style='margin-top: " + 30 + "px'>" +
                    data.nick + " отклонил Ваше приглашение!<br><button role='button' class='btn btn-link btn-block'" +
                    ">Ок</button></div> ").show();
        }
    });

    //Ping
    function ping(){
        setTimeout(function(){
            var startTime = Date.now();
            main_socket.emit('ping',function(){
                $('#ping').html((Date.now() - startTime) + 'мс');
                ping();
            });
        },1000);
    }
    //Double window
    main_socket.on('double_window',function(data){
        window.location = "../double_window.php?name=" + data;
    });
    //Online
    main_socket.on('online',function(value){
        $('#online_value').html(value);
    });

    $('#game').on('click' , '#res_to_town',function(){
        if($(this).attr('disabled')){
            console.log('Потерпи))');
        }
        else{
            main_socket.emit('resurrection','res',function(cb){
                $('#home').addClass('active').removeClass('disabled');
                $('#chat_main').show();
                Ajax('../town/main.php');
                main_socket.emit('change_chat','home',function(data){
                    updateChat(data);
                });
                hp_mp(cb);
            })
        }
    });

    function change_chat(location){
        main_socket.emit('change_chat',location,function(data){
            console.log('asdf');
        });
    }
});

//Остальные функции


$(document).ready(function(){
    $('#notification_green_close').click(function(){
        $('#notification_green').hide();
    });




    var party_count = parseInt($('#party').attr('summ'));
    switch (party_count){
        case 6:
            $('.party_char').addClass('party_5');
    }


});

$(document).ready(function(){
    $('#test_swipe').on('swipe',function(event){
        if($(this).hasClass('swipe')){
            $(event.target).removeClass("swipe");
        }
        else{
            $(event.target).addClass("swipe");
        }
    });
});