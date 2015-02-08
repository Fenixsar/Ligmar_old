<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/6/14
 * Time: 9:56 AM
 */
require_once('../work/link_header.php');

if($user_id != 0){
    echo '<div class="row" id="battle_place"><div id="wait_connect_battle"><div id="Gen" class="row">
        <div class="block" id="rotate_01"></div>
        <div class="block" id="rotate_02"></div>
        <div class="block" id="rotate_03"></div>
        <div class="block" id="rotate_04"></div>
        <div class="block" id="rotate_05"></div>
        <div class="block" id="rotate_06"></div>
        <div class="block" id="rotate_07"></div>
        <div class="block" id="rotate_08"></div>
        </div><div id="timer_text" class="row">Загрузка боя...</div></div>

        <div class="row" id="battle_" style="display: none">
            <div style="float: left;position: absolute;font-size: smaller"><img src="../img/skull.gif"><span id="round"></span></div>
            <div id="round_progress" style="float: right;position: absolute"></div>
                <h3 class="target" id="0"><span id="target_name"></span>[<span id="target_lvl"></span>]<br>
                    <span id="target_bonus" style="color: limegreen"></span>
                </h3>
                <div class="row">
                    <div class="col-xs-3"></div>
                    <div class="col-xs-6">
                        <div class="progress">
                            <div id="target_hp" reg="" now = "" max = "" class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                        </div>
                    </div>
                    <div id="hit_to_target" class="col-xs-3" style="height:20px;text-align:left;padding-left:5px; color:red"></div>
                </div>
                <div id="character_death" class="row"></div>
                <div id="prefer_battle_actions" class="row">
                    <div class="col-xs-6"><button id="start_battle" class="btn-main">Напасть!</button></div>
                    <div class="col-xs-6"><button class="btn-main next_mob">Искать еще!</button></div>
                </div>
                <div id="battle_actions" class="row" style="display: none;">
                    <div class="col-sm-6">
                        <div id="action_skills" class="row">
                            <div class="pull-left" style="padding: 1px"><button id="action_hit_target" class="btn-action progress-button"></button></div>
                            <div class="pull-left" style="padding: 1px"><button id="action_change_target" class="btn-action progress-button"></button></div>
                            <div class="pull-left" style="padding: 1px">
                                <button id="action_health_bottle" class="btn-action progress-button"></button>
                                <div class="row cooldown"></div></div>
                            <div class="pull-left" style="padding: 1px">
                                <button id="action_mana_bottle" class="btn-action progress-button"></button>
                                <div class="row cooldown"></div></div>
                        </div>
                        <div  class="row">
                        </div>

                    </div>
                    <div class="col-sm-6 hidden">
                        <div class="col-xs-3" style="padding: 1px"><button id="hit_target" class="btn-action">!</button></div>
                        <div class="col-xs-3" style="padding: 1px"><button id="hit_target" class="btn-action">!</button></div>
                        <div class="col-xs-3" style="padding: 1px"><button id="hit_target" class="btn-action">!</button></div>
                        <div class="col-xs-3" style="padding: 1px"><button id="hit_target" class="btn-action">!</button></div>
                    </div>
                </div>
            </div>
            <div class="row" id="finish_battle"></div>
        </div>';

    echo '<script type="text/javascript">
        if(main_status.bottles.hp <= 0){
            $("#action_health_bottle").addClass("disabled");
        }
        if(main_status.bottles.mp <= 0){
            $("#action_mana_bottle").addClass("disabled");
        }
        for (var i = 0; i < main_status.skills.length; i++){
            $("#action_skills").append("<div class=\'pull-left\'style=\'padding: 1px\'><button class=\'btn-action action_skill\' id_skill=\'" + main_status.skills[i].id +"\'></button><div class=\'row cooldown\'></div></div>")
        }
    </script>';
}