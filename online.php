<?php
date_default_timezone_set('Russia/Moscow');
include('work/start.php');
/**
 * Created by PhpStorm.
 * User: root
 * Date: 3/5/14
 * Time: 11:53 PM
 */
session_start();
include('main.php');
include('work/check.php');
$user = new character($login,$pass);
$user_id = $user->searchForAuth();

if($user_id == 0){
    echo '<script type="text/javascript">
    window.location = "/"
    </script>';
}
else {
    $title = 'Кто онлайн';
    include('header.php');
    echo '<body><div class="main"> ';
    include('top.php');
    echo '<div class="menu"><h3>Кто онлайн</h3></div>';
    if(isset($_GET['page'])){
        $page = $_GET['page'];
    }
    else{
        $page = 1;
    }
    $list = $user->getOnlineList($page,10);
    $i = 0;
    while(isset($list[$i])){
        echo "<div>{$list[$i]}</div>";
        $i++;
    }

    include('footer.php');
}
