<?php

require('phpQuery-onefile.php');
require('config.php');
require('controler.php');

$in_path="D:/OpenServer/domains/jshop/images/small/";
$out_path="D:/OpenServer/domains/jshop/components/com_jshopping/files/img_categories/";
$DB="joomshop";
$mysqli = new mysqli($HOST, $USER, $PASS, $DB);
$mysqli->query("INSERT INTO jsh_jshopping_categories (

jsh_jshopping_categories.category_id,
jsh_jshopping_categories.category_image,
jsh_jshopping_categories.category_parent_id,
jsh_jshopping_categories.category_publish,
jsh_jshopping_categories.category_ordertype,
jsh_jshopping_categories.ordering,
jsh_jshopping_categories.products_page,
jsh_jshopping_categories.products_row,
jsh_jshopping_categories.access,
jsh_jshopping_categories.`name_en-GB`,
jsh_jshopping_categories.`alias_en-GB`,
jsh_jshopping_categories.`name_ru-RU`,
jsh_jshopping_categories.`alias_ru-RU`
)
SELECT
id,
category_img,
_categories.parent_id,
1,
1,
1,
24,
4,
1,
_categories.name_eng,
REPLACE(REPLACE(_categories.name_eng,' ','_'),'/','_'),
_categories.name,
CONCAT(REPLACE(REPLACE(_categories.name_eng,' ','_'),'/','_'),'_ru')
FROM _categories");

$res_Url = $mysqli->query("SELECT * FROM _categories");

while ($row = $res_Url->fetch_array()) {
copy($in_path.$row[category_img],$out_path.$row[category_img]);
}