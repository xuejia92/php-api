<?php
include '../common.php';

Loader_Redis::common()->incr("stat_hainan",1);