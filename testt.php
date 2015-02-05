<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/28/14
 * Time: 6:48 PM
 */

include('header.php');




//echo "<div id='hit_target' class='row'>sdfsdfsdf</div>";
//
echo '<script type="text/javascript">
    $.ajax({
        type: "GET",
        url: "http://ex.ligmar.ru/ext.php"
        //data: { pass: "123654789", tel: "71111111111" },

    }).done(function(data){
        console.log(data);
    });
//        function petuh(){
//            $("#hit_target").html("Заебок!");
//            console.log("aaaaa");
//        }
//
    </script>';