<?php
//conf
include('../conf.php');
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
];

for ($x=0;$x<count($fileName);$x++){
    $data = file($fileName[$x]);
    if (isset($data)){
        checkFromFile($data);
    }

}

function checkFromFile($data)
{
    $start = microtime(true); //начало измерения всего скрипта
    $count = 1;
    $linkName = 'http://president.gov.by/ru/official_documents_ru/view/ukaz-325-ot-30-ijunja-2014-g-9177/';
    echo '<table>';
echo "<tr>
<th>ID</th>
<th>SITE</th>
<th>SSL CHECK RES</th>
<th>LINK</th>
<th>УНП</th>
</tr>";
    for ($i = 0; $i < count($data); $i++):
        $info = explode('	', trim($data[$i]));
        echo '<tr>';
        foreach ($info as $item):
            $html = file_get_html_curl($item);
            if (isset($html)):
                echo '<td>' . $count . '</td>';
                echo '<td>' . $item .'</td>';
                echo(isset($html->find('h1', 0)->innertext) ? ' <td style="color:white;background: green"">TEST OK</td>' : '<td style="color:white;background: red"> SSl BAD !!!</td>');
                $link = $html->find('a[href='.$linkName.']', 0);
                if (isset($link)){
                    echo '<td style="color:white;background: green"> LINK OK </td>';
                }
                else{
                   echo '<td style="color:white;background: red"> NO LINK !!!</td>'  ;
                }

                $string = $html;
                if(stristr($string, 'УНП') !== FALSE) {
                    echo '<td style="color:white;background: green"> UNP OK </td>';

                    }
                    else{
                        echo '<td style="color:white;background: red"> NO UNP !!!</td>'  ;
                }


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

?>
<style>
    table, td,th{
        border: 1px solid #000;
        border-collapse: collapse;
        padding: 5px;
    }
</style>
