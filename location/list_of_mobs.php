<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/6/14
 * Time: 4:24 PM
 */

require_once('../work/link_header.php');

if($user_id != 0){
    $mobs = $user->getMob($_POST['name']);
    echo '<div class="menu"><h3 style="text-align: center">' . $_POST['name'] . '</h3></div>';

    echo '<div class="row">';
    $i = 0;
    while($mobs[$i]){
        echo '<div class="row great_battle" battle_id="' . $mobs[$i]['id'] . '"><button class="btn-main_2">
        <span class="lol" style="text-align: center; font-size:16px"><img src="../img/me4.png"> ' . $mobs[$i]['name'] . '</span><br>
        <span style="font-weight: normal;">' . $mobs[$i]['description'] . '</span></button>
    </div>';
        $i++;
    }

    echo '</div>';
}