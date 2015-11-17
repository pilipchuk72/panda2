<?php

/*Файл конфигурации
 */

//$HOST='kalams.mysql.ukraine.com.ua';
//$USER='kalams_jshop';
//$PASS='syutwdg6';
//$DB='kalams_jshop';
$HOST='localhost';
$USER='root';
$PASS='';
$DB='panda';
//$tabVM='vm_panda';
//$tabCATEGORIES='categories';
$IMGDIR='W:/domains/panda2/images';

$conn = mysql_connect($HOST,$USER,$PASS)  or die("Could not connect: " . mysql_error());
$basename=$DB;
mysql_select_db($basename, $conn);

ini_set('max_execution_time', '0');
error_reporting(E_ALL);
?>
