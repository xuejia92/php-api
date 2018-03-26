<?php

/**
 * 海南斗地主flash入口
 */
include 'common.php';

define('FLASH_VER_PATH',  WEB_PATH . 'sites/config/flashcfg');

$mod  = $_REQUEST['m'] ? $_REQUEST['m'] : 'sites';
$page = $_REQUEST['p'] ? $_REQUEST['p'] : 'index';

$file =  WEB_PATH. "{$mod}/{$page}.php";

if(!is_file($file))
{
    exit('file is not exists...');
}

require_once($file);

//Loader_Udp::behavior()->sendLog(1, $mod.'|'.$page, $_POST, "");//记录用户行为