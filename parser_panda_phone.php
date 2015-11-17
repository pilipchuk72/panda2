<?php

require('phpQuery-onefile.php');
require('config.php');
require('controler.php');

//global $HOST, $USER, $PASS, $DB;
$mysqli = new mysqli($HOST, $USER, $PASS, $DB);
if ($mysqli->connect_error) {
    die('Ошибка соединения (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$tabVM = '_products';


$qUrl = "SELECT * FROM {$tabVM}  WHERE status=1";

$res_Url = $mysqli->query($qUrl);
$j = 0;

while ($row = $res_Url->fetch_array()) {
    $ID_PRODUCT=$row['id'];
    $URL=$row['url'];
//    $ID_PRODUCT = 4032;
//    $URL = "http://www.pandawill.com/laaboo-w01-smartphone-mtk6582-quad-core-1gb-8gb-50-inch-ips-screen-80mp-camera-p101271.html";
    pq_page($URL);
    $PRICE = trim(pq('span.price:first')->text());
    $PRICE = str_replace('$', '', $PRICE);

    // Описание

    $tabsParametr = pq('ul#cjpro_ul>li:eq(1)')->text();

    if($tabsParametr=="Parameter"){
        $DESCR = pq('div.cjpro_wen:eq(1)');
    }else{
        $DESCR = pq('div.cjpro_wen:eq(0)');
    }
    ;

    $allStoryImges = $DESCR->find('img');
    $ii = 0;
    foreach ($allStoryImges as $storyImage) {
        $storyImageSrc = pq($storyImage)->attr('taikoo_lazy_src');
        pq($storyImage)->removeAttr('taikoo_lazy_src');
        pq($storyImage)->removeAttr('onload');
        pq($storyImage)->removeAttr('alt');
        pq($storyImage)->attr('src',"/images/story/".(string)$ID_PRODUCT . "_" . (string)$ii . ".jpg");
        get_images($storyImageSrc, 'D:/OpenServer/domains/panda2/images/story', (string)$ID_PRODUCT . "_" . (string)$ii . ".jpg");
        $ii++;
    };
    $allTd = $DESCR->find('td, tr, span, table, col');
    foreach ($allTd as $td) {
        pq($td)->removeAttr('style');
        pq($td)->removeAttr('height');
        pq($td)->removeAttr('width');
        pq($td)->removeAttr('class');
    };
    $DESCR = $DESCR->html();
    $DESCR = str_replace("<span>", "", $DESCR);
    $DESCR = str_replace("</span>", "", $DESCR);

    $DESCR = $mysqli->real_escape_string($DESCR);

    // Замена путей к картинкам в описании, загрузка картинок в images/story
    // поиск больших изображений товара 4 первые шт
    $allLargeImg = pq('.gallery-media-slider>li>a');
    $i = 0;
    $IMGS_LARGE = '';
    foreach ($allLargeImg as $LargeImg) {
        $imgSrc = pq($LargeImg)->attr('onclick');
        $imgSrc = str_replace("switchImages(", '', $imgSrc);
        $imgSrc = str_replace(");", '', $imgSrc);
        $imgSrc = str_replace(" return false;", '', $imgSrc);
        $imgSrc=explode(",",$imgSrc);
        $imgSrc=str_replace("'","",$imgSrc[0]);
        $imgLarge = 'l_' . $ID_PRODUCT . '_' . $i . '.jpg';
        get_images($imgSrc, 'D:/OpenServer/domains/panda2/images/large', $imgLarge);
        $IMGS_LARGE = $IMGS_LARGE . '^' . $imgLarge;
        $i++;
        if ($i > 4) break;
    };

    $qUpdateProduct = "UPDATE $tabVM  SET description='{$DESCR}',
                        status=2,
                        img_large='{$IMGS_LARGE}',
                        price={$PRICE}
                    WHERE id={$ID_PRODUCT}";
   // $res_Url = $mysqli->query($qUpdateProduct);
    run_sql($qUpdateProduct);
}


$mysqli->close();

?>
