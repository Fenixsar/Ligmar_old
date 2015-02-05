<?php
include ('work/start.php');

$salt = '$2a$10$'.substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(),mt_rand()))), 0, 22) . '$';
$pass = 'sdffgbfgh';
echo $hashed_password = crypt($pass, $salt);

include('work/end.php');
printf('%.4F сек', $time);


//$lol = '11.11.94';
//echo md5($lol); 