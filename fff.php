<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/28/14
 * Time: 12:54 AM
 */

include('main.php');
//Подключение к БД

$pas = md5('123123');
$user = new character('burgul',$pas);
$user->searchForAuth();
//$time = time();
//$user->updateAllStates(0);

include('header.php');
echo '<div class="main" location="Небеса">';
include('top.php');

echo '<div style="float: left;position: absolute;font-size: smaller"><img src="../img/skull.gif"><span id="round">
    </span></div><div id="round_progress" style="float: right;position: absolute"></div><h3 class="target" id="0">
    <span id="target_name">Неебический бык</span>[<span id="target_lvl">55</span>]<br><span id="target_bonus"
    style="color: limegreen">Берсерк</span></h3><div class="row"><div class="col-xs-3"></div><div class="col-xs-6">
    <div class="progress"><div id="target_hp" class="progress-bar progress-bar-danger" role="progressbar"
    aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">59446</div></div></div>
    <div id="hit_to_target" class="col-xs-3" style="height:20px;text-align:left;padding-left:5px; color:red"></div></div>';

echo '<div class="row">
    <div id="put_me" class="col-xs-12 visible-xs" who="left">Нажми</div>
    <div id="battle_left" class="col-sm-6 col-xs-11" style="background-color: red">asdf asdfs</div>
    <div id="battle_right" class="col-sm-6 col-xs-1" style="background-color: blue"><div class="row">asdfsafs as fasd fs s daf</div></div>

</div>';


echo '<script type="text/javascript">
        $("#put_me").click(function(){
            if($(this).attr("who") == "left"){
                $("#battle_left").removeClass("col-xs-11").addClass("col-xs-1");
                $("#battle_right").removeClass("col-xs-1").addClass("col-xs-11");
                $(this).attr("who","right");
            }
            else{
                $("#battle_right").removeClass("col-xs-11").addClass("col-xs-1");
                $("#battle_left").removeClass("col-xs-1").addClass("col-xs-11");
                $(this).attr("who","left");
            }
        });
    </script>';


include('footer.php');
