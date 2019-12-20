<?php
//conf
include('../conf.php');
//$siteArr = [
//    'https://baranovichi.money-vdolg.xyz',
//    'https://bobruisk.money-vdolg.xyz',
//    'https://borisov.money-vdolg.xyz',
//    'https://brest.money-vdolg.xyz',
//    'https://gomel.money-vdolg.xyz',
//    'https://grodno.money-vdolg.xyz',
//    'https://jlobin.money-vdolg.xyz',
//    'https://jodino.money-vdolg.xyz',
//    'https://lida.money-vdolg.xyz',
//    'https://minsk.money-vdolg.xyz',
//    'https://mogilev.money-vdolg.xyz',
//    'https://molodechno.money-vdolg.xyz',
//    'https://mozir.money-vdolg.xyz',
//    'https://orsha.money-vdolg.xyz',
//    'https://pinsk.money-vdolg.xyz',
//    'https://polock.money-vdolg.xyz',
//    'https://rechica.money-vdolg.xyz',
//    'https://svetlogorsk.money-vdolg.xyz',
//    'https://vitebsk.money-vdolg.xyz',
//    'https://money-vdolg.xyz',
//];

$fileName = [
    '../query/site1.txt',
    '../query/site2.txt',
    '../query/site3.txt',
    '../query/site4.txt',
    '../query/site5.txt',
    '../query/site6.txt',
    '../query/site7.txt',
    '../query/site8.txt',
    '../query/site9.txt',
    '../query/site10.txt',
    '../query/site11.txt',
];

for ($x=0;$x<count($fileName);$x++){
    $data = file($fileName[$x]);
    checkFromFile($data);
}

function checkFromFile($data)
{
    $start = microtime(true); //начало измерения всего скрипта
    $count = 1;
    echo '<table border="1">';

    for ($i = 0; $i < count($data); $i++):
        $info = explode('	', trim($data[$i]));
        echo '<tr>';
        foreach ($info as $item):
            $html = file_get_html_curl($item);
            if (isset($html)):
                echo '<td>' . $count . '</td>';
                echo '<td>' . $item .'</td>';
                echo(isset($html->find('h1', 0)->innertext) ? ' <td style="color:white;background: green"">TEST OK</td>' : '<td style="color:white;background: red"> SSl BAD !!!</td>');
            endif;
        endforeach;
        $count++;
        echo '</tr>';
    endfor;

    echo '</table>';
    $time = microtime(true) - $start;
    printf('Скрипт выполнялся %.4F сек.', $time);
}




function SiteStatus($siteArr)
{
    $start = microtime(true); //начало измерения всего скрипта
    $count = 1;
    foreach ($siteArr as $url):
        $html = file_get_html_curl($url);
        if (isset($html)):
            echo $count . ' ' . $url . "  ";
            echo(isset($html->find('h1', 0)->innertext) ? 'TEST OK' : ' ssl bad');
            echo "<br>";
        endif;
        $count++;
    endforeach;
    $time = microtime(true) - $start;
    printf('Скрипт выполнялся %.4F сек.', $time);
}

//SiteStatus($siteArr);

// dirname(__FILE__);