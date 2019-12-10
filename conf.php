<?php
/**
 * Created by PhpStorm.
 * User: snabweb
 * Date: 015 15.11.18
 * Time: 13:33
 */
header('Content-type: text/html; charset="utf-8"');
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(0);
include('simple_html_dom.php');

function clearInt($data)
{
    return (int)$data;
}
function clearStr($data){
    return trim(strip_tags($data));
}

function makeMyDir($dir_name){
    $mc = dirname(__FILE__) . "/$dir_name/"; //путь сохранения папки и файлов

    if(!file_exists($mc)){
        if(!mkdir($mc, 0777, true)){
            return     $res = 'Failed to create folders...'. $mc;
        }
        else{
            return    $res ='Succesfully created nested directories...'.$mc;
        }
    }
    else{
   echo  $res = $mc.$dir_name;
    }
}

