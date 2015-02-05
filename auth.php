<?php
session_start();
include('work/start.php');
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/30/14
 * Time: 6:57 PM
 */
include('main.php');

$login = $_POST['login'];
$pass = md5($_POST['pass']);

$user = new character($login,$pass);
$auth = $user->searchForAuth();
if($auth == 0) {
    echo '<script type="text/javascript">
    window.location = "/?not=1"
    </script>';
}
else {
    $_POST['id'] = $auth;
    //Завершение авторизации
    include('header.php');
    echo '<body><div class="main"> ';
    echo '<h1 class="main">' .$name . '</h1>';
    echo '<div id="center"><img src="logo.jpg" width="250px"></div>';
    echo '<div><h2 class="enter">Вы успешно авторизованы, ' . $login . '!</h2></div>';
    echo '<div><hr class="zero"/>
    <a href="/" role="button" class="btn btn-link btn-block">Войти в игру</a>
    <hr class="zero"/></div>';

    include('footer.php');
    $_SESSION['login'] = $login;
    $_SESSION['pass'] = $pass;
    $_SESSION['id'] = $auth;
    setcookie("login", $login);
    setcookie("pass", $pass);
}