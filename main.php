<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/30/14
 * Time: 4:20 PM
 */

//namespace main;

class main extends PDO {
    private $db_host = "localhost";
    private $db_user = "root";
    private $db_name = "game";
    private $db_pass = "53Hpontar";
    protected $login;
    protected $pass;
    protected $id;
    protected $db;
    public $loc = array();
    public $mob = array();

    //Подключеие к БД
    protected function connectToDb(){
        try {
            $this->db = new PDO("mysql:host=$this->db_host; dbname=$this->db_name", $this->db_user, $this->db_pass, array(
                PDO::ATTR_PERSISTENT => true));
            $this->db->exec('SET NAMES utf8');
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
        include ('work/protect.php');
//
//        $stop_injection = new InitVars();
//        $stop_injection->checkVars();
    }
    //Метод поиска пользователя по логину
    public function searchUser ($login){
        $this->connectToDb();
        $sql = 'SELECT * from users where login="' . $login . '"';
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            return $row['id'];
        }
        return 0;
    }
    //Метод поиска пользователя для авторизации
    public function searchForAuth(){
        $auth = 0;
        $sql = 'SELECT * from users where login="' . $this->login . '" AND pass="' . $this->pass . '"';
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            $auth = $row['id'];
        }
        return $auth;
    }
    //Конструктор
    function __construct($login,$pass){
        $this->login = $login;
        $this->pass = $pass;
    }
    //Метод получения персонажа
    function getCharacter($id){
        $sql = "SELECT * FROM characters WHERE id = {$id}";
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            return $row;
        }
    }
    //Метод получения локаций
    public function getLocation(){
        $sql = 'SELECT * from locations where type = "mob" and loc = "' . $this->array['loc'] . '"';
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            $this->loc[] = $row;
        }
    }
    //Метод получения моба
    public function getMob($loc){
        $sql = "SELECT id, name, description from battles_with_mobs where loc = '{$loc}'";
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            $mobs[] = $row;
        }
        return $mobs;
    }
    //Начисление монеток с мобов
    public function addGold($gold){
        $sql = 'UPDATE users SET gold = ' . $gold . ' WHERE login = "' . $this->login . '"';
        $this->db->exec($sql);
    }
    //Получение баланса золота
    public function getGold(){
        $sql = 'SELECT gold FROM users WHE RE login = "' . $this->login . '"';
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            $gold = $row;
        }
        return $gold['gold'];
    }
    public function sendFeedback($name,$massage){
        $sql = "INSERT INTO feedbacks SET name = '{$name}', massage = '{$massage}'";
        $this->db->exec($sql);
    }
}

class character extends main {
    public $array = array();
    public $battle = array();
    public $quests = array();
    public $things = array();
    public $eqip;
    public $bag_free;
    public $a;

    //Конструктор
    function __construct($login,$pass){
        $this->connectToDb();
        $this->login = $login;
        $this->pass = $pass;
        $sql = 'SELECT * from characters where id IN (SELECT char_id FROM users WHERE login ="' . $this->login . '")';
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            $this->array = $row;
        }
    }
    //Функция определения %опыта
    public function getExpPercent(){
        return round(($this->array['exp']-$this->array['exp_was'])/(($this->array['exp_need'] - $this->array['exp_was'])/100) , 2);
    }













    //Метод для улучшения хар-ки
    public function upCharacter($char){
        switch ($char){
            case 1:
                $sql = 'UPDATE characters SET str_self = ' . ($this->array['str_self'] + 1) . ', free_char = ' .
                    ($this->array['free_char'] - 1) . ' WHERE id = ' . $this->array['id'];
                $this->db->exec($sql);
                echo '<script type="text/javascript">
                    window.location = "../user?update=1"
                    </script>';
                break;
            case 2:
                $sql = 'UPDATE characters SET dex_self = ' . ($this->array['dex_self'] + 1) . ', free_char = ' .
                    ($this->array['free_char'] - 1) . ' WHERE id = ' . $this->array['id'];
                $this->db->exec($sql);
                echo '<script type="text/javascript">
                    window.location = "../user?update=1"
                    </script>';
                break;
            case 3:
                $sql = 'UPDATE characters SET vit_self = ' . ($this->array['vit_self'] + 1) . ', free_char = ' .
                    ($this->array['free_char'] - 1) . ' WHERE id = ' . $this->array['id'];
                $this->db->exec($sql);
                echo '<script type="text/javascript">
                    window.location = "../user?update=1"
                    </script>';
                break;
            case 4:
                $sql = 'UPDATE characters SET int_self = ' . ($this->array['int_self'] + 1) . ', free_char = ' .
                    ($this->array['free_char'] - 1) . ' WHERE id = ' . $this->array['id'];
                $this->db->exec($sql);
                echo '<script type="text/javascript">
                    window.location = "../user?update=1"
                    </script>';
                break;
            default:
                echo '<script type="text/javascript">
                window.location = "/"
                </script>';
        }
    }
    //Метод для обновления всех статов
    public function updateAllStates($type){
        //Если передается параметр 1, то снять 5% опыта
        if ($type == 1){
            $exp = $this->array['exp'] - ($this->array['exp_need'] - $this->array['exp_was'])/20;
            if ($exp < $this->array['exp_was']) $exp = $this->array['exp_was'];
            $sql = 'UPDATE characters SET exp = ' . $exp . ', battle=0 WHERE name = "' . $this->array['name'] . '"';
            $this->db->exec($sql);
        }
        //Получаем массив снаряжения и сумму всех стат с него
        $this->getEqip();
        for ($i = 0; $i < 11; $i++){
            if(isset($this->eqip['eqip' . $i])){
               if (empty($summ)){
                   $summ = $this->getThing($this->eqip['eqip' . $i]);
               }
               else{
                   $boo = $this->getThing($this->eqip['eqip' . $i]);
                   foreach($boo as $k => $v)
                       array_key_exists($k,$summ) ? $summ[$k] += $v : $summ[$k] = $v;
               }
            }
        }
        $sql = 'SELECT * from class where name="' . $this->array['class'] . '" LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            $class = $row;
        }

        $this->array['str'] = $summ['str'] + $this->array['str_self'];
        $this->array['dex'] = $summ['dex'] + $this->array['dex_self'];
        $this->array['vit'] = $summ['vit'] + $this->array['vit_self'];
        $this->array['intel'] = $summ['intel'] + $this->array['int_self'];


        $health_max = $this->array['vit'] * $class['health_per_vitality'] + ($this->array['level'] - 1)*$class['health_per_lvl'] + $summ['self_hp'] + $summ['hp'];
        $mana_max = $this->array['intel'] * $class['mana_per_intellect'] + ($this->array['level'] - 1)*$class['mana_per_lvl'] + $summ['self_mana'] + $summ['mp'];

        $health_reg = ($this->array['level'] + $this->array['vit']/2 + 1)/10 + $summ['hp_reg'];
        $mana_reg = ($this->array['level'] + $this->array['intel']/1 + 1)/10 + $summ['mp_reg'];


        if($class['name'] == 'Воин') {
            $this->array['dmg_min'] = round(($summ['self_dmg_min'] + $summ['self_dmg'] + $this->array['level']) * ($this->array['str']/150 + 1));
            $this->array['dmg_max'] = round(($summ['self_dmg_max'] + $summ['self_dmg'] + $this->array['level']) * ($this->array['str']/150 + 1));
        }
        if($summ['dmg_mag'] == NULL){
            $summ['dmg_mag'] = 0;
        }
        $this->array['dmg_mag'] = $summ['dmg_mag'];

        $this->array['dmg_strike'] = ($this->array['dex']/100) + $summ['dmg_mag'] + 2;
        $this->array['strike'] = ceil($this->array['dex']/20 + $summ['strike']);

        $this->array['def'] = round(($this->array['str'] + $this->array['vit'])/4) + round($summ['def'] + $summ['self_def'])*(1 + round(((2*$this->array['vit']) + (3*$this->array['str']))/25)/100);
        $this->array['resist'] = round(($this->array['intel'] + $this->array['vit'])/4) + round($summ['resist'] + $summ['self_resist'])*(1 + round(((2*$this->array['vit']) + (3*$this->array['intel']))/25)/100);

        $this->array['accuracy'] = $this->array['dex']*10 + $summ['accur'];
        $this->array['dodge'] = $this->array['dex']*10 + $summ['dodge'] + $summ['self_dodge'];
        //Заносим все данные в БД
        $stmt = $this->db->exec('UPDATE characters SET
            str = ' . $this->array['str'] . ',
            dex = ' . $this->array['dex'] . ',
            vit = ' . $this->array['vit'] . ',
            intel = ' . $this->array['intel'] . ',
            health_max = ' . $health_max . ',
            health_reg = ' . $health_reg . ',
            mana_max = ' . $mana_max . ',
            mana_reg = ' . $mana_reg . ',
            dmg_min = ' . $this->array['dmg_min'] . ',
            dmg_max = ' . $this->array['dmg_max'] . ',
            dmg_mag = ' . $this->array['dmg_mag'] . ',
            dmg_strike = ' . $this->array['dmg_strike'] . ',
            strike = ' . $this->array['strike'] . ',
            def = ' . $this->array['def'] . ',
            resist = ' . $this->array['resist'] . ',
            accuracy = ' . $this->array['accuracy'] . ',
            dodge = ' . $this->array['dodge'] . ' WHERE id = ' . $this->array['id']);
        if (!$stmt) {
            echo "\nPDO::errorInfo():\n";
            print_r($this->db->errorCode());
        }
    }


    /*
     * Методы работы с квестами
     */
    //Получение списка и количества доступных квестов.
    public function getQuests(){
        $sql = "SELECT id FROM quests WHERE lvl_min <= {$this->array['level']} AND lvl_max >= {$this->array['level']}";
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        $i = 0;
        while($row = $stmt->fetch()) {
            $row['id'];
            $sql = "SELECT q{$row['id']} FROM quest WHERE id = {$this->array['id']}";
            $stmt1 = $this->db->prepare($sql);
            $stmt1 -> execute();
            while($row1 = $stmt1->fetch()) {
                if (!$row1['q' . $row['id']]){
                    $i++;
                    $q[] = $row['id'];
                }
            }
        }
        if ($q){
            $sql = 'SELECT id, name, type, brief_d, exp, gold, items, eqip FROM quests WHERE id IN (' . implode(", ", $q) .')';
            $stmt = $this->db->prepare($sql);
            $stmt -> execute();
            while($row = $stmt->fetch()) {
                $this->quests[] = $row;
            }
        }

        return $i;
    }
    //Получение информации по конретному квесту
    public function getQuest($id){
        $sql = "SELECT id, name, description, need_mobs_v, need_item_v, final, exp, gold, items, eqip FROM quests WHERE id = {$id}";
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            $this->quests = $row;
            return 1;
        }
    }
    //Активация квеста
    public function activQuest($id){
        $sql = "SELECT q{$id} FROM quest WHERE id = {$this->array['id']}";
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            if ($row["q{$id}"]) return 0;
        }
        $sql = "UPDATE quest SET q{$id} = 1 WHERE id = {$this->array['id']}";
        $this->db->exec($sql);
        $sql = "INSERT INTO log_quests SET char_id = {$this->array['id']}, quest = {$id}, mobs = 0, mobs_n = {$this->quests['need_mobs_v']}, items = 0";
        $stmt = $this->db->exec($sql);
        if (!$stmt) {
            echo "\nPDO::errorInfo():\n";
            print_r($this->db->errorCode());
        }
        return 1;
    }
    //Получение списка активированных квестов
    public function getActivQuests(){
        $boo = '';
        $sql = "SELECT quest, mobs, items FROM log_quests WHERE char_id = {$this->array['id']}";
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            if ($boo != '')
                $boo = $boo . ', ';
            $q[] = $row;
            $boo = $boo . $row['quest'];
        }

        if ($boo == ''){
            echo '<div style="padding: 5px; color: #ffffff">Нет активных заданий!</div>';
            return 0;
        }
        $sql = "SELECT id, name, type, brief_d, need_mobs_v, need_item_v, exp, gold, items, eqip FROM quests WHERE id IN ({$boo})";
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            $quests[] = $row;
        }
        $k = 0;
        while ($quests[$k]['name']){
            echo '<div class="menu1"><a href="../quests?q=' . $quests[$k]['id'] . '" role="button" class="btn1 btn-link1 btn-block"><img src="../img/quests.png"> <span style="font-weight: bold">';
            echo $quests[$k]['name'] . '</span><br><span style="color:white;font-size:12px">';
            echo $quests[$k]['brief_d'] . '</span><br><span';
            if ($quests[$k]['need_mobs_v'] == $q[$k]['mobs'] || $q[$k]['items'] == $quests[$k]['need_item_v'])
                    echo ' style="color: limegreen">Прогресс: (';
            else
                echo '>Прогресс: (';
            if ($quests[$k]['need_mobs_v']){
                echo $q[$k]['mobs'] . '/' . $quests[$k]['need_mobs_v'];
            }
            else
                echo $q[$k]['items'] . '/' . $quests[$k]['need_item_v'];
            echo ')</span><br><span style="font-weight: bold">Награда: ';
            if ($quests[$k]['exp']){
                echo '<img src="../img/help_book.png" alt="Опыт"> <span style="font-weight: bold">' . $quests[$k]['exp'] . '</span> ';
            }
            if ($quests[$k]['gold']){
                echo '<img src="../img/credits.png" alt="Золото"> <span style="font-weight: bold">' . $quests[$k]['gold'] . '</span> ';
            }
            if ($quests[$k]['items'] or $quests[$k]['eqip']){
                echo '<img src="../img/treasure.png" alt="Предмет"> <span style="font-weight: bold">Предмет ???</span> ';
            }
            echo '</a></div>';
            $k++;
        }
    }
    //Проверка на состояние квеста
    public function checkQuest($id){
        $sql = "SELECT q{$id} FROM quest WHERE id = {$this->array['id']}";
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            if ($row["q{$id}"] == 1){
                $sql = "SELECT need_mobs_v, need_item_v FROM quests WHERE id = {$id}";
                $stmt1 = $this->db->prepare($sql);
                $stmt1 -> execute();
                while($row1 = $stmt1->fetch()) {
                    if ($row1['need_mobs_v']){
                        $need = $row1['need_mobs_v'];
                        $bool = 1;
                    }
                    else
                        $need = $row1['need_item_v'];
                }
                $sql = "SELECT mobs,items FROM log_quests WHERE quest = {$id} AND char_id = {$this->array['id']}";
                $stmt1 = $this->db->prepare($sql);
                $stmt1 -> execute();
                while($row1 = $stmt1->fetch()) {
                    if ($bool){
                        if ($need == $row1['mobs']){
                            echo '<div class="text">' . $this->quests['final'] . '</div>';
                            echo '<div style="padding-left:5px; color:limegreen">Прогресс: (' . $row1['mobs'] . '/' . $need . ')</div>';
                            echo '<div class="menu1"><a href="../work/cancel_quest.php?q=' . $id . '" role="button" class="btn btn-link btn-block">Получить награду</a></div>';
                        }
                        else{
                            echo '<div class="text">' . $this->quests['description'] . '</div>';
                            echo '<div style="padding-left:5px">Прогресс: (' . $row1['mobs'] . '/' . $need . ')</div>';
                            echo '<div class="menu1"><a href="../work/cancel_quest.php?q=' . $id . '" role="button" class="btn btn-link btn-block">Отказаться от задания</a></div>';
                        }
                    }
                    else //Если для завершения задания требуется вещь
                        $need = $row1['need_item_v'];
                }


            }
            elseif ($row["q{$id}"] == 2){
                echo '<div class="text">' . $this->quests['description'] . '</div>';
                echo '<div class="menu1" style="text-align: center">Это задание уже выполнено</div>';
            }
            else {
                echo '<div class="text">' . $this->quests['description'] . '</div>';
                echo '<div class="menu1"><a href="../work/act_quest.php?q=' . $id . '" role="button" class="btn btn-link btn-block">Принять задание</a></div>';
            }
        }
    }
    //Отказ и сдача квеста
    public function cancelQuest($id){
        $sql = "SELECT q{$id} FROM quest WHERE id = {$this->array['id']}";
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            if($row["q{$id}"] == 1){
                $sql = "SELECT mobs,items FROM log_quests WHERE quest = {$id} AND char_id = {$this->array['id']}";
                $stmt1 = $this->db->prepare($sql);
                $stmt1 -> execute();
                while($row1 = $stmt1->fetch()) {
                    if ($this->quests['need_mobs_v'] == $row1['mobs']){
                        $last_id = 0;
                        if($this->quests['eqip']){
                            if ($this->getBag() == $this->array['bag']){
                                header("Location: ../quests/?not=4");
                            }
                            else{
                                $sql = "INSERT
                                          INTO things (char_id, kind, q,type,main_type,class,name,lvl,cost, socket,
                                          improvement,def,resist,dmg_min,dmg_max,dmg,mag_dmg,dodge,accur,strike,hp,
                                          hp_reg,mp,mp_reg,str,dex,vit,intel,n_lvl,n_str,n_dex,n_int,img)
                                        SELECT
                                            char_id, kind, q,type,main_type,class,name,lvl,cost, socket,improvement,def,
                                            resist,dmg_min,dmg_max,dmg,mag_dmg,dodge,accur,strike,hp,hp_reg,mp,mp_reg,
                                            str,dex,vit,intel,n_lvl,n_str,n_dex,n_int,img
                                        FROM
                                            things
                                        WHERE
                                            id = {$this->quests['eqip']}";
                                $this->db->exec($sql);
                                $sql = 'SELECT id FROM things ORDER BY id DESC LIMIT 1';
                                $stmt = $this->db->prepare($sql);
                                $stmt -> execute();
                                while($row = $stmt->fetch()) {
                                    $last_id = $row['id'];
                                }
                                $sql = "UPDATE things SET char_id = {$this->array['id']} WHERE id = {$last_id}";
                                $this->db->exec($sql);
                                $sql = "UPDATE bags SET bag{$this->bag_free} = {$last_id} WHERE id = {$this->array['id']}";
                                $this->db->exec($sql);
                            }
                        }
                        $sql = "DELETE FROM log_quests WHERE quest = {$id} AND char_id = {$this->array['id']}";
                        $this->db->exec($sql);
                        $sql = "UPDATE quest SET q{$id} = 2 WHERE id = {$this->array['id']}";
                        $this->db->exec($sql);
                        $gold = $this->getGold() + $this->quests['gold'];
                        $exp = $this->array['exp'] + $this->quests['exp'];
                        $this->updateExp($exp);
                        $this->addGold($gold);
                        $sql = "UPDATE notifications SET see = 1 WHERE link = {$this->quests['id']} AND char_id = {$this->array['id']}";
                        $this->db->exec($sql);
                        $time = time();
                        $sql = "INSERT INTO notifications SET char_id = {$this->array['id']}, type = 'quest_f', link = {$last_id},
                            text = 'Вы получили {$this->quests['gold']} золота и {$this->quests['exp']} опыта', time = {$time}";
                        $this->db->exec($sql);
                        header("Location: ../quests");
                    }
                    else{
                        $sql = "DELETE FROM log_quests WHERE quest = {$id} AND char_id = {$this->array['id']}";
                        $this->db->exec($sql);
                        $sql = "UPDATE quest SET q{$id} = NULL WHERE id = q{$id}";
                        $this->db->exec($sql);
                        header("Location: ../quests");
                    }
                }
            }
            else
                header("Location: ../quests/?not=2");
        }
    }
    //Получение квестов и количество мобов, которое нужно
    public function getQuestsAndMobs($id){
        $sql = "SELECT id FROM quests WHERE need_mobs_id = {$id}";
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            $q_id[] = $row['id'];
        }
        $a = implode(", ", $q_id);
        $sql = "SELECT id, quest, mobs, mobs_n FROM log_quests WHERE char_id = {$this->array['id']} AND quest IN ({$a})";
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            $mobs = $row['mobs'] + 1;
            if ($mobs <= $row['mobs_n']) {
                $sql = "UPDATE log_quests SET mobs = {$mobs} WHERE id = {$row['id']}";
                $this->db->exec($sql);
                if ($mobs == $row['mobs_n']){
                    $time = time();
                    $sql = "SELECT name FROM quests WHERE id = {$row['quest']}";
                    $stmt1 = $this->db->prepare($sql);
                    $stmt1 -> execute();
                    while($row1 = $stmt1->fetch()) {
                        $name = $row1['name'];
                    }
                    $sql = "INSERT INTO notifications SET char_id = {$this->array['id']}, type = 'quest', link = {$row['quest']}, text = '{$name}', time = {$time}";
                    $this->db->exec($sql);
                }

            }

        }
    }
    /*
     * Методы работы с уведомлениями
     */
    //Проверка на новые уведомления

    //Получение списка предметов рюкзака
    public function getBag(){
        for($i = 0; $i < $this->array['bag']; $i++){
            $a[] ="bag" . $i;
        }
        $b = implode(", ", $a);
        $sql = "SELECT {$b} FROM bags WHERE id = {$this->array['id']}";
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            $this->things = $row;//Массив вещей в рюкзаке
        }
        $k = 0;
        for($i = 0; $i < $this->array['bag']; $i++){
            if ($this->things['bag' . $i]){
                $k++;
            }
            else{
                $this->bag_free = $i;//Последняя свободная ячейка
            }
        }
        return $k;//Количество занятых ячеек
    }
    //Получение списка предметов сундука
    public function getBox(){
        $sql = 'SELECT box from users where login="' . $this->login . '"';
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            $box = $row['box'];
        }
        for($i = 0; $i < $box; $i++){
            $a[] ="box" . $i;
        }
        $b = implode(", ", $a);
        $sql = "SELECT {$b} FROM box WHERE id = {$this->array['id']}";
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            $this->things = $row;
        }
        $k = 0;
        for($i = 0; $i < $box; $i++){
            if ($this->things['box' . $i] != NULL){
                $k++;
            }
            else{
                $free = $i;//Последняя свободная ячейка
            }
        }
        $bok[] = $k;//Количество занятых ячеек
        $bok[] = $box;//Общее количество ячеек в сундуке
        $bok[] = $free;
        return $bok;
    }
    //Получение списка предметов шкатулки
    public function getCasket(){
        $sql = "SELECT * FROM casket WHERE id = {$this->array['id']}";
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            return $row;//Массив вещей в рюкзаке
        }
    }
    //Получение информации по конкретной вещи
    public function getThing($id){
        $sql = "SELECT * FROM things WHERE id = {$id}";
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            return $row;
        }
    }
    //Продажа вещи
    public function sellThing($id, $id_bag,$type){
        if($type == 1){
            $place = 'bag';
            $sql = "SELECT bag{$id_bag} FROM bags WHERE id = {$this->array['id']}";
        }
        else{
            $place = 'box';
            $sql = "SELECT box{$id_bag} FROM box WHERE id = {$this->array['id']}";
        }

        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            $lol = $row[$place . $id_bag];
        }
        if ($id != $lol)
            header("Location: ../");
        else{
            $sql = "SELECT char_id, cost FROM things WHERE id = {$id}";
            $stmt = $this->db->prepare($sql);
            $stmt -> execute();
            while($row = $stmt->fetch()) {
                $cost = $row['cost'];
                $q = $row['char_id'];
            }
            $gold = $this->getGold() + $cost;
            $this->addGold($gold);
            if ($q != 0){
                $sql = 'DELETE FROM things WHERE id = ' . $id;
                $this->db->exec($sql);
            }
            if($type == 1){
                $sql = "UPDATE bags SET bag{$id_bag} = NULL WHERE id = {$this->array['id']}";
                $this->db->exec($sql);
                header("Location: ../user/bag.php");
            }
            else{
                $sql = "UPDATE box SET box{$id_bag} = NULL WHERE id = {$this->array['id']}";
                $this->db->exec($sql);
                header("Location: ../user/box.php");
            }
        }

    }
    //Проверка на наличие вещи у персонажа
    public function checkThing($id){
        $sql = "SELECT char_id FROM things WHERE id = {$id}";
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            return $row['char_id'];
        }
    }
    //Проверка на возможность надеть
    public function checkThingToPutOn($id){
        $thing = $this->getThing($id);
        $cl = explode(", ", $thing['class']);
        $k = 0;
        for($i = 0;$i < count($cl); $i++){
            if($cl[$i] == 'Все'){
                $k = 1;
            }
            elseif($cl[$i] == $this->array['class']){
                $k = 1;
            }
        }
        if($k == 0){
            return 0;
        }
        elseif($thing['n_lvl'] > $this->array['level']){

        }
        elseif($thing['n_str'] > $this->array['str']){
            return 0;
        }
        elseif($thing['n_dex'] > $this->array['dex']){
            return 0;
        }
        elseif($thing['n_int'] > $this->array['intel']){
            return 0;
        }
        else{
            return 1;
        }
    }
    //Перекладываем вещь
    public function putTo($to,$id){
        //Проверяем, есть ли этот предмет у персонажа
        $check = $this->searchThing($id);
        if($check == 999){
            return 999;
        }
        //Проверяем есть ли свободное место
        if($to == 'bag'){
            $bag = $this->getBag();
            if($bag >= $this->array['bag']){
                return 999;
            }
        }
        elseif($to == 'box'){
            $box = $this->getBox();
            if($box[1] <= $box[0]){
                return 999;
            }
        }
        elseif($to == 'eqip'){
            if($this->checkThingToPutOn($id) == 0){
                return 999;
            }
        }
        if ($check == 2){
            $sql = "UPDATE equipment SET eqip{$this->a} = NULL WHERE id = {$this->array['id']}";
            $this->db->exec($sql);
            $this->updateAllStates(0);
        }
        elseif ($check == 1){
            $sql = "UPDATE bags SET bag{$this->a} = NULL WHERE id = {$this->array['id']}";
            $this->db->exec($sql);
        }
        elseif ($check == 0){
            $sql = "UPDATE box SET box{$this->a} = NULL WHERE id = {$this->array['id']}";
            $this->db->exec($sql);
        }
        else{
            return 999;
        }
        if($to == 'bag'){
            $sql = "UPDATE bags SET bag{$this->bag_free} = {$id} WHERE id = {$this->array['id']}";
            $this->db->exec($sql);
        }
        if($to == 'box'){
            $sql = "UPDATE box SET box{$box['2']} = {$id} WHERE id = {$this->array['id']}";
            $this->db->exec($sql);
        }
        if($to == 'eqip'){
            $t = $this->getThing($id);
            //Проверка на тип снаряжения
            if($t['main_type'] == 'weapon'){
                $i = 0;
            }
            if($t['main_type'] == 'head'){
                $i = 1;
            }
            if($t['main_type'] == 'shoulders'){
                $i = 2;
            }
            if($t['main_type'] == 'neck'){
                $i = 3;
            }
            if($t['main_type'] == 'chest'){
                $i = 4;
            }
            if($t['main_type'] == 'hands'){
                $i = 5;
            }
            if($t['main_type'] == 'rings'){
                $sql = "SELECT eqip6, eqip7 FROM equipment WHERE id = {$this->array['id']}";
                $stmt = $this->db->prepare($sql);
                $stmt -> execute();
                while($row = $stmt->fetch()) {
                    if(isset($row['eqip6'])){
                        if(!isset($row['eqip7']))   {
                            $i = 7;
                        }
                        else
                            $i = 6;
                    }
                    else
                        $i = 6;
                }
            }
            if($t['main_type'] == 'belt'){
                $i = 8;
            }
            if($t['main_type'] == 'legs'){
                $i = 9;
            }
            if($t['main_type'] == 'foot'){
                $i = 10;
            }
            $sql = "SELECT eqip{$i} FROM equipment WHERE id = {$this->array['id']}";
            $stmt = $this->db->prepare($sql);
            $stmt -> execute();
            while($row = $stmt->fetch()) {
                $q = $row['eqip' . $i];
            }
            if($q){
                if ($check == 1){
                $sql = "UPDATE bags SET bag{$this->a} = {$q} WHERE id = {$this->array['id']}";
                $this->db->exec($sql);
                }
                if ($check == 0){
                    $sql = "UPDATE box SET box{$this->a} = {$q} WHERE id = {$this->array['id']}";
                    $this->db->exec($sql);
                }
            }
            $sql = "UPDATE equipment SET eqip{$i} = {$id} WHERE id = {$this->array['id']}";
            $this->db->exec($sql);
            $this->updateAllStates(0);
            }

    }
    //Получаем снаряжение
    public function getEqip(){
        $sql = "SELECT * FROM equipment WHERE id = {$this->array['id']}";
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        while($row = $stmt->fetch()) {
            $this->eqip = $row;
        }
    }
    //Поиск вещи у персонажа
    public function searchThing($id){
        $t = $this->checkThing($id);
        if ($t == $this->array['id']){
            //Проверяем снаряжение
            $sql = "SELECT * FROM equipment WHERE id = {$this->array['id']}";
            $stmt = $this->db->prepare($sql);
            $stmt -> execute();
            while($row = $stmt->fetch()) {
                $eqip = $row;
            }
            $i = 0;
            while ($i < 11){
                if ($eqip['eqip' . $i] == $id){
                    $this->a = $i;
                    return 2;
                }
                $i++;
            }
            //Проверяем рюкзак
            $sql = "SELECT * FROM bags WHERE id = {$this->array['id']}";
            $stmt = $this->db->prepare($sql);
            $stmt -> execute();
            while($row = $stmt->fetch()) {
                $bag = $row;
            }
            $i = 0;
            while ($i < 50){
                if ($bag['bag' . $i] == $id){
                    $this->a = $i;
                    return 1;
                }
                $i++;
            }
            //Проверяем сундук
            $sql = "SELECT * FROM box WHERE id = {$this->array['id']}";
            $stmt = $this->db->prepare($sql);
            $stmt -> execute();
            while($row = $stmt->fetch()) {
                $box = $row;
            }
            $i = 0;
            while ($i < 100){
                if ($box['box' . $i] == $id){
                    $this->a = $i;
                    return 0;
                }
                $i++;
            }
        }
        else{
            return 999;
        }
    }
    //Присвоение вещи персонажу
    public function giveThing($id,$char_id){
        $sql = "UPDATE things SET char_id = {$char_id} WHERE id = {$id}";
        $this->db->exec($sql);
        $bag = $this->getBag();
        if($bag >= $this->array['bag']){
            return -1;
        }
        else{
            $sql = "UPDATE bags SET bag{$this->bag_free} = {$id} WHERE id = {$char_id}";
            $this->db->exec($sql);
            return $this->bag_free;
        }
    }

    //Получение массива персонажей в пати
    public function getParty(){
        $sql = "SELECT * FROM party WHERE id = {$this->array['party']}";
        $stmt = $this->db->prepare($sql);
        $stmt -> execute();
        $party = array();
        while($row = $stmt->fetch()) {
            $party[] = $row['id'];
            if($row['char_1'] && $row['char_1'] != $this->array['id'])
            $party[] = $row['char_1'];
            if($row['char_2'] && $row['char_2'] != $this->array['id'])
            $party[] = $row['char_2'];
            if($row['char_3'] && $row['char_3'] != $this->array['id'])
            $party[] = $row['char_3'];
            if($row['char_4'] && $row['char_4'] != $this->array['id'])
            $party[] = $row['char_4'];
            if($row['char_5'] && $row['char_5'] != $this->array['id'])
            $party[] = $row['char_5'];
            if($row['char_6'] && $row['char_6'] != $this->array['id'])
            $party[] = $row['char_6'];
        }
        return $party;
    }
}