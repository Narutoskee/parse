<?php
//conf
include('../conf.php');
$start = microtime(true); //начало измерения
$dir_name = "santeFerro"; // имя для папка сохранения
$mc = dirname(__FILE__) . "/$dir_name/"; //путь сохранения папки и файлов
echo(!file_exists($mc) ?
    (!mkdir($mc, 0777, true) ? 'Failed to create folders...' : 'Succesfully created nested directories...') : 'maybe_dir_make_before'); // проверка на создание папки - если существует вывести сообщение
echo "<br>";

$names = [
    0 => 'Назначение',
    1 => 'Серия',
    2 => 'Управление',
    3 => 'Монтаж',
    4 => 'Количество отверстий',
    6 => 'Присоединительный размер',
    7 => 'Цвет',
    8 => 'Поворотный излив',
    9 => 'Аэратор',
    10 => 'Врезной',
    13 => 'Лейка',
    14 => 'Донный клапан',
    15 => 'Термостат',
];

$divContents = [];
$count = 1;
echo '  
    <style>body {padding:10px;font:Arial 10px;} </style>
 ';
$url = 'https://santehlux.by/smesiteli/brand-ferro/';
$html = file_get_html_curl(trim($url));
$urlShortName = "https://santehlux.by";

$Pages = [];
$pagination = $html->find('div.page-pagination', 0);

foreach ($pagination->find('a') as $pageAll) :
    if ($pageAll->class == 'active cursor-default')
        $Pages[] = $url;
    if (!$pageAll->class)
        $Pages[] = $urlShortName . htmlspecialchars_decode($pageAll->href);
endforeach;
$urlS = [];
for ($page = 0; $page < count($Pages); $page++):
    echo 'Page: ' . $page . '<br>';
    $urlS = [$Pages[$page]]; //если надо несколько ссылок
    foreach ($urlS as $urlLink):
        echo "Name: $urlLink<br>";
        $html2 = file_get_html_curl(trim($urlLink));
        if (count($html2->find('.tovar-grid a.tovar_photo'))) :
            foreach ($html2->find('.tovar-grid a.tovar_photo') as $step) :
                $links = $urlShortName . $step->href;// получаем ссылку с урлов товара
                echo '<b>' . $count . '. <a href="' . $urlShortName . $step->href . '" target="_blank">' . $urlShortName . $step->href . '</a></b><br>';
                flush();
                $line = [];
                $line['link'] = $links; // находим все ссылки и передаем в массив
                $html3 = file_get_html_curl($urlShortName . $step->href);

                (isset($html3->find('h1', 0)->innertext) ? $line['name'] = trim($html3->find('h1', 0)->innertext) : $line['name'] = '');

                $art = $html3->find('span.artikul', 0);

                if (isset($art)):
                    $line['art'] = $html3->find('span.artikul', 0)->plaintext; // парсим время в html формате
                    $line['art'] = str_replace("Артикул: ", "", $line['art']);
                    $line['art'] = trim($line['art']);
                else :
                    $line['art'] = "";
                endif;
                foreach ($html3->find('table.tovar-open_harcteristics_table') as $table) {

                    foreach ($table->find('tr') as $tr) {

                        foreach ($names as $k => $name) {

                            $val = trim(preg_replace('@\s+@', ' ', $tr->plaintext));
                            if (strpos($val, $name) !== false)
                                $line[$name] = trim($val);
                            if (isset($line[$name])) {
                                $line[$name] = str_replace($name, "", $line[$name]);
                            } else {
                                $line[$name] = "";
                            }
                        }
                    }
                }

               echo '<br>';

                flush();
                echo implode(', ', $line) . '<br>';
                flush();
                $count++;
                usleep(600000); // 0.6 сек
                $divContents[] = $line;
                echo str_repeat('&nbsp;', 100) . '<br><hr><br>';
                flush();
            endforeach;
        endif;
    endforeach;
endfor;


$fp = fopen("$mc.$dir_name" . '.csv', 'w');
echo '<pre>';
print_r($divContents);
foreach ($divContents as $line) {
    fputcsv($fp, $line);
}

fclose($fp);

$html->clear();
unset($html);

