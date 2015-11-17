<?php

require('/classes/phpQuery-onefile.php');
require('config.php');
require('controler.php');

//header('Content-Type: text/html; charset=utf-8');
$TabName_Category="_categories";
$TabName_Products="_products";
$TabName_Filters="_filters";


// очищаем таблицы joomshoping
clean_tables();

$conn = mysql_connect($HOST, $USER, $PASS);
mysql_select_db($DB, $conn);

$query="INSERT into jsh_jshopping_categories (
jsh_jshopping_categories.category_id,
jsh_jshopping_categories.category_image,
jsh_jshopping_categories.category_parent_id,
jsh_jshopping_categories.category_publish,
jsh_jshopping_categories.category_ordertype,

jsh_jshopping_categories.ordering,
jsh_jshopping_categories.category_add_date,
jsh_jshopping_categories.products_page,
jsh_jshopping_categories.products_row,
jsh_jshopping_categories.access,
jsh_jshopping_categories.`name_ru-RU`)

SELECT
{$TabName_Category}.id,
'' as q,
0 as q1,
1 as q2,
'1' as q3,

'' as q5,
'2013-07-12 01:44:59' as q6,
'12' as q7,
'3' as q8,
'1' as q9,
{$TabName_Category}.`name`
FROM {$TabName_Category}";

mysql_query($query);

$query = "SELECT id, ids_category  FROM {$TabName_Products}";

$res_Url = mysql_query($query);
$j = 0;
while ($row = mysql_fetch_array($res_Url)) {

    $id = $row['id'];
    $ids_category = $row['ids_category'];
    for ($i = 0; $i < 5; $i++) {

    }
    // $categories=explode(',', $row['ids_category']);
    $qq = "call explode({$id},'{$ids_category}');";
    mysql_query($qq);
}
$query = "INSERT INTO jsh_jshopping_products (
jsh_jshopping_products.product_id,
jsh_jshopping_products.parent_id,
jsh_jshopping_products.product_ean,
jsh_jshopping_products.product_quantity,
jsh_jshopping_products.unlimited,
jsh_jshopping_products.product_availability,
jsh_jshopping_products.product_date_added,
jsh_jshopping_products.date_modify,
jsh_jshopping_products.product_publish,
jsh_jshopping_products.product_tax_id,
jsh_jshopping_products.currency_id,
jsh_jshopping_products.product_template,
jsh_jshopping_products.product_url,
jsh_jshopping_products.product_old_price,
jsh_jshopping_products.product_buy_price,
jsh_jshopping_products.product_price,
jsh_jshopping_products.min_price,
jsh_jshopping_products.different_prices,
jsh_jshopping_products.product_weight,
jsh_jshopping_products.image,
jsh_jshopping_products.product_manufacturer_id,
jsh_jshopping_products.product_is_add_price,
jsh_jshopping_products.add_price_unit_id,
jsh_jshopping_products.average_rating,
jsh_jshopping_products.reviews_count,
jsh_jshopping_products.delivery_times_id,
jsh_jshopping_products.hits,
jsh_jshopping_products.weight_volume_units,
jsh_jshopping_products.basic_price_unit_id,
jsh_jshopping_products.label_id,
jsh_jshopping_products.vendor_id,
jsh_jshopping_products.access,
jsh_jshopping_products.`name_en-GB`,
jsh_jshopping_products.`alias_en-GB`,
jsh_jshopping_products.`short_description_en-GB`,
jsh_jshopping_products.`description_en-GB`,
jsh_jshopping_products.`meta_title_en-GB`,
jsh_jshopping_products.`meta_description_en-GB`,
jsh_jshopping_products.`meta_keyword_en-GB`,
jsh_jshopping_products.`name_ru-RU`,
jsh_jshopping_products.`alias_ru-RU`,
jsh_jshopping_products.`short_description_ru-RU`,
jsh_jshopping_products.`description_ru-RU`,
jsh_jshopping_products.`meta_title_ru-RU`,
jsh_jshopping_products.`meta_description_ru-RU`,
jsh_jshopping_products.`meta_keyword_ru-RU`)
SELECT
{$TabName_Products}.id,
0,
'',
1, -- количество продуктов
0,
'',
'2013-07-28 22:50:41', -- время создания
'2013-07-28 22:50:41', -- время редактирования
1,-- опубликован 
1, -- ?
1, -- id валюты
'default', -- шаблон 
'', 
0,
0,
0, -- цена продукта
0, -- минимальная цена
0,
0,
CONCAT('l_',{$TabName_Products}.id,'_0.jpg'), -- изображение
0, -- id производителя
0,
3,
0,
0,
0,
1,
0,
0,
0,
0,
1,
'', -- name_en
'', -- alias_en
'', -- small_desc EN
'', -- descr en
'', -- 
'',
'',
{$TabName_Products}.`name`, -- название товара
'', -- alias ru
'', -- small descr ru
{$TabName_Products}.description,
'',
'',
''
FROM {$TabName_Products}";
mysql_query($query);

$dir = "D:/OpenServer/domains/panda2/images/large";   //задаём имя директории
if (is_dir($dir)) {   //проверяем наличие директории
    echo $dir . ' - директория существует!!!;<br>';
    $files = scandir($dir);    //сканируем (получаем массив файлов)
    array_shift($files); // удаляем из массива '.'
    array_shift($files); // удаляем из массива '..'
    //array_shift($files); // удаляем из массива 'large'

    for ($i = 0; $i < sizeof($files); $i++) {

        $file_name = basename($files[$i], ".jpg");
        if (!strpos($file_name, "l_")) {
            $id_img = explode('_', $file_name);
            try {
                $qq1 = "insert into jsh_jshopping_products_images (product_id,image_name)
                values ({$id_img[1]},'{$file_name}.jpg')";
                mysql_query($qq1);
            } catch (Exception $exc) {
                echo 'Файл: ' . $file_name . ' ошибка записи в базу ' . $exc->getTraceAsString() . '<br>' .
                    $qq . '<br>';
            }


        }
    }  //выводим все файлы
};


mysql_close($conn);

function clean_tables()
{
    run_sql("TRUNCATE TABLE jsh_jshopping_categories;");
    run_sql("TRUNCATE TABLE	jsh_jshopping_products;");
    run_sql("TRUNCATE TABLE	jsh_jshopping_products_to_categories;");
    run_sql("TRUNCATE TABLE	jsh_jshopping_products_images;");
}

?>
