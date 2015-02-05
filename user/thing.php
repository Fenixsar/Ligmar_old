<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2/24/14
 * Time: 11:00 AM
 */
require_once('../work/link_header.php');

echo '<div class="row">
        <div class="col-xs-4"><button id="eqip" class="btn-main">Снаряжение</button></div>
        <div class="col-xs-4" style="padding: 0 1px"><button id="bag" class="btn-main">Рюкзак</button></div>
        <div class="col-xs-4"><button id="box" class="btn-main">Сундук</button></div>
    </div>
    <hr class="main">';

if($user_id != 0 && $_POST['t']){
    $thing = $user->getThing($_POST['t']);
    echo '<div class="row without_chat"><div class="col-sm-6 col-xs-12">';
    if ($thing['name']){
        echo '<div><p style="font-size: 16px; font-weight: bold">';
        //Определяем тип вещи
        if($thing['kind'] == 1){
            echo '<span>';
        }
        elseif($thing['kind'] == 2){
            echo '<span style="color: cornflowerblue">';
        }
        elseif($thing['kind'] == 3){
            echo '<span style="color: mediumpurple">';
        }
        echo $thing['name'];
        //Количество ячеек и точка
        if ($thing['socket'])
            echo '<span style="font-weight:normal"> (яч: ' . $thing['socket'] . ')';
        if ($thing['improvement'])
            echo ' +' . $thing['improvement'] . '</span>';
        echo '</span></p></div>';
        //Основное описание
        echo '<div style="border-bottom: #666666 dotted 1px; margin-bottom: 3px"><div style="float: left; padding: 8px; height: 46px">
        <img src="' . $thing['img'] . '"></div>';
        echo '<div><p class="line">Тип: ' . $thing['type'] .'</p>
        <p class="line">Уровень: ' . $thing['lvl'] .'</p>
        <p class="line">';
        //Основной показатель
        if ($thing['main_type'] == 'weapon')
            echo 'Урон: ' . $thing['self_dmg_min'] .'-' . $thing['self_dmg_max'];
        elseif($thing['main_type'] == 'head'){
            if($thing['type'] == 'Тяжелый шлем'){
                echo 'Здоровье: ' . $thing['self_hp'];
            }
            else{
                echo 'Мана: ' . $thing['self_mana'];
            }
        }
        elseif($thing['main_type'] == 'shoulders'){
            echo 'Уклонение: ' . $thing['self_dodge'];
        }
        elseif($thing['main_type'] == 'neck'){
            if($thing['type'] == 'Ожерелье силы'){
                echo 'Защита: ' . $thing['self_def'];
            }
            elseif($thing['type'] == 'Магическое ожерелье'){
                echo 'Защита: ' . $thing['self_resist'];
            }
            else{
                echo 'Уклонение: ' . $thing['self_dodge'];
            }
        }
        elseif($thing['main_type'] == 'rings'){
            echo 'Урон: ' . $thing['self_dmg'];
        }
        else{
            echo 'Защита: ' . $thing['self_def'] . '<br>Маг. защита:' . $thing['self_resist'];
        }
        echo '</p></div></div><div style="border-bottom: #666666 dotted 1px; margin-bottom: 3px">';
        //Требуемые параметры
        echo '<p class="line"';
        $check_class = explode(", ", $thing['class']);
        $y = 0;
        while ($check_class[$y]){
            if ($user->array['class'] == $check_class[$y] || $thing['class'] == 'Все'){
                $not = 999;
                break;
            }
            else{
                $not = 1;
            }
            $y++;
        }
        if ($not == 1){
            echo ' style="color:red"';
        }
        echo '>Класс: ' . $thing['class'] . '</p>';
        if ($thing['n_lvl']) {
            echo '<p class="line"';
            if ($thing['n_lvl'] > $user->array['level']){
                echo ' style="color:red"';
                $not = 1;
            }
            echo '>Требуемый уровень: ' . $thing['n_lvl'] . '</p>';
        }
        if ($thing['n_str']) {
            echo '<p class="line"';
            if ($thing['n_str'] > $user->array['str']){
                echo ' style="color:red"';
                $not = 1;
            }
            echo '>Требуемая сила: ' . $thing['n_str'] . '</p>';
        }
        if ($thing['n_dex']) {
            echo '<p class="line"';
            if ($thing['n_dex'] > $user->array['dex']){
                echo ' style="color:red"';
                $not = 1;
            }
            echo '>Требуемая ловкость: ' . $thing['n_dex'] . '</p>';
        }
        if ($thing['n_int']) {
            echo '<p class="line"';
            if ($thing['n_int'] > $user->array['intel']){
                echo ' style="color:red"';
                $not = 1;
            }
            echo '>Требуемый интелект: ' . $thing['n_int'] . '</p>';
        }
        echo '</div>';
        //Дополнительыне статы
        if($thing['kind'] != 1){
            echo '<div style="border-bottom: #666666 dotted 1px; margin-bottom: 3px;">';
            if(isset($thing['str']) && $thing['str'] != 0) echo '<p class="line2">Сила +' . $thing['str'] . '</p>';
            if(isset($thing['dex']) && $thing['dex'] != 0) echo '<p class="line2">Ловкость +' . $thing['dex'] . '</p>';
            if(isset($thing['vit']) && $thing['vit'] != 0) echo '<p class="line2">Выносливость +' . $thing['vit'] . '</p>';
            if(isset($thing['intel']) && $thing['intel'] != 0) echo '<p class="line2">Интелект +' . $thing['intel'] . '</p>';
            if(isset($thing['hp']) && $thing['hp'] != 0) echo '<p class="line2">Здоровье +' . $thing['hp'] . '</p>';
            if(isset($thing['hp_reg']) && $thing['hp_reg'] != 0) echo '<p class="line2">Регенерация здоровья +' . $thing['hp_reg'] . '</p>';
            if(isset($thing['mp']) && $thing['mp'] != 0) echo '<p class="line2">Мана +' . $thing['mp'] . '</p>';
            if(isset($thing['mp_reg']) && $thing['mp_reg'] != 0) echo '<p class="line2">Регенерация маны +' . $thing['mp_reg'] . '</p>';
            if(isset($thing['def']) && $thing['def'] != 0) echo '<p class="line2">Защита +' . $thing['def'] . '</p>';
            if(isset($thing['def_perc']) && $thing['def_perc'] != 0) echo '<p class="line2">Защита +' . $thing['def_perc'] . '%</p>';
            if(isset($thing['resist']) && $thing['resist'] != 0) echo '<p class="line2">Сопротивление магии +' . $thing['resist'] . '</p>';
            if(isset($thing['dmg']) && $thing['dmg'] != 0) echo '<p class="line2">Урон +' . $thing['dmg'] . '</p>';
            if(isset($thing['dmg_mag']) && $thing['dmg_mag'] != 0) echo '<p class="line2">Магический урон +' . $thing['dmg_mag'] . '</p>';
            if(isset($thing['dmg_min']) && $thing['dmg_min'] != 0) echo '<p class="line2">Минимальный урон +' . $thing['dmg_min'] . '</p>';
            if(isset($thing['dmg_max']) && $thing['dmg_max'] != 0) echo '<p class="line2">Максимальный урон +' . $thing['dmg_max'] . '</p>';
            if(isset($thing['dmg_perc']) && $thing['dmg_perc'] != 0) echo '<p class="line2">Урон +' . $thing['dmg_perc'] . '%</p>';
            if(isset($thing['strike']) && $thing['strike'] != 0) echo '<p class="line2">Крит +' . $thing['strike'] . '%</p>';
            if(isset($thing['hp_still']) && $thing['hp_still'] != 0) echo '<p class="line2">Похищение здоровья +' . $thing['hp_still'] . '</p>';
            if(isset($thing['mp_still']) && $thing['mp_still'] != 0) echo '<p class="line2">Похищение маны +' . $thing['mp_still'] . '</p>';
            if(isset($thing['dodge']) && $thing['dodge'] != 0) echo '<p class="line2">Уворот +' . $thing['dodge'] . '</p>';
            if(isset($thing['accur']) && $thing['accur'] != 0) echo '<p class="line2">Меткость +' . $thing['accur'] . '</p>';
            if(isset($thing['dur']) && $thing['dur'] != 0) echo '<p class="line2">Прочность +' . $thing['dur'] . '%</p>';
            if(isset($thing['n_stats']) && $thing['n_stats'] != 0) echo '<p class="line2">Снижение требуемых характеристик -' . $thing['n_stats'] . '%</p>';
            echo '</div>';
        }
        echo '<div style="border-bottom: #666666 dotted 1px; margin-bottom: 3px;">';
        echo '<p class="line">Прочность: ' . $thing['self_dur_now'] . '/' . $thing['self_dur'] . '</p>';
        echo '</div>';
        if ($thing['q'] == 1){
            echo '<div style="border-bottom: #666666 dotted 1px; margin-bottom: 3px">
                <p style="color: #00F5FF;">Награда. Нельзя улучшить или передать.</p></div>';
        }
        $t = $user->searchThing($_POST['t']);
        //Проверка на состояние рюкзака и сундука
        $bag = $user->getBag();
        $box = $user->getBox();
        if($bag >= $user->array['bag']){
            $c = 'disabled="disabled"';
        }
        if($box[1] <= $box[0]){
            $x = 'disabled="disabled"';
        }
        //Проверяем, может ли чар его надеть
        if($user->checkThingToPutOn($_POST['t']) == 0){
            $g = 'disabled="disabled"';
        }
        //Если предмет находится в снаряжении
        if ($t == 2){
            echo '<div class="row">
                <div class="col-sm-6 col-xs-6">
                    <button to="bag" class="btn-main put_to" ' . $c . ' thing_id=' . $thing['id'] . '>В рюкзак</button>
                </div>
                <div class="col-sm-6 col-xs-6">
                    <button to="box" class="btn-main put_to" ' . $x . ' thing_id=' . $thing['id'] . '>В сундук</button>
                </div>
            </div>';
        }
        //Если предмет находится в рюкзаке
        elseif($t == 1){
            echo '<div class="row">
                <div class="col-sm-6 col-xs-6">
                    <button to="eqip" class="btn-main put_to" ' . $g . ' thing_id=' . $thing['id'] . '>Надеть</button>
                </div>
                <div class="col-sm-6 col-xs-6">
                    <button to="box" class="btn-main put_to" ' . $x . ' thing_id=' . $thing['id'] . '>В сундук</button>
                </div>
            </div>';
        }elseif($t == 0){
            echo '<div class="row">
                <div class="col-sm-6 col-xs-6">
                    <button to="eqip" class="btn-main put_to" ' . $g . ' thing_id=' . $thing['id'] . '>Надеть</button>
                </div>
                <div class="col-sm-6 col-xs-6">
                    <button to="bag" class="btn-main put_to" ' . $c . ' thing_id=' . $thing['id'] . '>В рюкзак</button>
                </div>
            </div>';
        }
        echo '</div>';
        if ($t != 2){
            echo '<div class="col-sm-6 col-xs-12">';
            if($thing['main_type'] == 'weapon'){
                $i = 0;
            }
            elseif($thing['main_type'] == 'head'){
                $i = 1;
            }
            elseif($thing['main_type'] == 'shoulders'){
                $i = 2;
            }
            elseif($thing['main_type'] == 'neck'){
                $i = 3;
            }
            elseif($thing['main_type'] == 'chest'){
                $i = 4;
            }
            elseif($thing['main_type'] == 'hands'){
                $i = 5;
            }
            elseif($thing['main_type'] == 'rings'){
                $i = 6;
            }
            elseif($thing['main_type'] == 'belt'){
                $i = 8;
            }
            elseif($thing['main_type'] == 'legs'){
                $i = 9;
            }
            elseif($thing['main_type'] == 'foot'){
                $i = 10;
            }
            $user->getEqip();
            $thing = $user->getThing($user->eqip['eqip' . $i]);
            if ($thing){
                echo '<div><p style="font-size: 16px; font-weight: bold">';
                //Определяем тип вещи
                if($thing['kind'] == 1){
                    echo '<span>';
                }
                elseif($thing['kind'] == 2){
                    echo '<span style="color: cornflowerblue">';
                }
                elseif($thing['kind'] == 3){
                    echo '<span style="color: mediumpurple">';
                }
                echo $thing['name'];
                //Количество ячеек и точка
                if ($thing['socket'])
                    echo '<span style="font-weight:normal"> (яч: ' . $thing['socket'] . ')';
                if ($thing['improvement'])
                    echo ' +' . $thing['improvement'] . '</span>';
                echo '</span></p></div>';
                //Основное описание
                echo '<div style="border-bottom: #666666 dotted 1px; margin-bottom: 3px"><div style="float: left; padding: 8px; height: 46px">
            <img src="' . $thing['img'] . '"></div>';
                echo '<div><p class="line">Тип: ' . $thing['type'] .'</p>
            <p class="line">Уровень: ' . $thing['lvl'] .'</p>
            <p class="line">';
                //Основной показатель
                if ($thing['main_type'] == 'weapon'){
                    echo 'Урон: ' . $thing['self_dmg_min'] .'-' . $thing['self_dmg_max'];
                }
                elseif($thing['main_type'] == 'head'){
                    if($thing['type'] == 'Тяжелый шлем'){
                        echo 'Здоровье: ' . $thing['self_hp'];
                    }
                    else{
                        echo 'Мана: ' . $thing['self_mana'];
                    }
                }
                elseif($thing['main_type'] == 'shoulders'){
                    echo 'Уклонение: ' . $thing['self_dodge'];
                }
                elseif($thing['main_type'] == 'neck'){
                    if($thing['type'] == 'Ожерелье силы'){
                        echo 'Защита: ' . $thing['self_def'];
                    }
                    elseif($thing['type'] == 'Магическое ожерелье'){
                        echo 'Защита: ' . $thing['self_resist'];
                    }
                    else{
                        echo 'Уклонение: ' . $thing['self_dodge'];
                    }
                }
                elseif($thing['main_type'] == 'rings'){
                    echo 'Урон: ' . $thing['self_dmg'];
                }
                else{
                    echo 'Защита: ' . $thing['self_def'] . '<br>Маг. защита:' . $thing['self_resist'];
                }
                echo '</p></div></div><div style="border-bottom: #666666 dotted 1px; margin-bottom: 3px">';
                //Требуемые параметры
                echo '<p class="line"';
                $check_class = explode(", ", $thing['class']);
                $y = 0;
                while ($check_class[$y]){
                    if ($user->array['class'] == $check_class[$y] || $thing['class'] == 'Все'){
                        $not = 999;
                        break;
                    }
                    else{
                        $not = 1;
                    }
                    $y++;
                }
                if ($not == 1){
                    echo ' style="color:red"';
                }
                echo '>Класс: ' . $thing['class'] . '</p>';
                if ($thing['n_lvl']) {
                    echo '<p class="line"';
                    if ($thing['n_lvl'] > $user->array['level']){
                        echo ' style="color:red"';
                        $not = 1;
                    }
                    echo '>Требуемый уровень: ' . $thing['n_lvl'] . '</p>';
                }
                if ($thing['n_str']) {
                    echo '<p class="line"';
                    if ($thing['n_str'] > $user->array['str']){
                        echo ' style="color:red"';
                        $not = 1;
                    }
                    echo '>Требуемая сила: ' . $thing['n_str'] . '</p>';
                }
                if ($thing['n_dex']) {
                    echo '<p class="line"';
                    if ($thing['n_dex'] > $user->array['dex']){
                        echo ' style="color:red"';
                        $not = 1;
                    }
                    echo '>Требуемая ловкость: ' . $thing['n_dex'] . '</p>';
                }
                if ($thing['n_int']) {
                    echo '<p class="line"';
                    if ($thing['n_int'] > $user->array['intel']){
                        echo ' style="color:red"';
                        $not = 1;
                    }
                    echo '>Требуемый интелект: ' . $thing['n_int'] . '</p>';
                }
                echo '</div>';
                //Дополнительыне статы
                if($thing['kind'] != 1){
                    echo '<div style="border-bottom: #666666 dotted 1px; margin-bottom: 3px;">';
                    if(isset($thing['str']) && $thing['str'] != 0) echo '<p class="line2">Сила +' . $thing['str'] . '</p>';
                    if(isset($thing['dex']) && $thing['dex'] != 0) echo '<p class="line2">Ловкость +' . $thing['dex'] . '</p>';
                    if(isset($thing['vit']) && $thing['vit'] != 0) echo '<p class="line2">Выносливость +' . $thing['vit'] . '</p>';
                    if(isset($thing['intel']) && $thing['intel'] != 0) echo '<p class="line2">Интелект +' . $thing['intel'] . '</p>';
                    if(isset($thing['hp']) && $thing['hp'] != 0) echo '<p class="line2">Здоровье +' . $thing['hp'] . '</p>';
                    if(isset($thing['hp_reg']) && $thing['hp_reg'] != 0) echo '<p class="line2">Регенерация здоровья +' . $thing['hp_reg'] . '</p>';
                    if(isset($thing['mp']) && $thing['mp'] != 0) echo '<p class="line2">Мана +' . $thing['mp'] . '</p>';
                    if(isset($thing['mp_reg']) && $thing['mp_reg'] != 0) echo '<p class="line2">Регенерация маны +' . $thing['mp_reg'] . '</p>';
                    if(isset($thing['def']) && $thing['def'] != 0) echo '<p class="line2">Защита +' . $thing['def'] . '</p>';
                    if(isset($thing['def_perc']) && $thing['def_perc'] != 0) echo '<p class="line2">Защита +' . $thing['def_perc'] . '%</p>';
                    if(isset($thing['resist']) && $thing['resist'] != 0) echo '<p class="line2">Сопротивление магии +' . $thing['resist'] . '</p>';
                    if(isset($thing['dmg']) && $thing['dmg'] != 0) echo '<p class="line2">Урон +' . $thing['dmg'] . '</p>';
                    if(isset($thing['dmg_mag']) && $thing['dmg_mag'] != 0) echo '<p class="line2">Магический урон +' . $thing['dmg_mag'] . '</p>';
                    if(isset($thing['dmg_min']) && $thing['dmg_min'] != 0) echo '<p class="line2">Минимальный урон +' . $thing['dmg_min'] . '</p>';
                    if(isset($thing['dmg_max']) && $thing['dmg_max'] != 0) echo '<p class="line2">Максимальный урон +' . $thing['dmg_max'] . '</p>';
                    if(isset($thing['dmg_perc']) && $thing['dmg_perc'] != 0) echo '<p class="line2">Урон +' . $thing['dmg_perc'] . '%</p>';
                    if(isset($thing['strike']) && $thing['strike'] != 0) echo '<p class="line2">Крит +' . $thing['strike'] . '%</p>';
                    if(isset($thing['hp_still']) && $thing['hp_still'] != 0) echo '<p class="line2">Похищение здоровья +' . $thing['hp_still'] . '</p>';
                    if(isset($thing['mp_still']) && $thing['mp_still'] != 0) echo '<p class="line2">Похищение маны +' . $thing['mp_still'] . '</p>';
                    if(isset($thing['dodge']) && $thing['dodge'] != 0) echo '<p class="line2">Уворот +' . $thing['dodge'] . '</p>';
                    if(isset($thing['accur']) && $thing['accur'] != 0) echo '<p class="line2">Меткость +' . $thing['accur'] . '</p>';
                    if(isset($thing['dur']) && $thing['dur'] != 0) echo '<p class="line2">Прочность +' . $thing['dur'] . '%</p>';
                    if(isset($thing['n_stats']) && $thing['n_stats'] != 0) echo '<p class="line2">Снижение требуемых характеристик -' . $thing['n_stats'] . '%</p>';
                    echo '</div>';
                }
                echo '<div style="border-bottom: #666666 dotted 1px; margin-bottom: 3px;">';
                echo '<p class="line">Прочность: ' . $thing['self_dur_now'] . '/' . $thing['self_dur'] . '</p>';
                echo '</div>';
                if ($thing['q'] == 1){
                    echo '<div style="border-bottom: #666666 dotted 1px; margin-bottom: 3px">
                    <p style="color: #00F5FF;">Награда. Нельзя улучшить или передать.</p></div>';
                }
            }
            echo '</div>';
        }
        echo '</div>';

        echo '</div>';
    }
    else {
        echo '<div>Предмета не существует!</div>';
    }
}
