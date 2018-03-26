<?php !defined('IN WEB') AND exit('Access Denied!');

$idc   = Config_Inc::$idc;

$title = $idc == 1 ? '点乐用户数据中心' : '點樂用戶資料中心';

include 'view/main.php';