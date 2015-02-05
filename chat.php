<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/3/14
 * Time: 11:38 AM
 */

echo '<div id="chat_main" class="row"><hr class="main">';

echo '<div class="row" id="chat" style="display: none">';
echo '<div class="col-sm-9 col-xs-12"><input type="text" id="field" placeholder="Сообщение..." maxlength="150" style="width: 70%"/><button id="butt" style="width: 30%">Отправить</button></div>';
echo '<div class="col-sm-3 hidden-xs" style="text-align: center; height: 26px; padding-top: 2px">Онлайн: <span id="user_value"></span></div>';
echo '<div class="col-sm-1 col-xs-2 square" style="overflow: hidden;padding-top: 2px">
    <button role="button" class="btn btn-link btn-block chat_chanel active" name="chat_message" add="" style="text-align: left; padding-left: 5px">Все</button><hr class="zero">
    <button role="button" class="btn btn-link btn-block chat_chanel" name="mess" add="" style="text-align: left; padding-left: 5px">Общ.</button><hr class="zero">
    <button role="button" class="btn btn-link btn-block chat_chanel" name="chat_party" add="/~ " style="text-align: left; padding-left: 5px">Груп.</button><hr class="zero">
    <button role="button" class="btn btn-link btn-block chat_chanel" name="chat_guild" add="/@ " style="text-align: left; padding-left: 5px">Клан</button><hr class="zero">
    <button role="button" class="btn btn-link btn-block chat_chanel" name="chat_market" add="/$ " style="text-align: left; padding-left: 5px">Торг.</button><hr class="zero">
    <button role="button" class="btn btn-link btn-block chat_chanel" name="chat_solo" add="" style="text-align: left; padding-left: 5px">Личн.</button></div>';
echo '<div class="col-sm-8 col-xs-10 square" id="chat_place">';
echo '</div>';
echo '<div id="chat_add_window" style="text-align: center;color: #000000;">
        <div style="height: 30px" id="name_chat_target">Персонаж</div><hr class="zero">
        <div id="char_link_for_chat_2" style="height: 30px">Написать</div><hr class="zero">
        <div style="height: 30px">Персонаж</div><hr class="zero">
        <div id="close_chat_add_window" style="height: 30px">Закрыть</div>
        </div>';
echo '<div class="col-sm-3 hidden-xs square" id="user_list">';
echo '</div>';
echo '</div>';
echo '<div id="wait_connect_chat" ><div id="Gen" class="row">
					<div class="block" id="rotate_01"></div>
					<div class="block" id="rotate_02"></div>
					<div class="block" id="rotate_03"></div>
					<div class="block" id="rotate_04"></div>
					<div class="block" id="rotate_05"></div>
					<div class="block" id="rotate_06"></div>
					<div class="block" id="rotate_07"></div>
					<div class="block" id="rotate_08"></div>
				</div><div id="timer_chat" style="text-align: center">Подключение к чату...</div></div></div>';