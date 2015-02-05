<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2/21/14
 * Time: 5:14 PM
 */
date_default_timezone_set("Europe/Moscow");
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
    if($user->getQuest($_GET['q'])){
        $user->cancelQuest($_GET['q']);
    }
    else
        header("Location: ../quests/?not=2");
}