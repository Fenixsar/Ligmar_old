<?php
echo '<hr class="main">';

if ($_POST['thing_1']['name']){
    echo '<div class="col-sm-6 col-xs-12">';
    echo '<div><p style="font-size: 16px; font-weight: bold">';
    //Определяем тип вещи
    if($_POST['thing_1']['kind'] == 1){
        echo '<span>';
    }
    elseif($_POST['thing_1']['kind'] == 2){
        echo '<span style="color: cornflowerblue">';
    }
    elseif($_POST['thing_1']['kind'] == 3){
        echo '<span style="color: mediumpurple">';
    }
    echo $_POST['thing_1']['name'];
    //Количество ячеек и точка
    if ($_POST['thing_1']['socket'])
        echo '<span style="font-weight:normal"> (яч: ' . $_POST['thing_1']['socket'] . ')';
    if ($_POST['thing_1']['improvement'])
        echo ' +' . $_POST['thing_1']['improvement'] . '</span>';
    echo '</span></p></div>';
    //Основное описание
    echo '<div style="border-bottom: #666666 dotted 1px; margin-bottom: 3px"><div style="float: left; padding: 8px; height: 46px">
        <img src="' . $_POST['thing_1']['img'] . '"></div>';
    echo '<div><p class="line">Тип: ' . $_POST['thing_1']['type'] .'</p>
        <p class="line">Уровень: ' . $_POST['thing_1']['lvl'] .'</p>
        <p class="line">';
    //Основной показатель
    if ($_POST['thing_1']['main_type'] == 'weapon')
        echo 'Урон: ' . $_POST['thing_1']['self_dmg_min'] .'-' . $_POST['thing_1']['self_dmg_max'];
    elseif($_POST['thing_1']['main_type'] == 'head'){
        if($_POST['thing_1']['type'] == 'Тяжелый шлем'){
            echo 'Здоровье: ' . $_POST['thing_1']['self_hp'];
        }
        else{
            echo 'Мана: ' . $_POST['thing_1']['self_mana'];
        }
    }
    elseif($_POST['thing_1']['main_type'] == 'shoulders'){
        echo 'Уклонение: ' . $_POST['thing_1']['self_dodge'];
    }
    elseif($_POST['thing_1']['main_type'] == 'neck'){
        if($_POST['thing_1']['type'] == 'Ожерелье силы'){
            echo 'Защита: ' . $_POST['thing_1']['self_def'];
        }
        elseif($_POST['thing_1']['type'] == 'Магическое ожерелье'){
            echo 'Защита: ' . $_POST['thing_1']['self_resist'];
        }
        else{
            echo 'Уклонение: ' . $_POST['thing_1']['self_dodge'];
        }
    }
    elseif($_POST['thing_1']['main_type'] == 'belt'){
        if($_POST['thing_1']['type'] == 'Ремень'){
            echo 'Защита: ' . $_POST['thing_1']['self_def'] . '</p>';
        }
        elseif($_POST['thing_1']['type'] == 'Сумка'){
            echo 'Маг. защита: ' . $_POST['thing_1']['self_resist'] . '</p>';
        }
        else{
            echo 'Уклонение: ' . $_POST['thing_1']['self_dodge'] . '</p>';
        }
    }
    elseif($_POST['thing_1']['main_type'] == 'finger'){
        echo 'Урон: ' . $_POST['thing_1']['self_dmg'];
    }
    else{
        echo 'Защита: ' . $_POST['thing_1']['self_def'] . '<br>Маг. защита:' . $_POST['thing_1']['self_resist'];
    }
    echo '</p></div></div><div style="border-bottom: #666666 dotted 1px; margin-bottom: 3px">';
    //Требуемые параметры
    echo '<p class="line"';
    if ($_POST['thing_1']['check']['class'] == 'false'){
        echo ' style="color:red"';
    }
    echo '>Класс: ' . $_POST['thing_1']['class'] . '</p>';
    if ($_POST['thing_1']['n_lvl']) {
        echo '<p class="line"';
        if ($_POST['thing_1']['check']['lvl'] == 'false'){
            echo ' style="color:red"';
            $not = 1;
        }
        echo '>Требуемый уровень: ' . $_POST['thing_1']['n_lvl'] . '</p>';
    }
    if ($_POST['thing_1']['n_str']) {
        echo '<p class="line"';
        if ($_POST['thing_1']['check']['str'] == 'false'){
            echo ' style="color:red"';
            $not = 1;
        }
        echo '>Требуемая сила: ' . $_POST['thing_1']['n_str'] . '</p>';
    }
    if ($_POST['thing_1']['n_dex']) {
        echo '<p class="line"';
        if ($_POST['thing_1']['check']['dex'] == 'false'){
            echo ' style="color:red"';
            $not = 1;
        }
        echo '>Требуемая ловкость: ' . $_POST['thing_1']['n_dex'] . '</p>';
    }
    if ($_POST['thing_1']['n_int']) {
        echo '<p class="line"';
        if ($_POST['thing_1']['check']['intel'] == 'false'){
            echo ' style="color:red"';
            $not = 1;
        }
        echo '>Требуемый интелект: ' . $_POST['thing_1']['n_int'] . '</p>';
    }
    echo '</div>';
    // Прочность
    echo '<div style="border-bottom: #666666 dotted 1px; margin-bottom: 3px;">';
    echo '<p class="line">Прочность: ' . $_POST['thing_1']['self_dur_now'] . '/' . $_POST['thing_1']['self_dur'] . '</p>';
    echo '</div>';
    //Дополнительыне статы
    if($_POST['thing_1']['kind'] != 1){
        echo '<div style="border-bottom: #666666 dotted 1px; margin-bottom: 3px;">';
        if(isset($_POST['thing_1']['str']) && $_POST['thing_1']['str'] != 0) echo '<p class="line2">Сила +' . $_POST['thing_1']['str'] . '</p>';
        if(isset($_POST['thing_1']['dex']) && $_POST['thing_1']['dex'] != 0) echo '<p class="line2">Ловкость +' . $_POST['thing_1']['dex'] . '</p>';
        if(isset($_POST['thing_1']['vit']) && $_POST['thing_1']['vit'] != 0) echo '<p class="line2">Выносливость +' . $_POST['thing_1']['vit'] . '</p>';
        if(isset($_POST['thing_1']['intel']) && $_POST['thing_1']['intel'] != 0) echo '<p class="line2">Интелект +' . $_POST['thing_1']['intel'] . '</p>';
        if(isset($_POST['thing_1']['hp']) && $_POST['thing_1']['hp'] != 0) echo '<p class="line2">Здоровье +' . $_POST['thing_1']['hp'] . '</p>';
        if(isset($_POST['thing_1']['hp_reg']) && $_POST['thing_1']['hp_reg'] != 0) echo '<p class="line2">Регенерация здоровья +' . $_POST['thing_1']['hp_reg'] . '</p>';
        if(isset($_POST['thing_1']['mp']) && $_POST['thing_1']['mp'] != 0) echo '<p class="line2">Мана +' . $_POST['thing_1']['mp'] . '</p>';
        if(isset($_POST['thing_1']['mp_reg']) && $_POST['thing_1']['mp_reg'] != 0) echo '<p class="line2">Регенерация маны +' . $_POST['thing_1']['mp_reg'] . '</p>';
        if(isset($_POST['thing_1']['def']) && $_POST['thing_1']['def'] != 0) echo '<p class="line2">Защита +' . $_POST['thing_1']['def'] . '</p>';
        if(isset($_POST['thing_1']['def_perc']) && $_POST['thing_1']['def_perc'] != 0) echo '<p class="line2">Защита +' . $_POST['thing_1']['def_perc'] . '%</p>';
        if(isset($_POST['thing_1']['resist']) && $_POST['thing_1']['resist'] != 0) echo '<p class="line2">Сопротивление магии +' . $_POST['thing_1']['resist'] . '</p>';
        if(isset($_POST['thing_1']['dmg']) && $_POST['thing_1']['dmg'] != 0) echo '<p class="line2">Урон +' . $_POST['thing_1']['dmg'] . '</p>';
        if(isset($_POST['thing_1']['dmg_mag']) && $_POST['thing_1']['dmg_mag'] != 0) echo '<p class="line2">Магический урон +' . $_POST['thing_1']['dmg_mag'] . '</p>';
        if(isset($_POST['thing_1']['dmg_min']) && $_POST['thing_1']['dmg_min'] != 0) echo '<p class="line2">Минимальный урон +' . $_POST['thing_1']['dmg_min'] . '</p>';
        if(isset($_POST['thing_1']['dmg_max']) && $_POST['thing_1']['dmg_max'] != 0) echo '<p class="line2">Максимальный урон +' . $_POST['thing_1']['dmg_max'] . '</p>';
        if(isset($_POST['thing_1']['dmg_perc']) && $_POST['thing_1']['dmg_perc'] != 0) echo '<p class="line2">Урон +' . $_POST['thing_1']['dmg_perc'] . '%</p>';
        if(isset($_POST['thing_1']['strike']) && $_POST['thing_1']['strike'] != 0) echo '<p class="line2">Крит +' . $_POST['thing_1']['strike'] . '%</p>';
        if(isset($_POST['thing_1']['hp_still']) && $_POST['thing_1']['hp_still'] != 0) echo '<p class="line2">Похищение здоровья +' . $_POST['thing_1']['hp_still'] . '</p>';
        if(isset($_POST['thing_1']['mp_still']) && $_POST['thing_1']['mp_still'] != 0) echo '<p class="line2">Похищение маны +' . $_POST['thing_1']['mp_still'] . '</p>';
        if(isset($_POST['thing_1']['dodge']) && $_POST['thing_1']['dodge'] != 0) echo '<p class="line2">Уворот +' . $_POST['thing_1']['dodge'] . '</p>';
        if(isset($_POST['thing_1']['accur']) && $_POST['thing_1']['accur'] != 0) echo '<p class="line2">Меткость +' . $_POST['thing_1']['accur'] . '</p>';
        if(isset($_POST['thing_1']['dur']) && $_POST['thing_1']['dur'] != 0) echo '<p class="line2">Прочность +' . $_POST['thing_1']['dur'] . '%</p>';
        if(isset($_POST['thing_1']['n_stats']) && $_POST['thing_1']['n_stats'] != 0) echo '<p class="line2">Снижение требуемых характеристик -' . $_POST['thing_1']['n_stats'] . '%</p>';
        echo '</div>';
    }

    if ($_POST['thing_1']['q'] == 1){
        echo '<div style="border-bottom: #666666 dotted 1px; margin-bottom: 3px">
                <p style="color: #00F5FF;">Награда. Нельзя улучшить или передать.</p></div>';
    }
    $disabled_1 = "disabled";
    $disabled_2 = "disabled";
    //Если предмет находится в рюкзаке
    if (isset($_POST['thing_1']['eqip']) && isset($_POST['thing_1']['box'])){
        $action_1 = "Надеть";
        $to_1 = "to='eqip'";
        if(!($_POST['thing_1']['eqip'] == "false")){
            $disabled_1 = "";
        }

        $action_2= "В сундук";
        $to_2 = "to='box'";
        if(!($_POST['thing_1']['box'] == "false")){
            $disabled_2 = "";
        }
    }
    //Если предмет находится в сундуке
    if (isset($_POST['thing_1']['eqip']) && isset($_POST['thing_1']['bag'])){
        $action_1 = "Надеть";
        $to_1 = "to='eqip'";
        if(!($_POST['thing_1']['eqip'] == "false")){
            $disabled_1 = "";
        }

        $action_2= "В рюкзак";
        $to_2 = "to='bag'";
        if(!($_POST['thing_1']['bag'] == "false")){
            $disabled_2 = "";
        }
    }

    //Если предмет находится в снаряжении
    if (isset($_POST['thing_1']['box']) && isset($_POST['thing_1']['bag'])){
        $action_1 = "В рюкзак";
        $to_1 = "to='bag'";
        if(!($_POST['thing_1']['bag'] == "false")){
            $disabled_1 = "";
        }

        $action_2= "В сундук";
        $to_2 = "to='box'";
        if(!($_POST['thing_1']['box'] == "false")){
            $disabled_2 = "";
        }
    }
    if(isset($_POST['from'])){
        $to_1 = $to_1 . ' from="' . $_POST['from'] . '"';
        $to_2 = $to_2 . ' from="' . $_POST['from'] . '"';
    }


    echo '<div class="row">
                <div class="col-sm-6 col-xs-6">
                    <button ' . $to_1 . ' class="btn-main put_to" ' . $disabled_1 . ' thing_id=' . $_POST['thing_1']['id'] . '>' . $action_1 . '</button>
                </div>
                <div class="col-sm-6 col-xs-6">
                    <button ' . $to_2 . ' class="btn-main put_to" ' . $disabled_2 . ' thing_id=' . $_POST['thing_1']['id'] . '>' . $action_2 . '</button>
                </div>
            </div>';
    echo '</div>';

    if (isset($_POST['thing_2'])){
        echo '<div class="col-sm-6 col-xs-12">';
        echo '<div><p style="font-size: 16px; font-weight: bold">';
        //Определяем тип вещи
        if($_POST['thing_2']['kind'] == 1){
            echo '<span>';
        }
        elseif($_POST['thing_2']['kind'] == 2){
            echo '<span style="color: cornflowerblue">';
        }
        elseif($_POST['thing_2']['kind'] == 3){
            echo '<span style="color: mediumpurple">';
        }
        echo $_POST['thing_2']['name'];
        //Количество ячеек и точка
        if ($_POST['thing_2']['socket'])
            echo '<span style="font-weight:normal"> (яч: ' . $_POST['thing_2']['socket'] . ')';
        if ($_POST['thing_2']['improvement'])
            echo ' +' . $_POST['thing_2']['improvement'] . '</span>';
        echo '</span></p></div>';
        //Основное описание
        echo '<div style="border-bottom: #666666 dotted 1px; margin-bottom: 3px"><div style="float: left; padding: 8px; height: 46px">
            <img src="' . $_POST['thing_2']['img'] . '"></div>';
        echo '<div><p class="line">Тип: ' . $_POST['thing_2']['type'] .'</p>
            <p class="line">Уровень: ' . $_POST['thing_2']['lvl'] .'</p>
            <p class="line">';
        //Основной показатель
        if ($_POST['thing_2']['main_type'] == 'weapon'){
            echo 'Урон: ' . $_POST['thing_2']['self_dmg_min'] .'-' . $_POST['thing_2']['self_dmg_max'];
        }
        elseif($_POST['thing_2']['main_type'] == 'head'){
            if($_POST['thing_2']['type'] == 'Тяжелый шлем'){
                echo 'Здоровье: ' . $_POST['thing_2']['self_hp'];
            }
            else{
                echo 'Мана: ' . $_POST['thing_2']['self_mana'];
            }
        }
        elseif($_POST['thing_2']['main_type'] == 'shoulders'){
            echo 'Уклонение: ' . $_POST['thing_2']['self_dodge'];
        }
        elseif($_POST['thing_2']['main_type'] == 'neck'){
            if($_POST['thing_2']['type'] == 'Ожерелье силы'){
                echo 'Защита: ' . $_POST['thing_2']['self_def'];
            }
            elseif($_POST['thing_2']['type'] == 'Магическое ожерелье'){
                echo 'Защита: ' . $_POST['thing_2']['self_resist'];
            }
            else{
                echo 'Уклонение: ' . $_POST['thing_2']['self_dodge'];
            }
        }
        elseif($_POST['thing_2']['main_type'] == 'finger'){
            echo 'Урон: ' . $_POST['thing_2']['self_dmg'];
        }
        else{
            echo 'Защита: ' . $_POST['thing_2']['self_def'] . '<br>Маг. защита:' . $_POST['thing_2']['self_resist'];
        }
        echo '</p></div></div><div style="border-bottom: #666666 dotted 1px; margin-bottom: 3px">';
        //Требуемые параметры
        echo '<p class="line"';
        if ($_POST['thing_2']['check']['class'] == 'false'){
            echo ' style="color:red"';
        }
        echo '>Класс: ' . $_POST['thing_2']['class'] . '</p>';
        if ($_POST['thing_2']['n_lvl']) {
            echo '<p class="line"';
            if ($_POST['thing_2']['check']['lvl'] == 'false'){
                echo ' style="color:red"';
                $not = 1;
            }
            echo '>Требуемый уровень: ' . $_POST['thing_2']['n_lvl'] . '</p>';
        }
        if ($_POST['thing_2']['n_str']) {
            echo '<p class="line"';
            if ($_POST['thing_2']['check']['str'] == 'false'){
                echo ' style="color:red"';
                $not = 1;
            }
            echo '>Требуемая сила: ' . $_POST['thing_2']['n_str'] . '</p>';
        }
        if ($_POST['thing_2']['n_dex']) {
            echo '<p class="line"';
            if ($_POST['thing_2']['check']['dex'] == 'false'){
                echo ' style="color:red"';
                $not = 1;
            }
            echo '>Требуемая ловкость: ' . $_POST['thing_2']['n_dex'] . '</p>';
        }
        if ($_POST['thing_2']['n_int']) {
            echo '<p class="line"';
            if ($_POST['thing_2']['check']['intel'] == 'false'){
                echo ' style="color:red"';
                $not = 1;
            }
            echo '>Требуемый интелект: ' . $_POST['thing_2']['n_int'] . '</p>';
        }
        echo '</div>';
        echo '<div style="border-bottom: #666666 dotted 1px; margin-bottom: 3px;">';
        echo '<p class="line">Прочность: ' . $_POST['thing_2']['self_dur_now'] . '/' . $_POST['thing_2']['self_dur'] . '</p>';
        echo '</div>';
        //Дополнительыне статы
        if($_POST['thing_2']['kind'] != 1){
            echo '<div style="border-bottom: #666666 dotted 1px; margin-bottom: 3px;">';
            if(isset($_POST['thing_2']['str']) && $_POST['thing_2']['str'] != 0) echo '<p class="line2">Сила +' . $_POST['thing_2']['str'] . '</p>';
            if(isset($_POST['thing_2']['dex']) && $_POST['thing_2']['dex'] != 0) echo '<p class="line2">Ловкость +' . $_POST['thing_2']['dex'] . '</p>';
            if(isset($_POST['thing_2']['vit']) && $_POST['thing_2']['vit'] != 0) echo '<p class="line2">Выносливость +' . $_POST['thing_2']['vit'] . '</p>';
            if(isset($_POST['thing_2']['intel']) && $_POST['thing_2']['intel'] != 0) echo '<p class="line2">Интелект +' . $_POST['thing_2']['intel'] . '</p>';
            if(isset($_POST['thing_2']['hp']) && $_POST['thing_2']['hp'] != 0) echo '<p class="line2">Здоровье +' . $_POST['thing_2']['hp'] . '</p>';
            if(isset($_POST['thing_2']['hp_reg']) && $_POST['thing_2']['hp_reg'] != 0) echo '<p class="line2">Регенерация здоровья +' . $_POST['thing_2']['hp_reg'] . '</p>';
            if(isset($_POST['thing_2']['mp']) && $_POST['thing_2']['mp'] != 0) echo '<p class="line2">Мана +' . $_POST['thing_2']['mp'] . '</p>';
            if(isset($_POST['thing_2']['mp_reg']) && $_POST['thing_2']['mp_reg'] != 0) echo '<p class="line2">Регенерация маны +' . $_POST['thing_2']['mp_reg'] . '</p>';
            if(isset($_POST['thing_2']['def']) && $_POST['thing_2']['def'] != 0) echo '<p class="line2">Защита +' . $_POST['thing_2']['def'] . '</p>';
            if(isset($_POST['thing_2']['def_perc']) && $_POST['thing_2']['def_perc'] != 0) echo '<p class="line2">Защита +' . $_POST['thing_2']['def_perc'] . '%</p>';
            if(isset($_POST['thing_2']['resist']) && $_POST['thing_2']['resist'] != 0) echo '<p class="line2">Сопротивление магии +' . $_POST['thing_2']['resist'] . '</p>';
            if(isset($_POST['thing_2']['dmg']) && $_POST['thing_2']['dmg'] != 0) echo '<p class="line2">Урон +' . $_POST['thing_2']['dmg'] . '</p>';
            if(isset($_POST['thing_2']['dmg_mag']) && $_POST['thing_2']['dmg_mag'] != 0) echo '<p class="line2">Магический урон +' . $_POST['thing_2']['dmg_mag'] . '</p>';
            if(isset($_POST['thing_2']['dmg_min']) && $_POST['thing_2']['dmg_min'] != 0) echo '<p class="line2">Минимальный урон +' . $_POST['thing_2']['dmg_min'] . '</p>';
            if(isset($_POST['thing_2']['dmg_max']) && $_POST['thing_2']['dmg_max'] != 0) echo '<p class="line2">Максимальный урон +' . $_POST['thing_2']['dmg_max'] . '</p>';
            if(isset($_POST['thing_2']['dmg_perc']) && $_POST['thing_2']['dmg_perc'] != 0) echo '<p class="line2">Урон +' . $_POST['thing_2']['dmg_perc'] . '%</p>';
            if(isset($_POST['thing_2']['strike']) && $_POST['thing_2']['strike'] != 0) echo '<p class="line2">Крит +' . $_POST['thing_2']['strike'] . '%</p>';
            if(isset($_POST['thing_2']['hp_still']) && $_POST['thing_2']['hp_still'] != 0) echo '<p class="line2">Похищение здоровья +' . $_POST['thing_2']['hp_still'] . '</p>';
            if(isset($_POST['thing_2']['mp_still']) && $_POST['thing_2']['mp_still'] != 0) echo '<p class="line2">Похищение маны +' . $_POST['thing_2']['mp_still'] . '</p>';
            if(isset($_POST['thing_2']['dodge']) && $_POST['thing_2']['dodge'] != 0) echo '<p class="line2">Уворот +' . $_POST['thing_2']['dodge'] . '</p>';
            if(isset($_POST['thing_2']['accur']) && $_POST['thing_2']['accur'] != 0) echo '<p class="line2">Меткость +' . $_POST['thing_2']['accur'] . '</p>';
            if(isset($_POST['thing_2']['dur']) && $_POST['thing_2']['dur'] != 0) echo '<p class="line2">Прочность +' . $_POST['thing_2']['dur'] . '%</p>';
            if(isset($_POST['thing_2']['n_stats']) && $_POST['thing_2']['n_stats'] != 0) echo '<p class="line2">Снижение требуемых характеристик -' . $_POST['thing_2']['n_stats'] . '%</p>';
            echo '</div>';
        }
        if ($_POST['thing_2']['q'] == 1){
            echo '<div style="border-bottom: #666666 dotted 1px; margin-bottom: 3px">
                    <p style="color: #00F5FF;">Награда. Нельзя улучшить или передать.</p></div>';
        }
    }

}
else {
    echo '<div>Предмета не существует!</div>';
}