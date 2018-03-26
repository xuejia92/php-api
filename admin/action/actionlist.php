<?php

$act    = $_REQUEST['act'];
$id     = $_REQUEST['id'];
$tapid  = $_REQUEST['tapid'];

$path = PRODUCTION_SERVER ? '' : 'test_';
define('PATH_KEY', $path);

switch ($act){

    case 'update':
        $return = Action_Actionlist::factory()->update($_REQUEST);
        if($return){
            Main_Flag::ret_sucess("修改成功！");
        }else{
            Main_Flag::ret_fail("修改失败！");
        }
        break;
    
    case 'del':
        $return = Action_Actionlist::factory()->del($_REQUEST);
        if($return){
            Main_Flag::ret_sucess("删除成功！");
        }else{
            Main_Flag::ret_fail("删除失败！");
        }
        break;
    
    case 'sort':
        $return = Action_Actionlist::factory()->sort($_REQUEST);
        if($return){
            Main_Flag::ret_sucess("排序成功！");
        }else{
            Main_Flag::ret_fail("排序失败！");
        }
        break;
    
    case 'getChannel':
        $result = Action_Actionlist::factory()->getChannel($_REQUEST);
        $aChannel = $result['cname'];
        $aCid = $result['cid'];
        include 'view/action.channel.php';
        break;
    
    case 'setList':
        $item = Action_Actionlist::factory()->setList($_REQUEST);
        include 'view/action.setList.php';
        break;

    default:
        $result = Loader_Redis::common()->get(PATH_KEY.'Activity_Config', false, true);
        $channel = Base::factory()->getChannel();
        include 'view/action.actionlist.php';
}
