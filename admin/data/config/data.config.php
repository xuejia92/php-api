<?php !defined('IN WEB') AND exit('Access Denied!');

class Data_Config{

	public static $roomconfig = array(
										1 =>array('1'=>'初级场','2'=>'中级场','3'=>'高级场','4'=>'二人场','5'=>'私人场'),
										3 =>array('1'=>'初级场','2'=>'中级场','3'=>'高级场','4'=>'大师场'),
										4 =>array('1'=>'初级场','2'=>'中级场','3'=>'高级场','4'=>'私人场'),
										5 =>array(8=>'1倍炮',9=>'100倍炮',10=>'500倍炮'),
										6 =>array('1'=>'初级场','2'=>'中级场','3'=>'高级场','4'=>'二人场','5'=>'私人场'),
										7=>array(1=>'初级场',2=>'中级场',3=>'高级场',4=>'私人场'),
	
	);
	public static $matchroomconfig = array('5'=>'5000金币场','6'=>'30万金币场','7'=>'100万金币场','8'=>'1元话费场','9'=>'5元话费场','10'=>'200乐券场','11'=>'400乐券场','12'=>'800乐券场');
	
    public static $monthlyconfig = array('11'=>'DAU','25'=>'付费人数');
}