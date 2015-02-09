<?php
/**
 * Created by PhpStorm.
 * User: fenixsar
 * Date: 09.02.15
 * Time: 9:27
 */

echo '<div class="menu"><h3 style="text-align: center">Кузнец</h3></div>';

echo '<div class="row">';
echo '<div class="col-xs-4"><button id="location" class="btn-main" disabled>Купить</button></div>';
echo '<div class="col-xs-8" style="padding: 0 1px"><button id="sell_window" class="btn-main">Продать/Разобрать</button></div>';
//echo '<div class="col-xs-4"><button class="btn-footer" disabled>Создать</button></div>';
echo '</div>';

echo '<hr class="main">';

echo '<div class="row">';
echo '<div class="col-xs-4"><button class="btn-main" disabled>Чинить</button></div>';
echo '<div class="col-xs-4" style="padding: 0 1px"><button class="btn-main" disabled>Создать</button></div>';
echo '</div>';

echo '<div id="trade_area" class="row"></div>';

echo '</div>';