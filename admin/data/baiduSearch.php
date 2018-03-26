<?php

$gameid = $_REQUEST['gameid'];
if ($gameid=='1'){
    $item = 190;
}else if ($gameid=='3'){
    $item = 191;
}else if ($gameid=='4'){
    $item = 192;
}else if ($gameid=='5'){
    $item = 193;
}else if ($gameid=='6'){
    $item = 194;
}else if ($gameid=='7'){
    $item = 195;
}else {
    $item = 100;
}

$act = $_REQUEST['act'];
switch ($act){
    case 'dumpexcel':
        $record = Data_Search::factory()->getClickIPNum($_REQUEST);
        
        $page_code = "utf-8";
        header("content-type: text/html; charset=$page_code"); //页面编码
        header("content-type:application/vnd.ms-excel");
        header("content-disposition:attachment;filename=".mb_convert_encoding("IP地址统计","gbk",$page_code).".xls");
        header("pragma:no-cache");
        header("expires:0");
        echo iconv("utf-8", "gb2312", "IP")."\t";
        echo iconv("utf-8", "gb2312", "次数")."\t";
        echo "\n"; //换行
        
        foreach ($record[$item] as $i=>$t) {
            echo iconv("utf-8", "gb2312",$i)."\t";
            echo iconv("utf-8", "gb2312",$t)."\t";
            echo "\n"; //换行
        }
        break;
    
    case 'dumpexcelView':
        
        include 'view/dumpexcel.php';
        break;
        
    case 'IP':
        $record = Data_Search::factory()->getClickIPNum($_REQUEST);
        
        include 'view/search.iplist.php';
        break;
        
    
    case 'detail':
        $record = Data_Search::factory()->getClickNum($_REQUEST);
        
        include 'view/search.detail.php';
        break;
        
    default:
        $record = Data_Search::factory()->getClickNum($_REQUEST);
        
        include 'view/search.detail.php';
        break;
}