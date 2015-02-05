<?php
date_default_timezone_set('Russia/Moscow');
include('../work/start.php');
/**
 * Created by PhpStorm.
 * User: root
 * Date: 6/8/14
 * Time: 8:20 PM
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
    $title = 'Арена - ' . $user->array['name'] . ' - ' . $title;
    include('../header.php');
    echo '<body><div class="main"> ';
    include('../top.php');
    if ($_GET['return'] == 1){
        echo '<div class="row"><div class="col-xs-0 col-sm-3"></div><div class="col-xs-12 col-sm-6 text-center"
            style="border: limegreen dotted 1px; background-color: forestgreen">Противник сбежал...<br>Вы получаете: ' . $_GET['exp'] . ' опыта</div></div>';
    }
    elseif ($_GET['return'] == 2){
        echo '<div class="row"><div class="col-xs-0 col-sm-3"></div><div class="col-xs-12 col-sm-6 text-center"
            style="border: limegreen dotted 1px; background-color: forestgreen">Вы одержали победу!.<br>Вы получаете: ' . $_GET['exp'] . ' опыта</div></div>';
    }
//    if(!$boo)
//        echo '<div id="center" style="margin-bottom: 15px"><img src="../img/z4.jpg" width="170px"></div>';
    echo '<div class="menu"><h3>Арена</h3></div>';
    echo '<div class="menu1"><a href="1x1.php" role="button" class="btn1 btn-block"><img src="../img/me4.png"> 1x1</a></div>';

}
include('../footer.php');