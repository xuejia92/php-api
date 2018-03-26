<?php 

$act = $_GET['act'];

switch ($act) {
	
	case 'getcard':
		$username = Helper::getCookie('sp_username');
		$tableid = $_GET['table'];
		$gameid  = $_GET['gameid'];
		
		if($gameid == 1){
			$info  = Loader_Redis::server()->lIndex($tableid, 0,false,false);
		}elseif($gameid == 6){
			$info  = Loader_Redis::rank(6)->lIndex($tableid, 0,false,false);
		}elseif($gameid == 4){
			$info  = Loader_Redis::rank(4)->lIndex($tableid, 0,false,false);
		}elseif($gameid == 7){
			$info  = Loader_Redis::rank(7)->lIndex($tableid, 0,false,false);
		}

		$array = explode(" ", $info);
		$array = array_slice ( $array ,  3 ); 
			
		$members = $mnicks = array();
		foreach ($array as $k=>$arr){
			$cards = explode("_", $arr);
			$temp = explode(":", $cards[0]);
			$mid  = $temp[1];
			$flag = 5;
			if($gameid == 4){
				$flag = 4;
			}
			if($gameid == 7){
				$flag = 4;
			}

			$_cards = array_slice($cards,$flag);
				
			foreach ($_cards as $c) {
				$members[$mid][] = $c;
			}
				
			if($mid > 1000){
				$userInfo = Member::factory()->getOneById($mid);
				$mnicks[$mid] = $userInfo['mnick'];
			}else{
				$mnicks[$mid] = '机器人';
			}
	
		}

		include 'view/shcard.php';
	break;
	case 'shview':
		//$username = Helper::getCookie('uc_username');
		$roomid = $_GET['roomid'];
		$gameid = $_GET['gameid'];
		if($gameid == 6){//德州
			$tables = array();
			switch ($roomid) {
				case '02':
					for($r=7;$r<13;$r++){						
						$key  = $r<10 ?  "*|0$r|*|*"  : "*|$r|*|*";
						$t = Loader_Redis::rank(6)->getKeys($key);
						$t && $tables = array_merge($tables,$t);
					}
				break;
				case '03':
					for($r=13;$r<22;$r++){
						$key    = "*|$r|0*|*|*|*|*";
						$t =  Loader_Redis::rank(6)->getKeys($key);
						$t && $tables = array_merge($tables,$t);
					}
				break;	
				case '04':
					for($r=22;$r<25;$r++){
						$key    = "*|$r|0*|*|*|*|*";
						$t =  Loader_Redis::rank(6)->getKeys($key);
						$t && $tables = array_merge($tables,$t);
					}
				break;		
			}
			
			$info = array();
			foreach($tables as $k=>$table){
				$_info  = Loader_Redis::rank(6)->lIndex($table, 0,false,false);
				$array  = explode(" ", $_info);
				$array  = array_slice ( $array ,  3 ); 

				foreach ($array as $i=>$arr){
					$cards   = explode("_", $arr);
					$temp    = explode(":", $cards[0]);
					$mids[$i]  = $temp[1];
				}

				$str = "";
				foreach ($mids as $mid) {
					if($mid > 1000){
						$userInfo = Member::factory()->getOneById($mid);
						$mnick    = $userInfo['mnick'];
					}else{
						$mnick = "机器人";
					}
					$str .= $mid."(<a style='color:red'> $mnick </a>)&nbsp;&nbsp;";
				}
				
				$info[$k] = $str;
			}
		}elseif($gameid == 1){//梭哈
			$key = "*|$roomid|0*|*|*|*|*";
			$tables = Loader_Redis::server()->getKeys($key);
			
			$info = array();
			foreach($tables as $k=>$table){
				$arr_temp = explode("|", $table);
				
				$mids     = array();
				$_members = array_slice ( $arr_temp ,  3 ); 
				if($_members){
					foreach ($_members as $m) {
						$m && $mids[] = $m;
					}
				}
				$str = "";
				foreach ($mids as $mid) {
					if($mid > 1000){
						$userInfo = Member::factory()->getOneById($mid);
						$mnick    = $userInfo['mnick'];
					}else{
						$mnick = "机器人";
					}
	
					$str .= $mid."(<a style='color:red'> $mnick </a>)&nbsp;&nbsp;";
				}
				$info[$k] = $str;
			}
		}elseif($gameid == 4){//斗牛
			$key = "*|$roomid|0*|*|*|*|*";
			$tables = Loader_Redis::rank($gameid)->getKeys($key);
			
			$info = array();
			foreach($tables as $k=>$table){
				$arr_temp = explode("|", $table);
				
				$mids     = array();
				$_members = array_slice ( $arr_temp ,  3 ); 
				if($_members){
					foreach ($_members as $m) {
						$m && $mids[] = $m;
					}
				}
				$str = "";
				foreach ($mids as $mid) {
					if($mid > 1000){
						$userInfo = Member::factory()->getOneById($mid);
						$mnick    = $userInfo['mnick'];
					}else{
						$mnick = "机器人";
					}
	
					$str .= $mid."(<a style='color:red'> $mnick </a>)&nbsp;&nbsp;";
				}
				$info[$k] = $str;
			}
		
		}elseif($gameid == 7){//炸金花
			$tables     = array();
			$key        =  "*|$roomid|*|*";
			$tables     = Loader_Redis::rank(7)->getKeys($key);
			//var_dump($tables);
	
			$info = array();
			foreach($tables as $k=>$table){
				$_info  = Loader_Redis::rank(7)->lIndex($table, 0,false,false);
				$array  = explode(" ", $_info);
				$array  = array_slice ( $array ,  3 ); 

				if(!$array){
					continue;
				}
				$mids = array();
				foreach ($array as $i=>$arr){
					$cards   = explode("_", $arr);
					$temp    = explode(":", $cards[0]);
					$mids[$i] = $temp[1];
				}

				$str = "";
				foreach ($mids as $mid) {
					if($mid > 1000){
						$userInfo = Member::factory()->getUserInfo($mid);
						$mnick    = $userInfo['mnick'];
					}else{
						$mnick = "机器人";
					}
					$str .= $mid."(<a style='color:red'> $mnick </a>)&nbsp;&nbsp;";
				}
				
				$info[$k] = $str;
			}
		}	
		
		include 'view/shview.php';
		break;
		
		case 'bairen':
			$type                = $_GET['type'];
			$data                = Main_Sh::factory()->getAll($type);
			$userInfo            = Member::factory()->getOneById($data['banker']);
			$data['mid']         = $data['banker'];
			$data['mnick']       = $data['mid'] > 1000 ? $userInfo['mnick'] : '机器人' ;
			$data['money']       = $userInfo['money'] ? $userInfo['money'] : 0;
			$data['freezemoney'] = $userInfo['freezemoney'] ? $userInfo['freezemoney'] : 0;
			if($type == 1){
				$data['count'] = 3;
				$data['b1'] = '龙';
				$data['b2'] = '和';
				$data['b3'] = '虎';
			}else{
				$data['count'] = 4;
				$data['b1'] = '天';
				$data['b2'] = '地';
				$data['b3'] = '玄';
				$data['b4'] = '黄';
			}
			include 'view/bairenview.php';			
		break;
		
		case 'update':
			$result = Main_Sh::factory()->update($_GET);
			if($result){
				Main_Flag::ret_sucess("操作成功！");
			}else{
				Main_Flag::ret_fail("操作失败！");
			}
		break;	
		
		case 'bairenajax':
			$type = $_GET['type'];
			$data = Main_Sh::factory()->getAll($type);
			$info = Main_Sh::factory()->getMembers($type);
			$data = array_merge($data,$info);
			$userInfo            = Member::factory()->getOneById($data['banker']);
			$data['mid']         = $data['banker'];
			$data['mnick']       = $data['mid'] > 1000 ? $userInfo['mnick'] : '机器人' ;
			$data['money']       = $userInfo['money'] ? $userInfo['money'] : 0;
			$data['freezemoney'] = $userInfo['freezemoney'] ? $userInfo['freezemoney'] : 0;
			if($type == 1){
				$data['count'] = 3;
				$data['b1'] = '龙';
				$data['b2'] = '和';
				$data['b3'] = '虎';
			}else{
				$data['count'] = 4;
				$data['b1'] = '天';
				$data['b2'] = '地';
				$data['b3'] = '玄';
				$data['b4'] = '黄';
			}
			echo json_encode($data);
		break;
		
		case 'checkLogin':
			include '../../common.php';
			$flag = Main_Model::factory()->checkLogin($_POST['username'], $_POST['password'],"sp_username");
			if(!$flag){
				Main_Model::factory()->showMsg("?m=main&p=login","用户名或密码错误");
			}
			header("Location: ../../6549estujif.php");
		break;
		case 'login':
			include '../../common.php';
			include 'view/login.php';
		break;
	
	default:
		include 'view/shmain.php';
	break;
}

