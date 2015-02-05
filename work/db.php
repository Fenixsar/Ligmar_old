<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/26/14
 * Time: 8:23 PM
 */
//defined('PROTECTOR') or die('Error: restricted access');

$db_host = "localhost";
$db_user = "root";
$db_name = "game";
$db_pass = "53Hpontar";
//Подключение к БД
try {
    $db = new PDO("mysql:host=$db_host; dbname=$db_name", $db_user, $db_pass);
    $db->exec('SET NAMES utf8');
}
catch (PDOException $e) {
    echo $e->getMessage();
}

include('protect.php');

$stop_injection = new InitVars();
$stop_injection->checkVars();
?>