<?php
echo '<div class="row without_chat">';
echo '<div class="row">
        <div class="col-xs-4">
            <button id="eqip" class="btn-main">Снаряжение</button>
        </div>
        <div class="col-xs-4">
            <button id="bag" class="btn-main">Рюкзак <span id="count_bag"></span></button>
        </div>
        <div class="col-xs-4">
            <button id="box" class="btn-main">Сундук <span id="count_box"></span></button>
        </div>
    </div>';

echo '<div id="things_area" class="row">';
echo '<div class="menu"><h3>' . $_POST['type'] . '</h3></div>';
echo '<div id="items_list" class="row"></div>';
echo '</div>';

echo '<hr class="main">';

echo '</div>';