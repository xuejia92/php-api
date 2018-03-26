<?php
include '../common.php';
$act = $_GET['act'];

switch ($act) {
    case 'feedback':
        $userInfo   = Member::factory()->getUserInfo($_GET['mid']);
        $mid        = $userInfo['mid'];
        $sid        = $userInfo['sid'];
        $cid        = $userInfo['cid'];
        $ctype      = $userInfo['ctype'];
        $pid        = $userInfo['pid'];
        $mnick      = $userInfo['mnick'];
        $content    = $_GET['content'];
        $phoneno    = "";
        $pic        = "";
        $gameid     = 100;
        $ip         = Helper::getip();
        
        $ret = 0;
        
        if(!$ctype || !$cid  ||!$mid ){
            echo $ret;
        }
        
        $ret = (int)Base::factory()->feedBack($gameid,$cid, $sid, $pid, $ctype, $content, $mid, $mnick, $phoneno, $pic,$ip);
        echo $ret;
        
        break;
    
    case 'check':
        $mid    = $_GET['mid'];
        $gameid = 100;
        
        $result['result']  = 0;
        $result['msg']     = array();
        if(!$mid || !$gameid){
            echo $result;
        }
        
        $history = Base::factory()->getMyfeed(100,1809946);
        
        $i = count($history)-1;
        
        foreach ($history as $k=>$v){
            $history[$i] = $v;
            $i--;
        }
        
        $j = 0;
        foreach ($history as $value){
            $his[$j] = $value;
            if ($j<4){
                $j++;
            }
        }
        
        if($history){
            $result['result'] = 1;
            $result['msg'] = $history;
        }
        
        echo json_encode($result);
        
        break;
}