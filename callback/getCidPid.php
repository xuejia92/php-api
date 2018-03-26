<?php
include '../common.php';
//echo  "ssssssssssss";





$act          = $_POST['act']?$_POST['act']:'';
if($act == 'cid'){
	$sql  = "SELECT * FROM uc_setting_cid ";
	$rows = Loader_Mysql::DBMaster()->getAll($sql);
}else if($act == 'pid'){
	$gameid =$_POST['gameid'] ;
	$cid = $_POST['cid'];
	$sql  = "SELECT * FROM uc_setting_pid WHERE gameid=".$gameid." AND cid=".$cid;
	$rows = Loader_Mysql::DBMaster()->getAll($sql);
}else{
	$sql  = "SELECT * FROM uc_setting_cid ";
	$rows = Loader_Mysql::DBMaster()->getAll($sql);
} 
	
$ret = json_encode($rows);

die($ret);
