<?php
date_default_timezone_set('Russia/Moscow');
include('../work/start.php');
/**
 * Created by PhpStorm.
 * User: root
 * Date: 6/8/14
 * Time: 8:29 PM
 */
session_start();
include('../main.php');
include('../work/check.php');
$user = new character($login,$pass);
$user_id = $user->searchForAuth();

if($user_id == 0){
    echo '<script type="text/javascript">
    window.location = "../index.php"
    </script>';
}
else {
    $title = 'Арена 1x1 - ' . $user->array['name'] . ' - ' . $title;
    $arena = true;
    include('../header.php');
    //Переадресация в бой
    echo '<body><div class="main"> ';
    include('../top.php');
    if ($user->array['health'] <= 0) echo '<div class="menu1"><a href="/?res=1" role="button" class="btn1 btn-link1 btn-block"><img src="../img/mob.png">Воскреснуть в городе</a></div>';
    echo '<div class="row" id="battle_id" name="1x1"><div id="Gen" class="marginLeft">
					<div class="block" id="rotate_01"></div>
					<div class="block" id="rotate_02"></div>
					<div class="block" id="rotate_03"></div>
					<div class="block" id="rotate_04"></div>
					<div class="block" id="rotate_05"></div>
					<div class="block" id="rotate_06"></div>
					<div class="block" id="rotate_07"></div>
					<div class="block" id="rotate_08"></div>
				</div><div id="timer_text" style="margin-top: 40px">Ожидание противника...</div></div>';


    echo '<div id="in_battle" name="0" class="row" style="display: none;">
        <h3 class="target" id="0">
            <span id="target_name"></span>[<span id="target_lvl"></span>]</h3>
        <div class="row"><div class="col-xs-3"></div> <div class="col-xs-6"><div class="progress">
        <div id="target_hp" reg="" hp = "0" class="progress-bar progress-bar-danger"
        role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0</div></div></div>
        <div id="hit_to_target" class="col-xs-3" style="height:20px;text-align:left;padding-left:5px; color:red"></div>
        </div>
        <div id="hit_target" class="btn btn-link btn-block" style="text-align: left; padding: 5px" wait="yes">
            <img src="../img/vxod.png"> Атаковать врага!</div>
    </div>';
}
include('../footer.php');