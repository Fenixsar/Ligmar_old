<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/6/14
 * Time: 2:23 PM
 */

//require_once('../work/link_header.php');
//
//if($user_id != 0){
    echo '<div class="menu"><h3 style="text-align: center">Зион</h3></div>';
    echo '<div style="margin-bottom: 15px;text-align: center"><img src="../img/gorod2.png" width="250px"></div>';

    echo '<div class="menu"><h3>Сражения</h3></div>';

    echo '<div class="row">';
    echo '<div class="col-xs-4"><button id="location" class="btn-main">Локации</button></div>';
    echo '<div class="col-xs-4" style="padding: 0 1px"><button class="btn-main" disabled>Арена</button></div>';
    //echo '<div class="col-xs-4"><button class="btn-footer">Герой</button></div>';
    echo '</div>';

    echo '<div class="menu"><h3>Город</h3></div>';

    echo '<div class="row">';
    echo '<div class="col-xs-4"><button class="btn-main" disabled>Старейшина</button></div>';
    echo '<div class="col-xs-4" style="padding: 0 1px"><button class="btn-main" disabled>Торговец</button></div>';
    echo '<div class="col-xs-4"><button class="btn-main" disabled>Искатель</button></div>';
    echo '</div>';

    echo '<hr class="main">';

    echo '<div class="row">';
    echo '<div class="col-xs-4"><button class="btn-main" disabled>Кузнец</button></div>';
    echo '<div class="col-xs-4" style="padding: 0 1px"><button class="btn-main" disabled>Портной</button></div>';
    echo '<div class="col-xs-4"><button class="btn-main" disabled>Ремесленник</button></div>';
    echo '</div>';

    echo '</div>';
//}