<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/30/14
 * Time: 8:08 PM
 */

echo '<body><div class="main"> ';
echo '<h1 class="main"><a class="not" href="index.php">' .$name . '</a></h1>';
echo '<div id="center"><img src="logo.jpg" width="250px"></div>';
echo '<script src="js/change_html5.js"></script>';
echo '<div><h2 class="enter">Вход в игру:</h2></div>';
echo '<form method="POST" role="form" action="../auth.php" id="authform">
  <div class="form-group">
    <label class="sr-only" for="exampleInputText1">Login</label>
    <input type="text" class="form-control" id="exampleInputText1" placeholder="Введите логин" name="login">';
if (empty($_GET['not'])) echo '<span class="help-block"></span>';
else echo '<span class="help-block" style="color: red">Неправельный логин или пароль!</span>';
echo '<span class="help-block"></span>
  </div>
  <div class="form-group">
    <label class="sr-only" for="exampleInputPassword2">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Пароль" name="pass">
    <span class="help-block"></span>
  </div>
  <div class="bottom">
  <button type="submit" class="btn btn-default">Вход</button>
  <a class="btn btn-default" href="../work/reg.php" role="botton">Регистрация</a>
  </div>
</form>';


