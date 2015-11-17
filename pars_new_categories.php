<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require('phpQuery-onefile.php');
require('config.php');
require('controler.php');

//insert_categories();
$mysqli = new mysqli($HOST, $USER, $PASS, $DB);
$urls_from_category = $mysqli->query('select id, url from _categories');

while ($row = $urls_from_category->fetch_array()) {
// URL первой страници категории
    $url_category = $row['url'] . '?limit=48';
    $id_category = $row['id'];
    insert_new_product($id_category, $url_category);
}


// функция парсит категории и записывает в таблицу categories
function insert_categories()
{
    $results = pq_page('http://www.pandawill.com/mobile-phone-c1.html?limit=48');
// Находим названия категорий
    $categories = $results->find(".gn_col:eq(1) li");


// Перебираем категории - записываем в табличку categories 
    foreach ($categories as $category) {
        $category_link = pq($category)->find('a')->attr('href');
        $category_name = pq($category)->find('a')->text();
        $query = "insert into _categories (name, link) values ('{$category_name}','{$category_link}') ";
//записываем в табличку categories
        mysql_query($query);
// $results = pq_page($category_link);
    }
}

function insert_new_product($id_category, $UURRLL)
{
    global $mysqli;
    pq_page($UURRLL);
    $res_items = pq('ul.products-grid>li');
    foreach ($res_items as $item) {
        try {
            $ii = pq($item);
            $productUrl = $ii->find('a.product-image')->attr('href');
            $sql1 = "select count(*) as count from _products where url='{$productUrl}' limit 1";
            $res = $mysqli->query($sql1);
            $data = $res->fetch_assoc();
            if ($data[count] == 0) {
                $sql2 = "select count(*) as count from _products_new where url='{$productUrl}' limit 1";
                $res2 = $mysqli->query($sql2);
                $data_new = $res2->fetch_assoc();
                if ($data_new[count] == 0) {
                    $imgSmallUrl = $ii->find("img")->attr('taikoo_lazy_src');
                    $product_name = $ii->find('.product-name>a')->text();
                    $product_sql = "INSERT _products_new (url, ids_category, name, img_small, status)
                            VALUES ('{$productUrl}', {$id_category}, '{$product_name}', '{$imgSmallUrl}', 1)";
                    run_sql($product_sql);
                } else {
                    $sql1 = "select * from _products where url='{$productUrl}' limit 1";
                    $res = run_sql($sql1);
                    $data2 = mysql_fetch_assoc($res);
                    $category_old_value = (string)$data2[ids_category];
                    $sql = " UPDATE _products_new SET ids_category='{$category_old_value},{$id_category}' WHERE url='{$productUrl}'";
                    run_sql($sql);
                };
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    return $res;
}

?>
