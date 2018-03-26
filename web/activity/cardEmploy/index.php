<?php
include 'model/activity.cardEmploy.php';

$mid    = $data['mid'] = Helper::uint($_REQUEST['mid']);
$gameid = $data['gameid']  = Helper::uint($_REQUEST['gameid']);
$ctype  = $data['ctype']   = Helper::uint($_REQUEST['ctype']);
$data['sid']     = Helper::uint($_REQUEST['sid']);
$data['cid']     = Helper::uint($_REQUEST['cid']);
$data['pid']     = Helper::uint($_REQUEST['pid']);
$data['mtkey']   = $_GET['mtkey'];
$time   =   date("Y-m-d",NOW);

$act = $_GET['act'];

switch ($act){
    case employ:
        $data['cardno'] = trim($_REQUEST['cardno']);
        $result = Activity_CardEmploy::factory()->employ($data, $time);
        echo json_encode($result);
        
        break;
        
    default:
        $status = Activity_CardEmploy::factory()->getInfo($mid);
        
        Loader_Redis::common()->hSet("cardEmployUsernum|$gameid|$ctype|$time", $mid, 1, false, false, 90*24*3600);
        Loader_Redis::common()->incr("cardEmployUserci|$gameid|$ctype|$time", 1, 90*24*3600);
        
        include 'view/index.php';
        break;
}