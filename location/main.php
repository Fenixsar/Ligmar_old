<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/5/14
 * Time: 1:32 AM
 */

require_once('../work/link_header.php');

if($user_id != 0){
    $user->getLocation();
    echo '<div class="menu"><h3 style="text-align: center">Локации</h3></div>';

    echo '<div class="row">';
    $i = 0;
    while($user->loc[$i]){
        echo '<div class="row location" name="' . $user->loc[$i]['name'] . '"><button class="btn-main_2">
        <span class="lol" style="text-align: center; font-size:16px"><img src="../img/me4.png"> ' . $user->loc[$i]['name'] . '</span><br>
        <span style="font-weight: normal;">' . $user->loc[$i]['description'] . '</span></button>
    </div>';
        $i++;
    }

    echo '</div>';
}