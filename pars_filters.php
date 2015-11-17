<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 24.08.2015
 * Time: 1:41
 */


require('phpQuery-onefile.php');
require('config.php');
require('controler.php');

$phones_url = "http://www.pandawill.com/mobile-phone-c1/android-os-phone-c551.html";
$tablets_url = "http://www.pandawill.com/notebook-tablet-pc-mid-c438.html";

$last_group_id=pars_filters($phones_url,1,1);
pars_filters($tablets_url,$last_group_id+1,12);

$query='SELECT * FROM _categories  WHERE url <> ""';
$res=mysql_query($query);
$last_group_id=1;
while ($row = mysql_fetch_array($res)) {
    $last_group_id=pars_filters($row['url'],$last_group_id,$row['id']);
    $last_group_id++;

}

function pars_filters($url, $id_filter, $category_id)
{
    pq_page($url);
    $filters = pq(".pw_item");
    foreach ($filters as $filter) {
        $item = pq($filter);
        $filter_name = $item->find("h3")->text();
        if (!($filter_name=="Price" or $filter_name=="With Play Store"  )){
            $filter_items=$item->find("li>a");
            foreach ($filter_items as $filter_item) {
                $item_name=pq($filter_item)->text();
                $item_url=pq($filter_item)->attr('href');
                $query = "insert into _filters ( `name`,category_id,url,`group`,id_group)
                        values ('{$item_name}',{$category_id},'{$item_url}','{$filter_name}',{$id_filter}) ";
                mysql_query($query);
            }
            $id_filter++;
        } ;

    };
    return $id_filter;
}