<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/26/14
 * Time: 7:12 PM
 */
//defined('PROTECTOR') or die('Error: restricted access');
$name = 'Ligmar';

echo '<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>'. $title .'</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../favicon.png" rel="shortcut icon" type="image/x-icon" />
    <!-- Bootstrap -->
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/mybootstrap.css" rel="stylesheet">
    <link href="../css/css.css" rel="stylesheet">
    <script type="text/javascript" src="../js/jquery-2.1.0.min.js"></script>
    <script type="text/javascript" src="../js/work/sort.js"></script>';

    echo '<script type="text/javascript" src="../js/work/links.js"></script>';
    echo '<script type="text/javascript" src="../js/work/chat.js"></script>';
    echo '<script type="text/javascript" src="../js/work/battle.js"></script>';


echo '<script type="text/javascript" src="../js/jquery.mobile.custom.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="../js/socket.io.js"></script>
    <script type="text/javascript" src="../js/work/work.js"></script>';

echo '<script type="text/javascript" src="../js/work/main.js"></script>';

    echo '<link href="../css/loading.css" rel="stylesheet">';

echo '</head>';
echo "<body><div id='notification_window' style='display: none'></div>";
