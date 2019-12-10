<?php
//conf
include('../conf.php');
// example of how to modify HTML contents
$start = microtime(true); //начало измерения
$dir_name = "sanTeka"; // имя для папка сохранения
$mc = dirname(__FILE__) . "/$dir_name/"; //путь сохранения папки и файлов
echo(!file_exists($mc) ?
    (!mkdir($mc, 0777, true) ? 'Failed to create folders...' : 'Succesfully created nested directories...') : 'maybe_dir_make_before'); // проверка на создание папки - если существует вывести сообщение
$names = [
    0=>'Назначение',
];
$divContents = [];
$count = 1;
// get DOM from URL or file
//$fp = fopen("/search.txt", 'rb');
$homepage = file_get_contents('../query/search.csv',FILE_USE_INCLUDE_PATH);
$codeQuery = explode(",",$homepage);
var_dump($codeQuery);
echo '  
    <style>body {padding:10px;font-size:15px;} </style>
 ';
for($start = 0;$start<count($codeQuery);$start++):
    $saitArt = trim($codeQuery[$start]);
    $uName = "https://santehlux.by/search/?q= $saitArt";
    echo $uName;
    $html = file_get_html_curl($uName);
    $urlShortName = "https://santehlux.by";
    if (count($html->find('.tovar-grid .tovar_name a'))) :
        foreach ($html->find('.tovar-grid .tovar_name a') as $e) :
            $links = $urlShortName.$e->href;// получаем ссылку с урлов товара
            echo '<b>' . $count . '. <a href="' .$urlShortName.$e->href . '" target="_blank">' .$urlShortName.$e->href . '</a></b><br>';
            flush();
            $line = [];
            $line['link'] = $links; // находим все ссылки и передаем в массив
            $html2 = file_get_html_curl($urlShortName.$e->href);
            (isset($html2->find('h1', 0)->innertext) ? $line['name'] = trim($html2->find('h1', 0)->innertext) : $line['name'] = '');
            $art = $html2->find('span.artikul', 0);
            if (isset($art)):
                    $line['art'] = $html2->find('span.artikul', 0)->plaintext; // парсим время в html формате
                    $line['art'] = str_replace("Артикул: ", "", $line['art']);
                    $line['art'] = trim($line['art']);
                if ($line['art'] != trim($codeQuery[$start]) ):
                    $line['art'] = trim($codeQuery[$start]);
                endif;
            else :
                $line['art'] = "";
            endif;

            if (isset($html2->find('span[class=not-have]', 0)->innertext)):
                $line['qty'] = 0;
            else:
                $line['qty'] = 2;
            endif;
            $price = $html2->find('span[itemprop=price]', 0)->attr['content'];
            if (isset($price)):
                $line['price'] =  $price;
            else :
                $line['price'] = "";
            endif;
            echo '<br>';
            flush();
            echo implode(', ', $line) . '<br>';
            flush();
            $count++;
            usleep(600000); // 0.6 сек
            $divContents[] = $line;
            echo str_repeat('&nbsp;', 100) . '<br><hr><br>';
            flush();
            $html2->clear();
            unset($html2);
        endforeach;
    endif;
endfor;

$fp = fopen("$mc.$dir_name".'.csv', 'w'); // открываем на запись цсв
echo '<pre>';
print_r($divContents); // после цикла выводит массив для проверки
foreach ($divContents as $line):
    fputcsv($fp, $line);
endforeach;
fclose($fp); //записали и закрыли
$time = microtime(true) - $start;
printf('Скрипт выполнялся %.4F сек.', $time);
//$html->clear();
//unset($html);
