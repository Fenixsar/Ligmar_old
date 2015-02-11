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
        Ajax('../town/work/sell.php',undefined,"#things_area",function(){
            $('#items_list').html(wait);
            socket.emit("get_things","bag",function(things){
                if(things){
                    things.forEach(function(thing) {
                        thing.action_1 = {type: "sell"};
                        thing.action_2 = {type: "break"};
                        Ajax('../town/work/thing.php', thing, {selector: "#items_list", where: "append"}, function () {
                            $('#things_area').find("#wait_loading_game").remove();
                        });
                    });
                }
                else{
                    $('#items_list').find("#wait_loading_game").remove();
                }
            });
        });
    });
    //----------------------------------Герой----------------------------------------\\
    $('#game').on('click','#eqip',function(){
        $('#hero').removeClass('active');
        Ajax('../user/eqip_bag_box.php',{type:"Снаряжение"},undefined,function(){
            socket.emit("get_box_bag_stat","",function(stats){
                $("#count_bag").html("(" + stats.bag_count + "/" + stats.bag + ")");
                $("#count_box").html("(" + stats.box_count + "/" + stats.box + ")");
            });
            $('#items_list').html(wait);
            socket.emit("get_things","eqip",function(things){
                things.forEach(function(thing) {
                    if(thing.id_thing){
                        thing.thing.action_0 = 'eqip';
                        Ajax('../town/work/thing.php', thing.thing, {selector: "#items_list", where: "append"}, function () {
                            $('#items_list').find("#wait_loading_game").remove();
                        });
                    }
                    else if(thing.id_thing === null){
                        Ajax('../town/work/thing.php', thing, {selector: "#items_list", where: "append"}, function () {
                            $('#items_list').find("#wait_loading_game").remove();
                        });
                    }

                });
            });
        });
    }).on('click','#bag',function(){
        $('#hero').removeClass('active');
        Ajax('../user/eqip_bag_box.php',{type:"Рюкзак"},undefined,function(){
            socket.emit("get_box_bag_stat","",function(stats){
                $("#count_bag").html("(" + stats.bag_count + "/" + stats.bag + ")");
                $("#count_box").html("(" + stats.box_count + "/" + stats.box + ")");
            });
            $('#items_list').html(wait);
            socket.emit("get_things","bag",function(things){
                if(things){
                    things.forEach(function(thing) {
                        thing.action_1 = {type: "eqip"};
                        thing.action_2 = {type: "box"};
                        Ajax('../town/work/thing.php', thing, {selector: "#items_list", where: "append"}, function () {
                            $('#items_list').find("#wait_loading_game").remove();
                        });
                    });
                }
                else{
                    $('#items_list').find("#wait_loading_game").remove();
                }
            });
        });
    }).on('click','#box',function(){
        $('#hero').removeClass('active');
        Ajax('../user/eqip_bag_box.php',{type:"Сундук"},undefined,function(){
            socket.emit("get_box_bag_stat","",function(stats){
                $("#count_bag").html("(" + stats.bag_count + "/" + stats.bag + ")");
                $("#count_box").html("(" + stats.box_count + "/" + stats.box + ")");
            });
            $('#items_list').html(wait);
            socket.emit("get_things","box",function(things){
                if(things){
                    things.forEach(function(thing) {
                        thing.action_1 = {type: "eqip"};
                        thing.action_2 = {type: "bag"};
                        Ajax('../town/work/thing.php', thing, {selector: "#items_list", where: "append"}, function () {
                            $('#items_list').find("#wait_loading_game").remove();
                        });
                    });
                }
                else{
                    $('#items_list').find("#wait_loading_game").remove();
                }
            });
        });
    }).on('click','#casket',function(){
        $('#hero').removeClass('active');
        Ajax('../user/casket.php');
    });
    //----------------------------------Предметы----------------------------------------\\


    $('#game').on('click','.btn-thing',function(){
        $('#things_area').html(wait);
        resize();

        socket.emit("get_thing",$(this).attr('thing_id'),function(thing){
            console.log(thing);
            Ajax('../town/work/thing_full.php',thing,"#things_area");
        });
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
