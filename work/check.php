<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2/2/14
 * Time: 4:08 PM
 */
session_start();
$user_id = 0; //гость
if (isset ($_SESSION['login']) && isset ($_SESSION['pass'])) {
    $login = $_SESSION['login'];
    $pass = $_SESSION['pass'];
}
// //////////////////////////////////////////////////////////
// Авторизация по COOKIE                                   //
// //////////////////////////////////////////////////////////
elseif (isset ($_COOKIE['login']) && isset ($_COOKIE['pass'])) {
    $login = base64_decode($_COOKIE['login']);
    $_SESSION['login'] = $login;
    $pass = $_COOKIE['pass'];
    $_SESSION['pass'] = $pass;
}

$title = 'Ligmar - Браузерная массовая многопользовательская онлайн игра в рельном времени';