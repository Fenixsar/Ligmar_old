<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2/1/14
 * Time: 5:42 PM
 */
//Верх всех страниц (хп, мана, опыт)
$exp = $user->getExpPercent();
echo '<div id=top class="row">';
echo '<div class="row"><div class="col-xs-3"><div class="progress" style="margin-bottom: 1px; height: 10px">
                  <div id="health" reg="'. $user->array['health_reg'] . '" max="'. $user->array['health_max'] . '"
                  now="0" class="progress-bar progress-bar-danger" role="progressbar"
                    aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%; line-height: 10px;">
                    </div></div>
                  <div class="progress" style="margin-bottom: 0px; height: 10px;">
                  <div id="mana" reg="'. $user->array['mana_reg'] . '" max="'. $user->array['mana_max'] . '" now = "0" class="progress-bar" role="progressbar"  aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%; line-height: 10px;"></div></div></div>';
echo '<div class="col-xs-6" style="text-align:center;font-size: 16px; font-weight: bold"><img src="../img/warrior18x18.gif"><span id ="name" name="' . $user->array['id'] . '">' . $user->array['name'] .'</span>(<span id="char_lvl">' . $user->array['level'] . '</span>)</div>
    <div class="col-xs-3"><div id="ping" style="float:right"></div><div id="notification_small" style="float:right;margin-right:15px;cursor: pointer;" value="0"></div></div></div>';
echo '<div class="exp1" title="asdfads"><div id="exp_bar" style="background:#FFDF8C; height:1px; width:' . $exp  . '%;"></div></div>';
echo '<div id="exp_amount" style="text-align: center; font-size: 10px; margin-bottom: 2px">' . $user->array['exp'] . '/' . $user->array['exp_need'] . '(' . $exp . '%)</div>';
////Группа
//if($user->array['party']){
//    $party = $user->getParty();
//    $count_party = count($party);
//    echo "<div id='party' class='row' name='{$party[0]}' summ='{$count_party}'>";
//    $p = 1;
//    while ($p < count($party) ){
//        $party_char = $user->getCharacter($party[$p]);
//        echo "<div class='party_char'><div style='float: left;margin-right: 5px'><span'>{$party_char['level']}</span></div>
//         <div class='party_char_name' style='font-size: smaller'>{$party_char['name']}</div><div class='party_progress'><div class='progress-bar progress-bar-danger' role='progressbar'
//                    aria-valuenow='60' aria-valuemin='0' aria-valuemax='100' style='width: 100%; line-height: 10px;'></div></div>
//                    <div class='party_progress'><div class='progress-bar' role='progressbar'
//                    aria-valuenow='60' aria-valuemin='0' aria-valuemax='100' style='width: 100%; line-height: 10px;'></div></div>
//              </div>";
//        $p++;
//    }
//    echo "</div>";
//}

//Оповещение
echo '<div id="notification_green" class="row" style="display: none"><div class="col-xs-0 col-sm-3"></div><div class="col-xs-12 col-sm-6 text-center"
            style="border: limegreen dotted 1px; background-color: forestgreen"><div id="notification_green_close">X</div><span id="notification_green_text"></span></div></div>';
echo '<div id="notification_blood" class="row" style="margin-bottom: 10px;display: none"><div class="col-xs-0 col-sm-3"></div><div class="col-xs-12 col-sm-6 text-center"
            style="border: limegreen dotted 1px; background-color: brown"><div class="row" id="notification_blood_text"></div></div></div>';
echo '</div>';
//Основной блок
echo '<div id="game" class="row">';
