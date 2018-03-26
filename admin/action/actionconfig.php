<?php

$url = PRODUCTION_SERVER? "http://user.dianler.com/admin.php" : "http://utest.dianler.com/ucenter/admin.php";
$id = $_REQUEST['id'];
$gameid = $_REQUEST['gameid'] ? $_REQUEST['gameid'] : 1;
$tapid  = $_REQUEST['tapid'] ? $_REQUEST['tapid'] : 1;
$act = $_REQUEST['act'];

$anotherCid = Loader_Redis::common()->hGetAll('another_game_cid');
foreach ($anotherCid as $key=>$value){
    $aCid[$key] = unserialize($value);
}
$cid = Base::factory()->getChannel();

switch ($act){
    case 'setCid':
        include 'view/action.setCid.php';
        break;
        
    case 'unsetCid':
        include 'view/action.unsetCid.php';
        break;
    
    case 'addcid':
        $return = Action_ConfigurationWuxingpingjia::addcid($_REQUEST);
        
        if($return){
            Main_Flag::ret_sucess("新增成功！");
        }else{
            Main_Flag::ret_sucess("新增成功！");
        }
        
        break;
    
    case 'getcid':
        $client = Setting_Cid::factory()->get(array('gameid'=>$gameid,'ctype'=>2));
        foreach ($client as $k=>$row){
            $getcid[$k]['cid'] = $row['id'];
            $getcid[$k]['cname'] = $row['cname'];
        }
        echo json_encode($getcid);
        break;
        
    case 'deleteCid':
        $return = Action_ConfigurationWuxingpingjia::deletcid($_REQUEST);
        
        if($return){
            Main_Flag::ret_sucess("删除成功！");
        }else{
            Main_Flag::ret_sucess("删除成功！");
        }
        
        break;
        
    case 'getChannel':
        $result = call_user_func_array(array(Action_Configuration::$action[$tapid]['class'],'getChannel'), array($_REQUEST));
        $aChannel = $result['cname'];
        $aCid = $result['cid'];
        include 'view/action.channel.php';
        break;
    
    case 'changeOption':
        $gid = $_REQUEST['gid'];
        $cid = $_REQUEST['cid'];
        $list = Loader_Redis::common()->hGet('activity_wuxingpingjia_config', $gid, false, true);
        echo $list['url'][$cid];
        
        break;
    
    case 'set':
        $return = call_user_func_array(array(Action_Configuration::$action[$tapid]['class'],'modify'), array($_REQUEST));
        
        if($return){
            Main_Flag::ret_sucess("修改成功！");
        }else{
            Main_Flag::ret_sucess("修改成功！");
        }
        
        break;
    
    case 'modify':
        if (in_array($tapid, array(3,4,5,6,7,8))){
            $list = Loader_Redis::common()->get(Action_Configuration::$action[$tapid]['kname'] , false, true);
            $action = $list;
        }else{
            $list = Loader_Redis::common()->hGetAll(Action_Configuration::$action[$tapid]['kname']);
            $action = array();
            foreach ($list as $key=>$value){
                $action[$key] = unserialize($value);    
            }
        }
        
        include "view/action.$id.php";
        break;
        
    default:
        $result = Action_Configuration::$action;
        
        include 'view/action.configuration.php';
        break;
}