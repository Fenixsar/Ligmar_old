<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/27/14
 * Time: 11:53 AM
 */
//defined('PROTECTOR') or die('Error: restricted access');
$title_page = 1;
include ('work/check.php');
$user = new character($login,$pass);
$user_id = $user->searchForAuth();

if($user_id == 0){
    include ('work/login.php');
}
else {
    include('header.php');
    echo '<div id="main">';
    include('top.php');
    include('town/main.php');
    include('chat.php');

    include('footer.php');
}
//Футер

