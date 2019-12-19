<?php
//conf
include('../conf.php');

$start = microtime(true); //начало измерения всего скрипта
$urlShortName = "https://santehlux.by";
$divContents = [];
$count = 1;
echo '
    <style>body {padding:10px;font-size:15px;} </style>
 ';
$data = file('../query/TPgoods.csv');
for ($i = 0; $i < count($data); $i++):
    $info = explode(',', trim($data[$i]));
    foreach ($info as $item):
        $uName = "https://santehlux.by/search/?q=\"$item\"";
        $uName = htmlspecialchars_decode(clearStr($uName));
        $html = file_get_html_curl($uName);
        $grid = $html->find('div.tovar-grid', 0); // парсим  в html формате
        $artSite = [];
        echo $count.'<br>';
        foreach($grid->find('article.tovar') as $goods) :
            $goods = str_get_html($goods->innertext);
            foreach($goods->find('div.artikul') as $art) :
                $art = str_replace("Артикул: ", "", $art->plaintext);
                $art = trim($art);
                if ($art === $item ):
                   $artSite['art']  = $art;
                    foreach($goods->find('div.tovar_name a') as $lnk) :
                        $links = $urlShortName . $lnk->href;// получаем ссылку с урлов товара
                                    echo '<b>' . $count . '. <a href="' . $links . '" target="_blank">' .$links . '</a></b><br>';
                                    flush();
                        $artSite['link'] = $links; // находим все ссылки и передаем в массив
                                    $html2 = file_get_html_curl($links);
                                    (isset($html2->find('h1', 0)->innertext) ? $artSite['name'] = trim($html2->find('h1', 0)->innertext) : $artSite['name'] = '');
                                    if (isset($html2->find('span[class=not-have]', 0)->innertext)):
                                        $artSite['qty'] = 0;
                                    else:
                                        $artSite['qty'] = 2;
                                    endif;
                                    $price = $html2->find('span[itemprop=price]', 0)->attr['content'];
                                    if (isset($price)):
                                        $artSite['price'] =  $price;
                                    else :
                                        $artSite['price'] = "";
                                    endif;
                                    echo '<br>';
                                    flush();
                                    usleep(800000); // 0.6 сек
                                    $html2->clear();
                                    unset($html2);
                    endforeach;
                endif;
            endforeach;
        endforeach;
        flush();
        echo implode(', ', $artSite) . '<br>';
        flush();
        $count++;
        usleep(800000); // 0.6 сек
       $divContents[] = $artSite;
        echo str_repeat('&nbsp;', 100) . '<br><hr><br>';
        $html->clear();
        unset($html);
    endforeach;

endfor;
$dirName = 'radaway';
$mc = dirname(__FILE__) . "/$dirName/"; //путь сохранения папки и файлов
echo(!file_exists($mc) ?
    (!mkdir($mc, 0777, true) ? 'Failed to create folders...'.$mc : 'Succesfully created nested directories...'.$mc) : 'maybe_dir...'.$mc.'..._make_before'); // проверка на создание папки - если существует вывести сообщение
echo "<br>";
$fp = fopen( $mc.$dirName.'.csv', 'w');

echo '<pre>';
print_r($divContents);
foreach ($divContents as $line) {
    fputcsv($fp, $line);
}

fclose($fp);
$time = microtime(true) - $start;
printf('Скрипт выполнялся %.4F сек.', $time);


