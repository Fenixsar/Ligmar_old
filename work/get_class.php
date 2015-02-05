<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/28/14
 * Time: 12:00 AM
 */
defined('PROTECTOR') or die('Error: restricted access');

include ('db.php');

$stmt = $db->prepare('SELECT * from class order by id');
$stmt -> execute();
if (!$stmt) {
    echo "\nPDO::errorInfo():\n";
    print_r($db->errorCode());
}
while($row = $stmt->fetch()) {
    echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
}