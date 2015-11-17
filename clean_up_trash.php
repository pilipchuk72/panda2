<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set('max_execution_time', '0');
error_reporting(E_ALL);
require('/classes/phpQuery-onefile.php');
require('config.php');
require('controler.php');

//insert_categories();

$urls_from_product = run_sql('SELECT * FROM products');
while ($row = mysql_fetch_array($urls_from_product)) {
     $page_pq = phpQuery::newDocument($row['description']);
    $res_items=pq($page_pq)->find('td');
	foreach ($res_items as $item) {
		$clean_str=pq($item)->text();
                pq($item)->text($clean_str);
                pq($item)->removeAttr("class");
                pq($item)->removeAttr("height");
                pq($item)->removeAttr("width");
                
	};
        
        $descr=$page_pq->html(); 
        $sql='UPDATE products SET description="'.$descr.' WHERE id='.$row['id'];
        run_sql($sql);
        echo $row['id'].'_____________<br>';
}

?>
