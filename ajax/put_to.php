<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/25/14
 * Time: 1:04 PM
 */
require_once('../work/link_header.php');

if($_POST['t'] && $_POST['to']){
    $check = $user->searchThing($_POST['t']);
    If ($check == 0){
        echo "box";
    }
    elseif($check == 1){
        echo "bag";
    }
    elseif($check == 2){
        echo "eqip";
    }

    echo $user->putTo($_POST['to'],$_POST['t']);
}