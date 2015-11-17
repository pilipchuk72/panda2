<?php

//Выполнение SQL запроса
function run_sql($sql)
{

    global $HOST, $USER, $PASS, $DB;
    $mysqli = new mysqli($HOST, $USER, $PASS, $DB);
    if ($mysqli->connect_error) {
        die('Ошибка соединения (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }
//    $mysqli->close();
    return $result = $mysqli->query($sql, MYSQLI_USE_RESULT);
    $mysqli->close();

//    $dbh = new PDO(
//        "mysql:host=$db_host;dbname=$db_name;charset=$db_charset",
//        $db_user,$db_pass, array(
//        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
//    ));
//
//    $data = $dbh->query($sql)->fetchAll();
//    return $data;



//    $conn = mysql_connect($HOST, $USER, $PASS);
//    mysql_select_db($DB, $conn);
//    $res = mysql_query($sql, $conn);
//    mysql_close($conn);
//    return $res;

}

// добавляем категорию
function insert_category($categoryName, $parent_id, $child_id, $url)
{
    global $HOST, $USER, $PASS, $DB;
    $conn = mysql_connect($HOST, $USER, $PASS);
    mysql_select_db($DB, $conn);
    $sql = "INSERT INTO categories (
                            categories.name_category,
                            categories.parent_ID,
                            categories.child_ID,
                            categories.url)
                    VALUES ('$categoryName',
                             $parent_id,
                             $child_id,
                             '$url')";
    mysql_query($sql, $conn);
    mysql_close($conn);
}

// строим  DOM
function pq_page($url)
{
    $results_page = get_xml_page($url);
    $page_pq = phpQuery::newDocument($results_page);
    return $page_pq;
}

// загрузка страницы и конвертирование в UTF8
function get_xml_page($url)
{
    //  $page = file_get_contents($url);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $page = curl_exec($ch);
    //$page = iconv("WINDOWS-1251", "UTF-8", $page);
    curl_close($ch);
    return $page;
}

// загрузка картинки
function get_images($url, $image_dir, $image_name)
{
//    echo $image_name . '<br/>';
    $savefile = $image_dir . "/" . $image_name;


    try {
        $ch = curl_init($url);
        $fp = fopen($savefile, "wb");
        if (!$fp)
            write_log('Не удалось открыть файл для сохранения изображения ' . $url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        $filesize = filesize($savefile);
        if ($filesize > 0) {
            return TRUE;
        } else
            return FALSE;
    } catch (Exception $exc) {
//        echo $exc->getTraceAsString();
        return FALSE;
    }
}

?>
