<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/29/14
 * Time: 3:10 PM
 */

require_once('../work/link_header.php');

if($user_id != 0){
    echo '<div class="row">
        <div class="col-xs-4"><button id="eqip" class="btn-main">Снаряжение</button></div>
        <div class="col-xs-4" style="padding: 0 1px"><button id="bag" class="btn-main">Рюкзак</button></div>
        <div class="col-xs-4"><button id="box" class="btn-main">Сундук</button></div>
    </div>
    <hr class="main">';

    $casket = $user->getCasket();
    $k = 0;
    for($i = 1; $i <= $user->array['casket']; $i++){
        if($casket[$i] != NULL){
            $k++;
        }
    }
    echo '<div class="menu"><h3>Шкатулка (' . $k . '/' . $user->array['casket'] . ')</h3></div><div class="row without_chat">';

    $i = 1;
    while($i < $user->array['casket']){
        If($casket[$i] != NULL){
            $stone = explode(",",$casket[$i]);

            echo '<div class="col-sm-6 col-xs-12" style="border: #666666 dotted 1px;"><button thing_id=' .
                $stone[1] . ' class="btn-thing"><p class="thing">';
            echo $stone[0] . " " . $stone[1] . " ур.</p>";
            echo "Количество: " . $stone[2];
            echo '</button></div>';
        }

        $i++;
    }
}