<?php !defined('IN WEB') AND exit('Access Denied!');

$channel_android = Setting_Cid::factory()->get(array('ctype'=>1));

$channel_ios     = Setting_Cid::factory()->get(array('ctype'=>2));

include 'view/data.php';