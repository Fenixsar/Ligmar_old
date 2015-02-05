<?php
session_start();
setcookie("login", "");
setcookie("pass", "");
//$_COOKIE['login'];
//$_COOKIE['pass'];
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/30/14
 * Time: 9:12 PM
 */
unset ($_SESSION['login']);
unset ($_SESSION['pass']);

echo '<script type="text/javascript">
    window.location = "/"
    </script>';