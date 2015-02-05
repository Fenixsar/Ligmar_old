<?php
define('PROTECTOR', 1);
include('../work/start.php');
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/27/14
 * Time: 12:09 PM
 */
session_start();
$title = 'Регистрация';

include('../header.php');

echo '<body><div class="main"> ';
echo '<h1 class="main"><a class="not" href="/">' .$name . '</a></h1>';
echo '<div id="center"><img src="../logo.jpg" width="250px"></div>';
echo '<div><h2 class="enter">Регистрация:</h2></div>';

echo '<script src="../js/change_html5.js"></script>';

echo '<form method="POST" role="form" action="reg_final.php" id="regform">
  <div class="form-group">
    <label class="sr-only" for="exampleInputText1">Login</label>
    <input type="text" class="form-control" id="exampleInputText1" placeholder="Введите логин" name="login" required>';
if (empty($_GET['not'])) echo '<span class="help-block">Используется для входа в игру</span>';
    else echo '<span class="help-block" style="color: red">Этот логин уже используется!</span>';
echo '</div>
  <div class="form-group">
    <label class="sr-only" for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Пароль" name="pass" required>
    <span class="help-block">Пароль должен содержать не менее 6 символов</span>
  </div>
    <div class="form-group">
    <label class="sr-only" for="exampleInputPassword2">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Пароль еще раз" required>
    <span class="help-block">Советуем использовать надежный пароль</span>
  </div>
      <div class="form-group">
    <label class="sr-only" for="exampleInputEmail1">Password</label>
    <input type="email" class="form-control" id="exampleInputEmail11" placeholder="Email" name="email" required>
    <span class="help-block">Для восстановления пароля</span>
  </div>
  <div class="form-group">
    <select class="form-control" id="sel_class" name="class" required>';
    include('../work/get_class.php');
  echo'</select>
    <span class="help-block">Выберите класс персонажа</span>
  </div>
    <div class="form-group">
    <select class="form-control" name="gender" required>
        <option value="Мужской">Мужской</option>
        <option value="Женский">Женский</option>
    </select>
    <span class="help-block">Выберите пол персонажа</span>
  </div>
  <div class="bottom">
  <button type="submit" class="btn btn-default">Регистрация</button>
  </div>
</form>';
include('../footer.php');