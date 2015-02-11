<?php
if(isset($_POST['place'])){
    switch ($_POST['place']){
        case "weapon":
            $type = "Оружие";
            break;
        case "head":
            $type = "Шлем";
            break;
        case "shoulders":
            $type = "Накидка";
            break;
        case "neck":
            $type = "Ожерелье";
            break;
        case "chest":
            $type = "Корпус";
            break;
        case "hands":
            $type = "Перчатки";
            break;
        case "finger":
            $type = "Кольцо";
            break;
        case "belt":
            $type = "Ремень";
            break;
        case "legs":
            $type = "Брюки";
            break;
        case "foot":
            $type = "Обувь";
            break;
    }
    echo '<div class="col-sm-6 col-xs-12"><button disabled="disabled" class="btn-thing" style="height: 55px; line-height: 29px">
            <img class="img-rounded" src="../img/things/fon.jpg" style="float: left;"> ' . $type . '</button></div>';
}
else{
    echo '<div class="col-sm-6 col-xs-12" style="border: #666666 dotted 1px;"><button thing_id=' .
        $_POST['id'] . ' class="btn-thing"><div style="float: left; width: 32px;height: 35px; margin: 2px">
        <img src="' . $_POST['img'] . '"></div><div><p class="thing">';
    //Определяем тип вещи
    if($_POST['kind'] == 1){
        echo '<span>';
    }
    elseif($_POST['kind'] == 2){
        echo '<span style="color: cornflowerblue">';
    }
    elseif($_POST['kind'] == 3){
        echo '<span style="color: mediumpurple">';
    }
    echo $_POST['name'];
    //Количество ячеек и точка
    if ($_POST['socket'])
        echo '<span style="font-weight:normal"> (яч: ' . $_POST['socket'] . ')';
    if ($_POST['improvement'])
        echo ' +' . $_POST['improvement'] . '</span>';
    echo '</span></p></div><div><p class="thing">Уровень: ' . $_POST['lvl'] . '</p><p class="thing">';
    if($_POST['main_type'] == 'weapon')
        echo 'Урон: ' . $_POST['self_dmg_min'] . '-' . $_POST['self_dmg_max'] . '<p class="thing"> </p>';
    elseif($_POST['main_type'] == 'head'){
        if($_POST['type'] == 'Тяжелый шлем'){
            echo 'Здоровье: ' . $_POST['self_hp'] . '</p>';
        }
        else{
            echo 'Мана: ' . $_POST['self_mana'] . '</p>';
        }
    }
    elseif($_POST['main_type'] == 'shoulders'){
        echo 'Уклонение: ' . $_POST['self_dodge'] . '</p>';
    }
    elseif($_POST['main_type'] == 'rings'){
        echo 'Урон: ' . $_POST['self_dmg'] . '</p>';
    }
    elseif($_POST['main_type'] == 'neck'){
        if($_POST['type'] == 'Ожерелье силы'){
            echo 'Защита: ' . $_POST['self_def'] . '</p>';
        }
        elseif($_POST['type'] == 'Магическое ожерелье'){
            echo 'Маг. защита: ' . $_POST['self_resist'] . '</p>';
        }
        else{
            echo 'Уклонение: ' . $_POST['self_dodge'] . '</p>';
        }
    }
    elseif($_POST['main_type'] == 'belt'){
        if($_POST['type'] == 'Ремень'){
            echo 'Защита: ' . $_POST['self_def'] . '</p>';
        }
        elseif($_POST['type'] == 'Сумка'){
            echo 'Маг. защита: ' . $_POST['self_resist'] . '</p>';
        }
        else{
            echo 'Уклонение: ' . $_POST['self_dodge'] . '</p>';
        }
    }
    else echo 'Защита: ' . $_POST['self_def'] . '; Маг.защита: ' . $_POST['self_resist'];
    echo '</p></div></button>';


    if($_POST['action_1']['type'] == 'sell') {
        $word_1 = "Продать";
        $class_1 = $_POST['action_1']['type'];
    }
    if($_POST['action_1']['type'] == 'eqip') {
        $word_1 = "Надеть";
        $class_1 = "put_to";
        $put_1 = 'to="' . $_POST['action_1']['type'] . '"';
        if($_POST['eqip'] == "false"){
            $dis_1 = "disabled";
        }
    }

    if($_POST['action_2']['type'] == 'break') {
        $word_2 = "Разобрать";
        $class_2 = $_POST['action_2']['type'];
    }
    if($_POST['action_2']['type'] == 'box') {
        $word_2 = "В сундук";
        $class_2 = "put_to";
        $put_2 = 'to="' . $_POST['action_2']['type'] . '"';
        if($_POST['box'] == "false"){
            $dis_2 = "disabled";
        }
    }
    if($_POST['action_2']['type'] == 'bag') {
        $word_2 = "В рюкзак";
        $class_2 = "put_to";
        $put_2 = 'to="' . $_POST['action_2']['type'] . '"';
        if($_POST['bag'] == "false"){
            $dis_2 = "disabled";
        }
    }


    if($_POST['action_0'] != 'eqip') {
        echo '<div class="row">
                <div class="col-sm-6 col-xs-6">
                    <button ' . $put_1 . ' class="btn-main ' . $class_1 . '" ' . $dis_1 . ' thing_id=' . $_POST['id'] . '>' . $word_1 . '</button>
                </div>
                <div class="col-sm-6 col-xs-6">
                    <button ' . $put_2 . ' class="btn-main ' . $class_2 . '" ' . $dis_2 . ' thing_id=' . $_POST['id'] . '>' . $word_2 . '</button>
                </div>
            </div>';

        echo '</div>';
    }
}
