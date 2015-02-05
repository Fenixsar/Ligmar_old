<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/5/14
 * Time: 1:53 PM
 */
date_default_timezone_set('Russia/Moscow');
include('../work/start.php');

session_start();
include('../main.php');
include('../work/check.php');
$user = new character($login,$pass);
$user_id = $user->searchForAuth();

$title = 'Обратная связь - ' . $title;
include('../header.php');
echo '<body><div class="main"> ';
if($user_id == 0){

}
else {
    include('../top.php');
}

$user->sendFeedback($_POST['name'],$_POST['massage']);

echo 'Спасибо, Ваше сообщение отправлено!';
include ('../footer.php');