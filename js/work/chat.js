/**
 * Created by root on 6/16/14.
 */
function chat(socket){
    //Переменные
    var click = undefined; //Для переопределения
    //Functions
    function showOnlyActiveChatChanel(){
        var type = $('.chat_chanel').filter('.active').attr('name');
        $('.chat_message').hide();
        $('.' + type).show();
    }
    //Connect to the server
    //При удачном соединении
    socket.on('chat', function(data) {
        $('#wait_connect_chat').hide();
        $('#chat').show();
        if(data){
            var user = '';
            data = naturalSort(data);
            for(var i = 0; i < data.length; i++){
                user = user + '<button role="button" class="btn btn-link btn-block chat_list_char" style="text-align: left;' +
                    ' padding-left: 5px">' + data[i] + '</button><hr class="zero">';
            }
            $('#user_value').html(data.length);
            $("#user_list").html(user);
        }
        else{
            window.location = "../"
        }

    });
    //Обновление списка
    socket.on('chat_list_update',function(list){
        updateChat(list);
    });

    //Отправка сообщений
    function send_message(){
        if($('#field').val() != ''){
            if($('#butt').attr('click') != 'yes'){
                socket.emit('message_to_chat',$('#field').val(),function(cb){
                    if(cb.send == 'ok'){
                        var p_style = 'class="mess chat_message"',input_val = '', pre_mess = $("#name").html(),message = $('#field').val(),pre_mess_2 = '';
                        if(cb.name != undefined){
                            if(cb.name == 'party'){
                                p_style = 'class="chat_party chat_message"';
                                input_val = '/~ ';
                                message = message.substring(3);
                            }
                            else{
                                if(cb.name == 'guild'){
                                    p_style = 'class="chat_guild chat_message"';
                                    input_val = '/@ ';
                                    message = message.substring(3);
                                }
                                else{
                                    if(cb.name == '$'){
                                        p_style = 'class="chat_market chat_message"';
                                        input_val = '/$ ';
                                        message = message.substring(3);
                                    }
                                    else{
                                        p_style = 'class="chat_solo chat_message"';
                                        input_val = '/' + cb.name + ' ';
                                        pre_mess = cb.name;
                                        pre_mess_2 = 'Вы шепчете ';
                                        message = message.substring(cb.name.length + 2);
                                        $('.chat_chanel[name="chat_solo"]').attr('add','/' + cb.name + ' ');
                                    }
                                }
                            }
                        }
                        var timee = new Date();
                        var time = '[' + timee.getHours() + ':' + timee.getMinutes() + ':' + timee.getSeconds() + '] '+ pre_mess_2 + '<b class="char_link_for_chat" check="0">'
                            + pre_mess + '</b>: ';
                        $('#chat_place').prepend('<p ' + p_style +  '>' +time +  message + '</p>');
                        $('#field').val(input_val);
                        click = click_to_char();

                    }
                    else{
                        $('#chat_place').prepend('<p style="color: red">[Система]: ' +  cb.message + '</p>');
                    }
                    $('#butt').attr('click','no');
                })
            }
            $('#butt').attr('click','yes');
        }
    }
    var but = $('#butt');
    $('#field').keyup(function(e){
        if(e.which == 13){
            send_message();
        }
    });
    but.click(function(){
        send_message();
    });

    //Получение сообщений
    socket.on('message_from_chat',function(data){
        var p_style = 'class="mess chat_message"';
        if(data.type == '$'){
            p_style = 'class="chat_market chat_message"';
        }
        var timee = new Date();
        var time = '[' + timee.getHours() + ':' + timee.getMinutes() + ':' + timee.getSeconds() +
            '] <b class="char_link_for_chat" check="0">' + data['char'] + '</b>: ';
        $('#chat_place').prepend('<p ' + p_style + ' style="display:none">' +time +  data['text'] + '</p>');
        click = click_to_char();
        showOnlyActiveChatChanel();
    });
    //Получение личного сообщения
    socket.on('private_message',function(data){
        var timee = new Date();
        var time = '[' + timee.getHours() + ':' + timee.getMinutes() + ':' + timee.getSeconds() +
            '] <b class="char_link_for_chat" check="0">' + data['char'] + '</b> шепчет Вам: ';
        $('#chat_place').prepend('<p class="chat_solo chat_message" style="display:none">' +time +  data['text'] + '</p>');
        $('.chat_chanel[name="chat_solo"]').attr('add','/' + data['char'] + ' ');
        click = click_to_char();
        showOnlyActiveChatChanel();
    });
    //Системное сообщение
    socket.on('system_message',function(data){
        if(data.type == 'change_chat'){
            $('#chat_place').prepend('<p style="color: red">[Система]: Локальный чат изменился на: ' +  data.par + '</p>');
        }
        else{
            $('#chat_place').prepend('<p style="color: red">[Система]: ' +  data.message + '</p>');
        }
    });

    //Получение лога боя
    socket.on('chat_log',function(data){
        if(data.target == $("#name").html()){
            if(data.target_hp != undefined){
                var value = data.target_hp/(parseInt($('#health').attr('max'))/100);
                $('#health').html(parseInt(data.target_hp)).css('width',value + '%').attr('now',data.target_hp);
            }
        }

        var text = '';
        if(data.a == $("#name").html()){
            text = 'Вы ';
        }
        else{
            text = data.a + ' ';
        }
        if(data.hit.hit == 0){
            if(data.a == $("#name").html()){
                text += 'промахнулись.';
            }
            else{
                text += 'промахнулся по Вам.';
            }
        }
        else{
            if(data.a == $("#name").html()){
                text += 'ударили по ';
            }
            else{
                text += 'ударил по ';
            }
            if(data.target == $("#name").html()){
                text += 'Вам на <span style="color: chocolate">' + data.hit.dmg;
            }
            else{
                text += data.target + ' на <span>' + data.hit.dmg;
            }
            if(data.hit.strike == 1){
                text += ' Крит!</span>'
            }
        }

        var message = '<p class="chat_log chat_message" style="font-size: small;display: none">' + text + '</p>';
        $('#chat_place').prepend(message);
        showOnlyActiveChatChanel();
    });
    //Дроп
    socket.on('chat_drop',function(data){
        if(data.type == 'drop'){
            for(var i = 0; i < data.drop.length;i++){
                var add_class = 'chat_drop';
                var lvl = ' ';
                if(data.drop[i].lvl){
                    lvl = ' ' + data.drop[i].lvl + 'ур. ';
                }
                var text = "Вы нашли " + data.drop[i].name + lvl + '(' + data.drop[i].amount + ')';
                var message = '<p class="chat_log chat_message ' + add_class + '" style="font-size: small;display: none">' + text + '</p>';
                $('#chat_place').prepend(message);
                showOnlyActiveChatChanel();
            }
        }
        else{
            if(data.type == 'gold'){
                var text = 'Вы нашли ' + data.amount + ' монет!';
                var add_class = 'chat_gold';
            }
            if(data.type == 'exp'){
                if(data.amount.lvl_up){
                    var text = 'Вы получили ' + data.amount.lvl_up + ' уровень!';
                    $("#notification_green_text").html('Поздравляем!<br>Вы получили ' + data.amount.lvl_up + ' уровень!');
                    $("#notification_green").show();
                }
                else{
                    if (data.amount.exp_get > 0)
                        var text = 'Вы получили ' + data.amount.exp_get + ' опыта!';
                    else
                        var text = '';
                }
                var add_class = 'chat_exp';
                updateExp(data.amount);
            }
            if(data.type == 'item'){
                var text = 'Найдено: <span class="chat_item" id_item="' + data.id + '">' + data.name + '</span>';
                var add_class = 'chat_drop';
            }
            var message = '<p class="chat_log chat_message ' + add_class + '" style="font-size: small;display: none">' + text + '</p>';
            $('#chat_place').prepend(message);
            showOnlyActiveChatChanel();
        }
    });

    socket.on('disconnect', function(){
        $('#wait_connect').show();
        $('#square').hide();
    });


    var click_to_char = function(){
        if (screen.width >= 540){
            $(".char_link_for_chat[check='0']").on('click',function() {
                $('#field').val('/' + $(this).html() + ' ');
                var input = $('#field');
                input.setCursorPosition(input.val().length);
            }).attr('check','1');
        }
        else {
            $(".char_link_for_chat[check='0']").click(function() {
                var nick = $(this).html();
                $("#char_link_for_chat_2").click(function(){
                    $('#field').val('/' + nick + ' ');
                    var input = $('#field');
                    input.setCursorPosition(input.val().length);
                    $("#chat_add_window").hide();
                });
                $("#name_chat_target").html($(this).html());
                $("#chat_add_window").show();
            }).attr('check','1');
        }
    }
    //Закрытие откна действий в чате
    $("#close_chat_add_window").click(function(){
        $("#chat_add_window").hide();
    });

    //Установка курсора в конец импута
    $.fn.setCursorPosition = function(pos) {
        this.each(function(index, elem) {
            if (elem.setSelectionRange) {
                elem.setSelectionRange(pos, pos);
            } else if (elem.createTextRange) {
                var range = elem.createTextRange();
                range.collapse(true);
                range.moveEnd('character', pos);
                range.moveStart('character', pos);
                range.select();
            }
        });
        return this;
    };

    //Обновление опыта
    function updateExp(data){
        if(data.lvl_up){
            $('#char_lvl').html(data.lvl_up);
        }
        var exp = (data.exp - data.exp_was)/((data.exp_need - data.exp_was)/100);
        $('#exp_bar').css('width',exp + '%');
        $('#exp_amount').html(data.exp.toFixed(0) + '/' + data.exp_need + '(' + exp.toFixed(2) + '%)');
    }
};

function updateChat(list){
    var user = '';
    list = naturalSort(list);
    for(var i = 0; i < list.length; i++){
        user = user + '<button role="button" class="btn btn-link btn-block chat_list_char" style="text-align: left;' +
            ' padding-left: 5px">' + list[i] + '</button><hr class="zero">';
    }
    $('#user_value').html(list.length);
    $("#user_list").html(user);
}


//Остальные обработчики
$(document).ready(function(){
    $('.chat_chanel').click(function(){
        $('.chat_chanel').removeClass('active');
        $(this).addClass('active');
        $('.chat_message').hide();
        $('.' + $(this).attr('name')).show();
        $('#field').val($(this).attr('add'));
        var input = $('#field');
        input.setCursorPosition(input.val().length);
    });
    //Список пользователей
    $('#user_list').on('click','.chat_list_char',function(){
        if($(this).hasClass('active')){
            $(this).removeClass('active');
            $('.chat_char_toolbar').hide();
        }
        else{
            $('#user_list .chat_list_char').removeClass('active');
            $('.chat_char_toolbar').hide();
            if($(this).html() != $("#name").html()) {
                $(this).addClass('active').after('<div class="row chat_char_toolbar">' +
                    '<button role="button" class="btn btn-link btn-block" style="text-align: center;">Профиль</button><hr style="border-top: 1px solid" class="zero">' +
                    '<button role="button" class="btn btn-link btn-block chat_write_to_char" style="text-align: center;">Написать</button><hr style="border-top: 1px solid" class="zero">' +
                    '<button role="button" class="btn btn-link btn-block invite_to_group" style="text-align: center;">Группа</button><hr style="border-top: 1px solid" class="zero"></div>');
            }
        }
    }).on('click','.chat_write_to_char',function(){  //Написать
        var input = $('#field').val('/' + $(this).parent().prev().html() + ' ');
        input.setCursorPosition(input.val().length);
    });

});


