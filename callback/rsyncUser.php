<?php

include '../common.php';

Logs::factory()->debug(array('requeet'=>$_REQUEST,'server'=>$_SERVER,'xml'=>$GLOBALS['HTTP_RAW_POST_DATA']),'rsyncuser');
echo '0';
