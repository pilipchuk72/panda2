<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require('phpQuery-onefile.php');
require('config.php');
require('controler.php');

//insert_categories();

$urls_from_category = run_sql('select id, url from _categories where id=15');
while ($row = mysql_fetch_array($urls_from_category)) {
// URL первой страници категории
    $url_category = $row['url'] . '?limit=48';

    $id_category = $row['id'];
    echo $id_category . '=====' . $url_category . '<br>';
    pq_page($url_category);
    $count_goods = pq('p.amount:eq(0')->text();
//    $amount=str_replace(' total',"",$count_goods);
//    $amount=str_replace('Items 1 to 48 of ', '', $amount);
    $amount = trim(str_replace(' Item(s)', '', str_replace(' total', '', str_replace('Items 1 to 48 of ', '', $count_goods))));
    if ($amount > 48) {
        $number_page = ceil($amount / 48);
        for ($i = 1; $i <= $number_page; $i++) {
            $UURRLL = $url_category . '&p=' . $i;
            echo '-------------  ' . $i . "==" . $UURRLL . '<br>';
            insert_to_vm_panda($id_category, $UURRLL);
        }
    } else {
        insert_to_vm_panda($id_category, $url_category);
    };
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
        $query = "insert into categories (name, link) values ('{$category_name}','{$category_link}') ";
//записываем в табличку categories
        mysql_query($query);
// $results = pq_page($category_link);
    }
}

function insert_to_vm_panda($id_category, $UURRLL)
{
    global $conn;
    pq_page($UURRLL);
    $res_items = pq('ul.products-grid>li');
    foreach ($res_items as $item) {
        try {
            $ii = pq($item);
            $hhq = $ii->html();
            $productUrl = $ii->find('a.product-image')->attr('href');
            $sql1 = "select count(*) as count from _products where url='{$productUrl}' limit 1";
            $res = run_sql($sql1);
            // $res = mysql_query($sql1, $conn)or die("Invalid query: " . mysql_error());;
            $data = mysql_fetch_assoc($res);
            if ($data[count] == 0) {
                $imgSmallUrl = $ii->find("img")->attr('taikoo_lazy_src');
                $product_name = $ii->find('.product-name>a')->text();
                $product_sql = "INSERT _products (url, ids_category, name, img_small, status)
                            VALUES ('{$productUrl}', {$id_category}, '{$product_name}', '{$imgSmallUrl}', 1)";
                run_sql($product_sql);
            } else {
                $sql1 = "select * from _products where url='{$productUrl}' limit 1";
                $res = run_sql($sql1);
                $data2 = mysql_fetch_assoc($res);
                $category_old_value=(string)$data2[ids_category];
                $sql = " UPDATE _products SET ids_category='{$category_old_value},{$id_category}' WHERE url='{$productUrl}'";
                run_sql($sql);
            };
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    return $res;
}

?>
