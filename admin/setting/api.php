<?php !defined('IN WEB') AND exit('Access Denied!');

$aCid = Base::factory()->getChannel();
$aPid = Base::factory()->getPack();

$result = array('channel'=>$aCid,'pack'=>$aPid);

var_dump($result);