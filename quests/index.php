<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2/19/14
 * Time: 10:37 PM
 */
date_default_timezone_set('Russia/Moscow');
include('../work/start.php');
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2/6/14
 * Time: 12:58 AM
 */

session_start();
$title = 'Задания';
include('../main.php');
include('../work/check.php');
$user = new character($login,$pass);
$user_id = $user->searchForAuth();

if($user_id == 0){
    echo '<script type="text/javascript">
    window.location = "../index.php"
    </script>';
}
else {
    include('../header.php');
    echo '<body><div class="main"> ';
    include('../top.php');
    if(!$boo)
        echo '<div id="center" style="margin-bottom: 15px"><img src="../img/kvest.jpg" width="170px"></div>';
    if ($_GET['q']){
        if ($user->getQuest($_GET['q'])){
            echo '<div class="menu"><h3><img src="../img/quests.png"> ' . $user->quests['name'] . '</h3></div>';
            $user->checkQuest($_GET['q']);
            echo '<div style="font-weight: bold; padding: 5px">Награда: ';
            if ($user->quests['exp']){
                echo '<img src="../img/help_book.png" alt="Опыт"> <span style="font-weight: bold">' . $user->quests['exp'] . '</span> ';
            }
            if ($user->quests['gold']){
                echo '<img src="../img/credits.png" alt="Золото"> <span style="font-weight: bold">' . $user->quests['gold'] . '</span> ';
            }
            if ($user->quests['items'] or $user->quests['eqip']){
                echo '<img src="../img/treasure.png" alt="Предмет"> <span style="font-weight: bold">Предмет ???</span></div> ';
            }
        }
        else{
            echo '<div class="menu"><h3>Ошибка!</h3></div>';
            echo 'Извините, но такого задания не существует.';
        }
    }
    elseif($_GET['not']){
        echo '<div class="menu"><h3>Ошибка!</h3></div>';
        if($_GET['not'] == 1)
            echo 'К сожалению, Вы не можете принять это задание, т.к. оно либо выполнено, либо уже принято.';
        elseif($_GET['not'] == 2)
            echo 'Извините, но такого задания не существует.';
        elseif($_GET['not'] == 3)
            echo 'Извините, но Вы уже выполнили это задание или оно Вам не доступно.';
        elseif($_GET['not'] == 4)
            echo 'Вы не можете завершить задание, т.к. рюкзак полон.';
    }
    else{
        echo '<div class="menu"><h3>Задания</h3></div>';
        $quests = $user->getQuests();
        echo '<div><a href="../quests" role="button" class="btn btn-link btn-block1" style="width: 49%">Активные</a>
            <a href="../quests?can=1" role="button" class="btn btn-link btn-block1" style="width: 49%">Доступные (';
        echo $quests;
        echo ')</a></div>';

        if (!$_GET['can']){
            $user->getActivQuests();
        }
        else {
            if ($quests == 0){
                echo 'Нет доступных заданий!';
            }
            else{
                $i = 0;
                while ($user->quests[$i]['name']){
                    echo '<div class="menu1"><a href="../quests?q=' . $user->quests[$i]['id'] . '" role="button" class="btn1 btn-link1 btn-block"><img src="../img/quests.png"> <span style="font-weight: bold">';
                    echo $user->quests[$i]['name'] . '</span><br><span style="color:white;font-size:12px">';
                    echo $user->quests[$i]['brief_d'] . '</span><br><span>Награда: ';
                    if ($user->quests[$i]['exp']){
                        echo '<img src="../img/help_book.png" alt="Опыт"> <span style="font-weight: bold">' . $user->quests[$i]['exp'] . '</span> ';
                    }
                    if ($user->quests[$i]['gold']){
                        echo '<img src="../img/credits.png" alt="Золото"> <span style="font-weight: bold">' . $user->quests[$i]['gold'] . '</span> ';
                    }
                    if ($user->quests[$i]['items'] or $user->quests[$i]['eqip']){
                        echo '<img src="../img/treasure.png" alt="Предмет"> <span style="font-weight: bold">Предмет ???</span> ';
                    }
                    echo '</a></div>';
                    $i++;
                }

            }
        }
    }
    //Футер
    include('../footer.php');
}