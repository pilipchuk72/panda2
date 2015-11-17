<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set('max_execution_time', '0');
error_reporting(E_ALL);
require('phpQuery-onefile.php');
require('config.php');
require('controler.php');

//insert_categories();
$mysqli = new mysqli($HOST, $USER, $PASS, $DB);
$urls_from_category = $mysqli->query('select * from _products where _products.description IS null');
$i=1;
while ($row = $urls_from_category->fetch_array()) {
    echo $i.'-'.$row['img_small'].'<br>';
    get_images($row['img_small'],'D:\OpenServer\domains\panda2\images\small', 's'.$row['id'].'.jpg');
    $i++;
    $url=$row['url'];
    run_sql("UPDATE _products SET status=2 WHERE url='{$url}'");
}

?>
