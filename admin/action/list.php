<?php !defined('IN WEB') AND exit('Access Denied!');

$fun = Action_Config::$action[$_REQUEST['actionid']]['class'];
$actionname = Action_Config::$action[$_REQUEST['actionid']]['name'];

$detail = Action_item::factory()->$actionname($_REQUEST);

$item = $detail['item'];

$gameid = $_REQUEST['gameid'];
$ctype  = $_REQUEST['ctype'];

switch ($_REQUEST['act']) {
    case "detail":
        $result = call_user_func_array(array("$fun",'getItemDetail'), array($_REQUEST));
        
        include 'view/action.list.php';
        break;
    
    case "weixinActiveCodeSwitch":
        $weixinActiveCodeSwitch = Loader_Redis::common()->get('weixinActiveCodeSwitch', false, false);
        if ($weixinActiveCodeSwitch==1){
            $ret = Loader_Redis::common()->set('weixinActiveCodeSwitch', 0, false, false);
        }else {
            $ret = Loader_Redis::common()->set('weixinActiveCodeSwitch', 1, false, false);
        }
        
        if($ret){
            Main_Flag::ret_sucess("修改成功！");
        }else{
            Main_Flag::ret_fail("修改失败！");
        }
        break;
        
    default:
        $result = call_user_func_array(array("$fun",'getItemDetail'), array($_REQUEST));
        include 'view/action.list.php';
        break;
}