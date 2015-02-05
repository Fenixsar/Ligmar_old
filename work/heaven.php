<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/26/14
 * Time: 1:54 PM
 */

require_once('../work/link_header.php');

if($user_id != 0){
    echo '<div class="menu"><h3 style="text-align: center">Небеса</h3></div>';
    echo '<div class="row" style="text-align: center"><div class="col-xs-12">Время до воскрешения: <span id="res_time_remaining">0:0</span></div>
    <div class="col-xs-6"><div id="res_to_town" class="btn btn-link btn-block" style="text-align: center; padding: 5px" disabled>Воскреснуть</div></div>
    <div class="col-xs-6"><div id="use_rune_life" class="btn btn-link btn-block" style="text-align: center; padding: 5px" disabled>Руна Жизни</div></div></div>';
}