<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/8/14
 * Time: 1:25 PM
 */

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
