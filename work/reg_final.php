<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/28/14
 * Time: 12:54 AM
 */
//define('PROTECTOR', 1);
include('../work/start.php');

session_start();
$title = 'Регистрация завершена';
$login = $_POST['login'];

include('../header.php');
include('../work/db.php');

//Получаем id последнего персонажа
$sql = 'SELECT * FROM characters ORDER BY id DESC LIMIT 1';
$stmt = $db->prepare($sql);
$stmt -> execute();
while($row = $stmt->fetch()) {
    $char_id = $row['id'];
}
$char_id++;
//Добавляем нового пользователя
$sql = 'INSERT INTO users (login,pass,ip,email,char_id,gold,box,last_visit)
     values ("' . $login .'" , "' . md5($_POST['pass']) .'" ,"' . $_SERVER['REMOTE_ADDR']  . '" , "' . $_POST['email']  .
    '" , "' . $char_id  . '", 0,30,"' . date('Y-m-d') . '")';
$stmt = $db->exec($sql);
$last_id = $stmt = $db->lastInsertId();
if (!$stmt) {
    echo "\nPDO::errorInfo():\n";
    print_r($db->errorCode());
    echo '<script type="text/javascript">
    window.location = "../work/reg.php?not=1"
    </script>';

}
//Получаем данные о классе

$sql = 'SELECT * from class where name="' . $_POST['class'] . '"';
$stmt = $db->prepare($sql);
$stmt -> execute();
while($row = $stmt->fetch()) {
    $class = $row;
}
if (!$stmt) {
    echo "\nPDO::errorInfo():\n";
    print_r($db->errorCode());
}
//Создаем нового персонажа
$stmt = $db->exec('INSERT INTO characters SET
    name = "' . $_POST['login'] . '",
    user = "' . $_POST['login'] . '",
    class = "' . $_POST['class'] . '",
    gender = "' . $_POST['gender'] . '",
    exp = 0,
    exp_was = 0,
    exp_need = 50,
    level = 1,
    str_self = 5,
    dex_self = 5,
    vit_self = 5,
    int_self = 5,
    str = 5,
    dex = 5,
    vit = 5,
    intel = 5,
    health = ' . $class['health'] . ',
    health_max = ' . $class['health'] . ',
    health_reg = ' . $class['health_reg'] . ',
    mana = ' . $class['mana'] . ',
    mana_max = ' . $class['mana'] . ',
    mana_reg = ' . $class['mana_reg'] . ',
    dmg_min = 1,
    dmg_max = 2,
    dmg_type = ' . $class['dmg_type'] . ',
    strike = 1,
    aspeed = ' . $class['aspeed'] . ',
    def = 3,
    resist = 2,
    accuracy = ' . $class['accur_per_dex']*5 . ',
    dodge = ' . $class['dodge_per_dex']*5 . ',
    free_char = 0,
    ctbh = ' . $class['ctbh'] . '
');
if (!$stmt) {
    echo "\nPDO::errorInfo(666):\n";
    print_r($db->errorCode());
}
if ($_POST['class'] == 'Воин'){
    $sql = "SELECT type, main_type, class, name, n_str, self_dmg, self_dur, cost, img from standard_things where id = 1";
    $stmt = $db->prepare($sql);
    $stmt -> execute();
    while($row = $stmt->fetch()) {
        $weapon = $row;
    }
    $min = round($weapon['self_dmg']*0.8);
    $max = round($weapon['self_dmg']*1.2);
    $sql = "INSERT INTO things SET
        char_id ={$char_id},
        kind = 1,
        q = 1,
        type = '{$weapon['type']}',
        main_type = '{$weapon['main_type']}',
        class = '{$weapon['class']}',
        name = '{$weapon['name']}',
        n_str = {$weapon['n_str']},
        self_dmg_min = {$min},
        self_dmg_max = {$max},
        self_dur = {$weapon['self_dur']},
        self_dur_now = {$weapon['self_dur']},
        cost = {$weapon['cost']},
        img = '{$weapon['img']}'";
    $stmt = $db->exec($sql);
    $stmt = $db->lastInsertId();
    $eqip = "INSERT INTO equipment SET id = {$char_id}, eqip0 = {$stmt}";
}

//Создаем для него рюкзак и снаряжение
$stmt = $db->exec("INSERT INTO bags SET id = {$char_id}");
$stmt = $db->exec("INSERT INTO casket SET id = {$char_id}");
$stmt = $db->exec("INSERT INTO box SET id = {$last_id}");
$stmt = $db->exec("INSERT INTO storage SET id = {$last_id}");
$stmt = $db->exec($eqip);
//Создаем для него таблицу квестов и статистики
$stmt = $db->exec("INSERT INTO quest SET id = {$char_id}");
$stmt = $db->exec("INSERT INTO statistic SET id = {$char_id}");
//Обновляем все статы




//Завершение регистрации
echo '<body><div class="main"> ';
echo '<h1 class="main">' .$name . '</h1>';
echo '<div id="center"><img src="../logo.jpg" width="250px"></div>';
echo '<div><h2 class="enter">Регистрация успешно завершена!</h2></div>';
echo '<div><p class="enter"><a href="/">Войти в игру</a></p></div>';

include('../footer.php');

$_SESSION['login'] = $login;
$_SESSION['pass'] = md5($_POST['pass']);
setcookie("login", $login);
setcookie("pass", $pass);
