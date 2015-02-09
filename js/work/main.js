/**
 * Created by root on 8/1/14.
 */
//Высота документа
var ua = navigator.userAgent.toLowerCase();
var isOpera = (ua.indexOf('opera')  > -1);
var isIE = (!isOpera && ua.indexOf('msie') > -1);

function getDocumentHeight() {
    return Math.max(document.compatMode != 'CSS1Compat' ? document.body.scrollHeight : document.documentElement.scrollHeight, getViewportHeight());
}

function getViewportHeight() {
    return ((document.compatMode || isIE) && !isOpera) ? (document.compatMode == 'CSS1Compat') ? document.documentElement.clientHeight : document.body.clientHeight : (document.parentWindow || document.defaultView).innerHeight;
}

function resize(){
    var chat_height = parseInt($('#chat_main').css('height'));
    if($('#chat_main').css('display') == 'none'){
        chat_height = 0;
    }
    if(parseInt($('#game').css('height')) + chat_height > getViewportHeight() - parseInt($('#footer').css('height')) - parseInt($('#top').css('height'))){
        $('html').css('height',"auto");
        $('body').css('min-height',"auto");
    }
    else{
        $('html').css('height',"100%");
        $('body').css('min-height',"100%");
    }
}
//------------------------------------------------------
var wait = '<div id="wait_loading_game"><div id="Gen" class="row">' +
    '<div class="block" id="rotate_01"></div>' +
    '<div class="block" id="rotate_02"></div>' +
    '<div class="block" id="rotate_03"></div>' +
    '<div class="block" id="rotate_04"></div>' +
    '<div class="block" id="rotate_05"></div>' +
    '<div class="block" id="rotate_06"></div>' +
    '<div class="block" id="rotate_07"></div>' +
    '<div class="block" id="rotate_08"></div>' +
    '</div><div id="timer_text" class="row">Загрузка...</div></div>';

function Ajax(url,param,selector,callback){
    if(selector == undefined){
        selector = "#game";
    }
    $('#game').css('height',$('#game').css('height'));
    $.ajax({
        beforeSend: function(){
            $(selector).html(wait);
        },
        url: url,
        type: 'POST',
        data: param,
        error: function(){
            $(selector).html('<span style="color: red">При загрузке произошла ошибка, обновите страницу.</span>');
        },
        success: function(data){
            //-----------------------В зависимости от полученных данных--------------------\\
            if (data == 'box'){
                Ajax('../user/box.php');
            }
            else if(data == 'bag'){
                Ajax('../user/bag.php');
            }
            else if (data == 'eqip'){
                main_socket.emit('updateAllStats','0');
                Ajax('../user/eqip.php');
            }
            else{
                $(selector).html(data);
                $('#game').css('height','auto');
            }
            //-----------------------В зависимости от передаваемого параметра---------------\\
            if(param == 'hero'){
                main_socket.emit('get_character', 0 , function(data){
                    $('#header_name_hero').html(data.name);
                    $('#header_class_hero').html(data.class);
                    $('#header_level_hero').html(data.level);
                    if(data.str != data.str_self) $('#strength_hero').css('color','dodgerblue');
                    $('#strength_hero').html(data.str);
                    if(data.dex != data.dex_self) $('#dexterity_hero').css('color','dodgerblue');
                    $('#dexterity_hero').html(data.dex);
                    if(data.intel != data.intel_self) $('#intelligence_hero').css('color','dodgerblue');
                    $('#intelligence_hero').html(data.intel);
                    if(data.vit != data.vit_self) $('#vitality_hero').css('color','dodgerblue');
                    $('#vitality_hero').html(data.vit);
                    $('#dmg_hero').html(data.dmg_min + '-' + data.dmg_max);
                    $('#add_mag_dmg_hero').html(data.dmg_mag);
                    $('#accur_hero').html(data.accuracy);
                    $('#strike_hero').html(data.strike);
                    $('#def_hero').html(data.def);
                    $('#resist_hero').html(data.resist);
                    $('#dodge_hero').html(data.dodge);
                    $('#dmg_strike_hero').html(Math.round(data.dmg_strike*100) + '%');
                    $('#health_bottles_hero').html(data.hp_b);
                    $('#mana_bottles_hero').html(data.mp_b);
                    $('#wood_hero').html(data.wood);
                    $('#ore_hero').html(data.ore);
                    $('#thread_hero').html(data.thread);
                    $('#leather_hero').html(data.leather);
                });
            }
            else if (param == 'death'){
                $('#home').removeClass('active').addClass('disabled');
                main_socket.emit('death_info','1',function(death_info){

                    if(death_info < 0){
                        death_info = 0;
                    }
                    var time_m = death_info;
                    death_info = new Date(death_info);
                    $('#res_time_remaining').html(death_info.getMinutes() + ':' + death_info.getSeconds());
                    var res_time = setInterval(function(){
                        time_m -= 1000;
                        if(time_m <= 0){
                            time_m = 0;
                            clearInterval(res_time);
                            $('#res_to_town').removeAttr('disabled');
                        }
                        death_info = new Date(time_m);
                        $('#res_time_remaining').html(death_info.getMinutes() + ':' + death_info.getSeconds());
                    },1000)
                });
            }
            else if (param != undefined){
                if(param.to == 'eqip'){
                    main_socket.emit('updateAllStats','0');
                }
            }
            resize();
            if (callback) {
                callback();
            }
        }
    });
};

$(document).ready(function(){
    resize();


    //Время
    var time = new Date();
    $('#server_time').html(time.getHours() + ':' + time.getMinutes() + ':' + time.getSeconds());
    setInterval(function(){
        time = new Date();
        $('#server_time').html(time.getHours() + ':' + time.getMinutes() + ':' + time.getSeconds());
    },1000);




    //BK и высота
    if(getViewportHeight() == 600){
        $('#game').css('height', getViewportHeight() - parseInt($('#footer').css('height')) - parseInt($('#top').css('height'))).css('overflow','auto');
    }
    else{
        window.onresize = function(){
            resize();
        };

    }
});