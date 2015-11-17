<?php

require('phpQuery-onefile.php');
require('config.php');
require('controler.php');

$conn = mysql_connect($HOST,$USER,$PASS);
mysql_select_db($DB, $conn);
$tabVM='products';

$qUrl="SELECT * FROM {$tabVM} limit 2290,1000  ";

$res_Url=run_sql($qUrl);
$j=0;
while ($row= mysql_fetch_array($res_Url)){
    $URL=$row['url'];
    $id=$row['id'];
    pq_page($URL);

    // Замена путей к картинкам в описании, загрузка картинок в images/story
    $allStoryImges=pq('div.std>img');
    foreach ($allStoryImges as $StoryImge) {
        $imgSrc=pq($StoryImge)->attr('src');
        echo $j.'---'.$URL.'+++'.$imgSrc.'<br>';
        if ($imgSrc!='') {
                    get_images($imgSrc, 'D:/OpenServer/domains/panda2/images/story', basename($imgSrc));
        }
    };
    echo $j.'-----'.$id.'<br>';
    $j++;
}


mysql_close($conn);




?>
