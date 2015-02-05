<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/5/14
 * Time: 9:18 AM
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
    echo '<h1 class="main"><a class="not" href="../index.php">' .$name . '</a></h1>';
}
else {
    include('../top.php');
}
echo '<form method="POST" action="feedb.php">
    <p>Ваше имя:</p>
    <input type="text" name="name">
    <p>Сообщение:<br>
        <span style="font-size: 12px; color: green">Просим по возможности вставлять ссылку с скриншотом. Спасибо.</span>
    </p>
    <textarea name="massage" cols="40" rows="3"></textarea>
    <br>
    <br>
    <button type="submit">Отправить</button>


</form>';

include ('../footer.php');