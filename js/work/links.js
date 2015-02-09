/**
 * Created by root on 8/6/14.
 */
function links(socket){
    //---------------------------------Локации--------------------------------------\\
    $('#game').on('click','#location',function(){
        $('#home').removeClass('active');
        Ajax('../location/main.php');
    }).on('click', '.location', function(){
        socket.emit('change_chat',$(this).attr('name'),function(data){
            updateChat(data);
        });
        Ajax('../location/list_of_mobs.php',{name: $(this).attr('name')});
    });
    //---------------------------------Город--------------------------------------\\
    $('#game').on('click','#smith',function(){
        $('#home').removeClass('active');
        Ajax('../town/smith.php');
    });
    $('#game').on('click','#sell_window',function(){
        $('#home').removeClass('active');
        Ajax('../town/work/sell.php',undefined,"#trade_area");
    });
    //----------------------------------Герой----------------------------------------\\
    $('#game').on('click','#eqip',function(){
        $('#hero').removeClass('active');
        Ajax('../user/eqip.php');
    }).on('click','#bag',function(){
        $('#hero').removeClass('active');
        Ajax('../user/bag.php');
    }).on('click','#box',function(){
        $('#hero').removeClass('active');
        Ajax('../user/box.php');
    }).on('click','#casket',function(){
        $('#hero').removeClass('active');
        Ajax('../user/casket.php');
    });
    //----------------------------------Предметы----------------------------------------\\
    $('#game').on('click','.btn-thing',function(){
        Ajax('../user/thing.php',{t:$(this).attr('thing_id')});
    }).on('click','.put_to',function(){
        Ajax('../ajax/put_to.php',{t:$(this).attr('thing_id'), to:$(this).attr('to')});
    });






    //----------------------------------Футер----------------------------------------\\
    $('#home').click(function(){
        if($('#home').html() == 'Покинуть бой'){
            socket.emit('leave_battle', 0,function(loc){
                Ajax('../location/list_of_mobs.php',{name: loc});
                $('#home').html('Город');
            });
            $('#chat_main').show();
        }
        else{
            socket.emit('change_chat','home',function(data){
                updateChat(data);
            });
            $('.btn-footer').removeClass('active');
            $('#home').addClass('active');
            $('#chat_main').show();
            Ajax('../town/main.php');
        }
    });
    $('#hero').click(function(){
        $('.btn-footer').removeClass('active');
        $('#hero').addClass('active');
        $('#chat_main').hide();
        Ajax('../user/main.php','hero');
    });
}
