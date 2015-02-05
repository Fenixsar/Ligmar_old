<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2/21/14
 * Time: 2:31 PM
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
    $user->getQuest($_GET['q']);
    if($user->activQuest($_GET['q']) == 1)
        header("Location: ../quests/");
    else
        header("Location: ../quests/?not=1");
}