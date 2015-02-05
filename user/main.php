<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/8/14
 * Time: 2:51 AM
 */

require_once('../work/link_header.php');

if($user_id != 0){
    echo '<div id="hero_main" class="row without_chat">
    <div><h3 id="header_name_hero">----</h3><br>
        <h4 id="header_class"><span id="header_class_hero">----</span>, <span id="header_level_hero">--</span></h4></div>
    <div class="menu"><h3>Характеристики</h3></div>
    <div class="row">
        <div class="col-sm-6">
            <ul class="char">
                <li class="char">Сила<span id="strength_hero" class="char"></span></li>
                <li class="char">Ловкость<span id="dexterity_hero" class="char"></span></li>
            </ul>
        </div>
        <div class="col-sm-6">
            <ul class="char">
                <li class="char">Интелект<span id="intelligence_hero" class="char"></span></li>
                <li class="char">Выносливость<span id="vitality_hero" class="char"></span></li>
            </ul>
        </div>
    </div>
    <hr class="zero">
    <div class="row">
        <div class="col-sm-6">
            <ul class="char">
                <li class="char">Атака<span id="dmg_hero" class="char"></span></li>
                <li class="char">Доп. магическая атака<span id="add_mag_dmg_hero" class="char"></span></li>
                <li class="char">Меткость<span id="accur_hero" class="char"></span></li>
                <li class="char">Шанс крит. удара<span id="strike_hero" class="char"></span></li>
            </ul>
        </div>
        <div class="col-sm-6">
            <ul class="char">
                <li class="char">Защита<span id="def_hero" class="char"></span></li>
                <li class="char">Магическая защита<span id="resist_hero" class="char"></span></li>
                <li class="char">Уворот<span id="dodge_hero" class="char"></span></li>
                <li class="char">Критический урон<span id="dmg_strike_hero" class="char"></span></li>
            </ul>
        </div>
    </div>
    <div class="menu"><h3>Персонаж</h3></div>
    <div class="row">
        <div class="col-xs-4"><button class="btn-main" disabled>Умения</button></div>
        <div class="col-xs-4" style="padding: 0 1px"><button class="btn-main" disabled>Задания</button></div>
        <div class="col-xs-4"><button class="btn-main" disabled>Достижения</button></div>
    </div>
    <div class="menu"><h3>Имущество</h3></div>
    <div class="row">
        <div class="col-xs-4"><button id="eqip" class="btn-main">Снаряжение</button></div>
        <div class="col-xs-4" style="padding: 0 1px"><button id="bag" class="btn-main">Рюкзак</button></div>
        <div class="col-xs-4"><button id="box" class="btn-main">Сундук</button></div>
    </div>
    <hr class="main">
    <div class="row">
        <div class="col-xs-4"><button id="casket" class="btn-main">Шкатулка</button></div>
        <div class="col-xs-4" style="padding: 0 1px"><button class="btn-main" disabled>Почта</button></div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <ul class="char">
                <li class="char">Зелья здоровья<span id="health_bottles_hero" class="char"></span></li>
            </ul>
        </div>
        <div class="col-sm-6">
            <ul class="char">
                <li class="char">Зелья маны<span id="mana_bottles_hero" class="char"></span></li>
            </ul>
        </div>
    </div>
    <hr class="main">
    <div class="row">
        <div class="col-sm-6">
            <ul class="char">
                <li class="char">Древесина<span id="wood_hero" class="char"></span></li>
                <li class="char">Руда<span id="ore_hero" class="char"></span></li>
            </ul>
        </div>
        <div class="col-sm-6">
            <ul class="char">
                <li class="char">Нить<span id="thread_hero" class="char"></span></li>
                <li class="char">Кожа<span id="leather_hero" class="char"></span></li>
            </ul>
        </div>
    </div>
    <hr class="main">
    </div>';



}