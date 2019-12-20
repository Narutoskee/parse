<?php
//conf
include('../conf.php');


//function checkFromFile($data)
//{
//    $start = microtime(true); //начало измерения всего скрипта
//    $count = 1;
//    echo '<ul>';
//    for ($i = 0; $i < count($data); $i++):
//        $info = explode(',', trim($data[$i]));
//        foreach ($info as $item):
//            $html = file_get_html_curl($item);
//            if (isset($html)):
//                echo '<li>' .$count . '  ' . $item;
//                echo(isset($html->find('h1', 0)->innertext) ? ' TEST OK' : 'ssl bad');
//                echo  '</li>';
//            endif;
//        endforeach;
//        $count++;
//    endfor;
//    echo '</ul>';
//    $time = microtime(true) - $start;
//    printf('Скрипт выполнялся %.4F сек.', $time);
//}
////checkFromFile($data);

//$f = fopen($fileName, "rt") or die("Ошибка!");
//for ($i=0; ($data=fgetcsv($f,1000,";"))!==false; $i++) {
//    $num = count($data);
//    echo "<h3>Строка номер $i (полей: $num):</h3>";
//    for ($c=0; $c<$num; $c++)
//        echo "[$c]<br>";
//}
//fclose($f);


$fileName = '../query/domen.csv';
$data = file($fileName);

$handle = fopen($fileName, "r");
echo "<table>";
echo "<tr>";
$count = 0;
while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
    foreach ($data as $key =>$item) {
if($key==1){
    if ($key == $count) {
        $arr[] = trim($item);
  //  echo "<th> $item  $count</th>";
    }
    else{
        echo "<td>$item</td>";
    }
 }
$count++;
    }
    echo "<tr>";
}
echo "</table>";
fclose($handle);

$fp = fopen('file.csv', 'w');

foreach ($arr as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp);



function cheker($myArr){
    $start = microtime(true); //начало измерения всего скрипта
    $count = 1;
    foreach ($myArr as $url):
        $html = file_get_html_curl($url);
        if (isset($html)):
            echo $count . ' ' . $url . " ";
            echo(isset($html->find('h1', 0)->innertext) ? 'TEST OK' : 'ssl bad');
            echo "<br>";
        endif;
        $count++;
    endforeach;
    $time = microtime(true) - $start;
    printf('Скрипт выполнялся %.4F сек.', $time);
}


?>
<style>
    table, td{
        border: 1px solid #000;
        border-collapse: collapse;
        padding: 5px;
    }
    table, th{
        border: 1px solid #000;
        border-collapse: collapse;
        padding: 5px;
    }
</style>

