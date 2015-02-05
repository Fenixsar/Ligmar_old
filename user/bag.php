<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2/4/14
 * Time: 12:12 PM
 */
require_once('../work/link_header.php');

if($user_id != 0){
    $box=$user->getBox();
    if($box[0] >= $box[1]){
        $x = 'disabled';
    }
    echo '<div class="row">
            <div class="col-sm-6 col-xs-12">
                <button id="eqip" class="btn-main">Снаряжение</button>
            </div>
            <div class="col-sm-6 col-xs-12">
                <button id="box" class="btn-main">Сундук ('. $box[0] . '/'. $box[1] . ')</button>
            </div>
        </div>';
    echo '<div class="menu"><h3>Рюкзак (' . $user->getBag() . '/' . $user->array['bag'] .  ')</h3></div><div class="row without_chat">';
    $i = 0;
    while($i < $user->array['bag']){
        if ($user->things['bag' . $i]){
            $thing = $user->getThing($user->things['bag' . $i]);
            if($user->checkThingToPutOn($user->things['bag' . $i]) == 0){
                $e = 'disabled';
            }
            else{
                unset($e);
            }
            echo '<div class="col-sm-6 col-xs-12" style="border: #666666 dotted 1px;"><button thing_id=' .
                $thing['id'] . ' class="btn-thing"><div style="float: left; width: 32px;height: 35px; margin: 2px">
        <img src="' . $thing['img'] . '"></div><div><p class="thing">';
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
            echo '</span></p></div><div><p class="thing">Уровень: ' . $thing['lvl'] . '</p><p class="thing">';
            if($thing['main_type'] == 'weapon')
                echo 'Урон: ' . $thing['self_dmg_min'] . '-' . $thing['self_dmg_max'] . '<p class="thing"> </p>';
            elseif($thing['main_type'] == 'head'){
                if($thing['type'] == 'Тяжелый шлем'){
                    echo 'Здоровье: ' . $thing['self_hp'] . '</p>';
                }
                else{
                    echo 'Мана: ' . $thing['self_mana'] . '</p>';
                }
            }
            elseif($thing['main_type'] == 'shoulders'){
                echo 'Уклонение: ' . $thing['self_dodge'] . '</p>';
            }
            elseif($thing['main_type'] == 'rings'){
                echo 'Урон: ' . $thing['self_dmg'] . '</p>';
            }
            elseif($thing['main_type'] == 'neck'){
                if($thing['type'] == 'Ожерелье силы'){
                    echo 'Защита: ' . $thing['self_def'] . '</p>';
                }
                elseif($thing['type'] == 'Магическое ожерелье'){
                    echo 'Маг. защита: ' . $thing['self_resist'] . '</p>';
                }
                else{
                    echo 'Уклонение: ' . $thing['self_dodge'] . '</p>';
                }
            }
            elseif($thing['main_type'] == 'belt'){
                if($thing['type'] == 'Ремень'){
                    echo 'Защита: ' . $thing['self_def'] . '</p>';
                }
                elseif($thing['type'] == 'Сумка'){
                    echo 'Маг. защита: ' . $thing['self_resist'] . '</p>';
                }
                else{
                    echo 'Уклонение: ' . $thing['self_dodge'] . '</p>';
                }
            }
            else echo 'Защита: ' . $thing['self_def'] . '; Маг.защита: ' . $thing['self_resist'];
            echo '</p></div></button>';
            echo '<div class="row">
                <div class="col-sm-6 col-xs-6">
                    <button to="eqip" class="btn-main put_to" thing_id=' . $thing['id'] . '>Надеть</button>
                </div>
                <div class="col-sm-6 col-xs-6">
                    <button to="box" class="btn-main put_to" thing_id=' . $thing['id'] . '>В сундук</button>
                </div>
            </div>';
            echo '</div>';
        }
        $i++;
    }
    echo '</div>';
}