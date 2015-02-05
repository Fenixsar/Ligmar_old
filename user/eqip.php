<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2/24/14
 * Time: 5:03 PM
 */
require_once('../work/link_header.php');

if($user_id != 0){
    $user->getEqip();
    echo '<div class="menu"><h3>Снаряжение</h3></div>';
    echo '<div class="row">';
    for ($i = 0; $i < 11; $i++){
        if($user->eqip['eqip' . $i]){
            $thing = $user->getThing($user->eqip['eqip' . $i]);
            echo '<div class="col-sm-6 col-xs-12"><button thing_id=' . $user->eqip['eqip' . $i] . ' class="btn-thing">
            <img class="img-rounded" src="' . $thing['img'] . '" style="float:left; height:37px;"><p class="thing">';
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
            echo '</span></p><p class="thing">Уровень: ' . $thing['lvl'] . '</p><p class="thing">';
            if($thing['main_type'] == 'weapon')
                echo 'Урон: ' . $thing['self_dmg_min'] . '-' . $thing['self_dmg_max'] . '</p></button></div>';
            elseif($thing['main_type'] == 'head'){
                if($thing['type'] == 'Тяжелый шлем'){
                    echo 'Здоровье: ' . $thing['self_hp'] . '</p></button></div>';
                }
                else{
                    echo 'Мана: ' . $thing['self_mana'] . '</p></button></div>';
                }
            }
            elseif($thing['main_type'] == 'shoulders'){
                echo 'Уклонение: ' . $thing['self_dodge'] . '</p></button></div>';
            }
            elseif($thing['main_type'] == 'rings'){
                echo 'Урон: ' . $thing['self_dmg'] . '</p></button></div>';
            }
            elseif($thing['main_type'] == 'neck'){
                if($thing['type'] == 'Ожерелье силы'){
                    echo 'Защита: ' . $thing['self_def'] . '</p></button></div>';
                }
                elseif($thing['type'] == 'Магическое ожерелье'){
                    echo 'Маг. защита: ' . $thing['self_resist'] . '</p></button></div>';
                }
                else{
                    echo 'Уклонение: ' . $thing['self_dodge'] . '</p></button></div>';
                }
            }
            elseif($thing['main_type'] == 'belt'){
                if($thing['type'] == 'Ремень'){
                    echo 'Защита: ' . $thing['self_def'] . '</p></button></div>';
                }
                elseif($thing['type'] == 'Сумка'){
                    echo 'Маг. защита: ' . $thing['self_resist'] . '</p></button></div>';
                }
                else{
                    echo 'Уклонение: ' . $thing['self_dodge'] . '</p></button></div>';
                }
            }
            else{
                echo 'Защита: ' . $thing['self_def'] . '; Маг. защита: ' . $thing['self_resist'] . '</p></button></div>';
            }
        }
        else{
            echo '<div class="col-sm-6 col-xs-12"><button disabled="disabled" class="btn-thing" style="height: 51px">
            <img class="img-rounded" src="../img/things/fon.jpg" style="float: left;"> Пусто</button></div>';
        }

    }
    echo '</div>';
    echo '<hr class="main">';
    echo '<div class="row without_chat">
            <div class="col-sm-6 col-xs-12">
                <button id="bag" class="btn-main">Рюкзак</button>
            </div>
            <div class="col-sm-6 col-xs-12">
                <button id="box" class="btn-main">Сундук</button>
            </div>
        </div>';
}
