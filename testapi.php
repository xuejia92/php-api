<?php

/**
 * 测试API文件
 * 
 * @author GaifyYang
 */


include 'common.php';

die();
// $gameInfo        = Member::factory()->getGameInfo('1809946');
// $userInfoKey = Config_Keys::getUserInfo('1809946');
// $server_mtkey = Loader_Redis::minfo('1809946')->hGet($userInfoKey, 'mtkey');
// var_dump($server_mtkey);
// echo '<br/>';

// var_dump(Loader_Redis::ote(1809946)->hGetAll("OTE|1809946"));
$le = array(1=>'初级场',2=>'中级场',3=>'高级场',4=>'大师场');
$rows = array();
$stime = 1439740800;
$etime = 1440345600;
$ttime = $stime+86400;
while ($stime<$etime){
    foreach ($le as $level=>$lname){
    	//echo date("Y-m-d H:i:s",$stime);
    	//echo "<br/>";
    	//echo date("Y-m-d H:i:s",$ttime);
        $sql = "SELECT SUM(money) AS money FROM logchip.log_member3 WHERE gameid=3 AND level=$level AND stime>=$stime AND stime<$ttime";
        $row = Loader_Mysql::DBLogchip()->getOne($sql);
        $rows[$level][date("Y-m-d",$stime)] = (int)$row['money'];
    }
    $ttime += 86400;
    $stime += 86400;
}

var_dump($rows);

die();
$filename = "斗地主金币流水";

$page_code = "utf-8";
header("content-type: text/html; charset=$page_code"); //页面编码
header("content-type:application/vnd.ms-excel");
header("content-disposition:attachment;filename=".mb_convert_encoding($filename,"gbk",$page_code).".xls");
header("pragma:no-cache");
header("expires:0");
//输出内容如下：
echo iconv("utf-8", "gb2312","场次")."\t";
echo iconv("utf-8", "gb2312","2015-08-17")."\t";
echo iconv("utf-8", "gb2312","2015-08-18")."\t";
echo iconv("utf-8", "gb2312","2015-08-19")."\t";
echo iconv("utf-8", "gb2312","2015-08-20")."\t";
echo iconv("utf-8", "gb2312","2015-08-21")."\t";
echo iconv("utf-8", "gb2312","2015-08-22")."\t";
echo iconv("utf-8", "gb2312","2015-08-23")."\t";
echo "\n";

foreach ($le as $level=>$lname){
    echo iconv("utf-8", "gb2312",$lname)."\t";
    echo ($rows[$level]['2015-08-17']*-1)."\t";
    echo ($rows[$level]['2015-08-18']*-1)."\t";
    echo ($rows[$level]['2015-08-19']*-1)."\t";
    echo ($rows[$level]['2015-08-20']*-1)."\t";
    echo ($rows[$level]['2015-08-21']*-1)."\t";
    echo ($rows[$level]['2015-08-22']*-1)."\t";
    echo ($rows[$level]['2015-08-23']*-1)."\t";
    echo "\n";
}

die();
$sql = "SELECT *, count( mid ) total FROM ucenter.uc_sitemid2mid WHERE deviceno !='' AND time > '1439135000' AND time<=1439222399 GROUP BY deviceno ORDER BY total DESC LIMIT 3";

/*
$sql = "INSERT INTO `uc_admin_log` (`id`, `username`, `model`, `page`, `act`, `req`, `ctime`, `ip`) VALUES
('', 'yangkaifeng', 'setting', 'privilege', 'setView', 'm=setting&p=privilege&act=setView&id=4&navTabId=privilege&_=1401963353859', 1401963357, '183.14.195.141');";
*/
//$row = Loader_Mysql::DBSlave()->fetchRow($sql);
//Loader_Mysql::DBSlave()->query($sql);
//$row = Loader_Mysql::DBSlave()->getOne($sql);

$str = "sdfsdf12";

//$row = Loader_Mysql::DBSlave()->escape($str);

//Loader_Mysql::DBMaster()->query($sql);
//$row = Loader_Mysql::DBMaster()->insertID();

$row = Loader_Mysql::DBSlave()->getOne($sql);

var_dump($row);
die();

/*

header("Content-type:text/html;charset=utf-8");


$sql = "show tables";

$row = Loader_Mysql::DBMaster()->getAll($sql);

//var_dump($row);

Loader_Redis::common()->set('test', 88888,false,false);

$var  = Loader_Redis::common()->get('test',false,false);

var_dump($var);

die();


for($i=144;$i<150;$i++){
	
	
	$key = "UST|2014-12-01|$i";
	
	$info = Loader_Redis::common()->hGetAll($key);
	$count = 0;
	foreach ($info as $k=>$v) {
		
		$g = explode("-", $k);
		$gid = $g[0];
		foreach (Config_Game::$game as $gameid=>$name) {
			if($gid == $gameid){
				$count = $count + $v;
				$rtn[$i] = $count;
			}
		}
	}
}


$array = array(144=>'狗','145'=>'鸟','146'=>'狐狸','147'=>'长颈鹿','148'=>'熊猫','149'=>'羊');

foreach ($rtn as $itemid=>$v) {
	$row[$array[$itemid]] = $v;
}
echo '购买情况 : ';
var_dump($row);
echo '<br/>';


$renshu = Loader_Redis::common()->getKeys("ac_lebao|*");
echo '查看活动人数 : ';
var_dump(count($renshu));
echo '<br/>';

$time = NOW;
$keyday = date('Hi',$time);
if ($keyday<2000){
    $time = date("ymd",strtotime("-1 days"));
}else {
    $time = date("ymd",NOW);
}

$keys = Loader_Redis::common()->getKeys("laobal_".$time."*");
$mmt['dog']   = $mmt['bird']   = $mmt['fox']   = $mmt['giraffe']   = $mmt['panda']   = $mmt['sheep']  = 0;
$cc['dog'] = $cc['bird'] = $cc['fox'] = $cc['giraffe'] = $cc['panda'] = $cc['sheep'] = 0;
foreach ($keys as $key) {
    $which = Loader_Redis::common()->get($key);
    $cc['dog']       = $which['dog']     + $cc['dog'];
    $cc['bird']      = $which['bird']    + $cc['bird'];
    $cc['fox']       = $which['fox']     + $cc['fox'];
    $cc['giraffe']   = $which['giraffe'] + $cc['giraffe'];
    $cc['panda']     = $which['panda']   + $cc['panda'];
    $cc['sheep']     = $which['sheep']   + $cc['sheep'];

    $which['dog']     && $mmt['dog'] ++;
    $which['bird']    && $mmt['bird'] ++;
    $which['fox']     && $mmt['fox'] ++;
    $which['giraffe'] && $mmt['giraffe'] ++;
    $which['panda']   && $mmt['panda'] ++;
    $which['sheep']   && $mmt['sheep'] ++;
}
$data = $amount = $ren = array();
$j = 0;
foreach ($cc as $a=>$b) {
    $data[$j]['类型'] = $a;
    $data[$j]['数量'] = $b;
    $data[$j]['人数']   = $mmt[$a];
    $amount[] = $b;
    $ren[] = $mmt[$a];
    $j ++;
}

array_multisort ($amount,SORT_ASC,$ren, SORT_DESC, $data );//如果次数相同，则选择人数多的

for ($n=0;$n<6;$n++){
    var_dump($data[$n]);
    echo '<br/>';
}
$lottery = $data[0]['类型'];//开奖结果

$aWhere[] = $data['roomid'] ? "roomid=".Helper::uint($data['roomid']) : "roomid=0";
var_dump($aWhere);

die();
/*$gg = Loader_Redis::common()->hGetAll("lebao-top15-141218");
$o = 0;
foreach ($gg as $kkkk=>$vvvv){
    $be[$o]['id'] = $kkkk;
    $be[$o]['number'] = $vvvv;
    $id[] = $kkkk;
    $xx[] = $vvvv;
    $o++;
}
array_multisort($xx,SORT_DESC,$be);
echo '<pre>';
var_dump($be);
var_dump(count($be));
$records0 = Loader_Redis::common()->hGetAll("shengdan|2014-12-22|3|1|hat");
$records1 = Loader_Redis::common()->hGetAll("shengdan|2014-12-22|3|1|clothes");
$records2 = Loader_Redis::common()->hGetAll("shengdan|2014-12-22|3|1|boots");
foreach ($records0 as $jjj0=>$jjjjkk0){
    $jkjk0[] = $jjj0;
}
foreach ($records1 as $jjj1=>$jjjjkk1){
    $jkjk1[] = $jjj1;
}
foreach ($records2 as $jjj2=>$jjjjkk2){
    $jkjk2[] = $jjj2;
}
$jjjkkkk = array_merge( $jkjk0, $jkjk1, $jkjk2);
echo '<br/>';
var_dump($jjjkkkk);
echo '<br/>';
$temp[] = array_unique($jjjkkkk);
var_dump($temp);
echo '<br/>';

$keys = array('0'=>'NOW','1'=>'-1 days','2'=>'-2 days','3'=>'-3 days','4'=>'-4 days','5'=>'-5 days');
$total_game = $total_ctype = $total_all = array();
foreach ($keys as $k => $v) {
    $time = date('Y-m-d',strtotime($v));
    foreach (Config_Game::$game as $gameid=>$gamename) {
        foreach (Config_Game::$clientTyle as $ctype=>$cliname) {
            $records = Loader_Redis::common()->hGetAll("shengdan-join|$time|$gameid|$ctype");
            if ($records){
                foreach ($records as $record){
                    $total_ctype[$time][$gameid][$ctype]++;
                    $total_game[$time][$gameid]++;
                    $total_all[$time]++;
                }
            }
        }
    }
}
var_dump($total_all);

die();*/

/*$keys = array('0'=>'-1 days','1'=>'-2 days','2'=>'-3 days','3'=>'-4 days','4'=>'-5 days');

foreach ($keys as $ll){
    $now = date('ymd',strtotime("$ll"));
    $gg[$now] = Loader_Redis::common()->hGetAll("lebao-top15-$now");
    $o = 0;
    if ($gg[$now]){
        foreach ($gg[$now] as $kkkk=>$vvvv){
            $be[$now][$o]['id'] = $kkkk;
            $be[$now][$o]['数量'] = $vvvv;
            $id[$now][] = $kkkk;
            $xx[$now][] = $vvvv;
            $o++;
        }
        array_multisort($xx[$now],SORT_DESC,$be[$now]);
    }
}
var_dump($be);*/

//$jjkjkk = array_merge($iiiu[0],$iiiu[1],$iiiu[2],$iiiu[3],$iiiu[4],$iiiu[5]);
//$opopop = array_flip($jjkjkk);
//var_dump($opopop);


/*$new['dog'] = 18;
$new['bird'] = 15;
$new['fox'] = 16;
$new['giraffe'] =19;
$new['panda'] = 21;
$new['sheep'] = 24;*/

/*$new = Loader_Redis::common()->get('tesetrandom-141204');
$x = 0;
foreach ($new as $q=>$w){
    $ba[$x] = $w;
    $x++;
}
array_multisort($ba,SORT_ASC,$new);
$ran[0] = rand(0, 9);
$ran[1] = rand(0, 3);
$ran[2] = rand(0, 3);
$ran[3] = rand(0, 3);
$ran[4] = rand(0, 3);
$ran[5] = rand(0, 3);

$o = 0;
foreach ($new as $u=>$y){
    $new[$u] = $new[$u] + $ran[$o];
    $o++;
}

var_dump($new);
echo '<br/>';
$ll = Loader_Redis::common()->set('tesetrandom-141204', $new, false, true, 3600);


//Loader_Redis::common()->delete('tesetrandom-141204');

die();*/

/*
$types    = array('0'=>'dog','1'=>'bird','2'=>'fox','3'=>'giraffe','4'=>'panda','5'=>'sheep');
$keyday  = array('0'=>'NOW','1'=>'-1 days','2'=>'-2 days','3'=>'-3 days','4'=>'-4 days','5'=>'-5 days');
foreach ($types as $k=>$ty){
    $rt = Loader_Redis::common()->getKeys('lebao|2014-12-01|*|*|'.$ty);
    foreach ($rt as $u){
        $hg = Loader_Redis::common()->hGetAll($u);
        $hgc[$ty] = count($hg);
        foreach ($hg as $key=>$value ){
            $tmp = Loader_Redis::common()->hget($u,$key);
            $con[$ty] = $tmp + $con[$ty];
        }
    } 
}
var_dump($hgc);
var_dump($con);*/


/*$rows = array();
$types    = array('0'=>'dog','1'=>'bird','2'=>'fox','3'=>'giraffe','4'=>'panda','5'=>'sheep');
$keys = array('0'=>'NOW','1'=>'-1 days','2'=>'-2 days','3'=>'-3 days','4'=>'-4 days','5'=>'-5 days');

$total_game = $total_ctype = $total_all = array();

foreach ($keys as $k => $v) {
    $time = date('Y-m-d',strtotime($v));
    foreach (Config_Game::$game as $gameid=>$gamename) {
        foreach (Config_Game::$clientTyle as $ctype=>$cliname) {
            foreach ($types as $type){
                $records = Loader_Redis::common()->hGetAll("lebao|$time|$gameid|$ctype|$type");                
                if($records){
                    foreach ($records as $record) {
                        $total_game['mid'][$time][$gameid][$type] ++ ;//某个游戏的人数
                        $total_game['times'][$time][$gameid][$type] = $total_game['times'][$time][$gameid][$type] + $record;//某个游戏的人数次数
                        
                        
                        $total_ctype['mid'][$time][$gameid][$ctype][$type] ++ ;//某个游戏某个客户端的人数
                        $total_ctype['times'][$time][$gameid][$ctype][$type] = $total_ctype['times'][$time][$gameid][$ctype][$type] + $record;//某个游戏个客户端次数

                        $total_all['mid'][$time][$type] ++;//总人数
                        $total_all['times'][$time][$type] = $total_all['times'][$time][$type] + $record;//总次数
                    }
                }
            }
        }
    }
}

echo '<pre>';
var_dump($total_game);
echo "<br/>";

echo '<pre>';
var_dump($total_ctype);
echo "<br/>";

echo '<pre>';
var_dump($total_all);
echo "<br/>";*/





/*
$userinfo = Member::factory()->getUserInfo(113821);
var_dump($userinfo);


die();
$mid = 113821;
$roll = 50;


$result = Loader_Tcp::callServer($mid)->setRollExp($mid, 100);//	减roll
var_dump($result);
die();
$result = Loader_Tcp::callServer($mid)->setRoll($mid, -$roll);//加roll1
	var_dump($result);
	die();
if($result){
	$result = Loader_Tcp::callServer($mid)->setRollExp($mid, $roll);//加roll1
	var_dump($result);
}

die();


$exptime = 6;
$mid     = 194;
$gameid  = 3;
$vipexptime = Loader_Redis::account()->ttl(Config_Keys::props($gameid,$mid));//如果之前购买了计牌器，则天数累加
$vipexptime = Helper::uint($vipexptime) ?  ceil(Helper::uint($vipexptime)/86400) : 0;
$exptime = $exptime + $vipexptime;
Loader_Redis::account()->set(Config_Keys::props($gameid,$mid), 1,false,false,24*3600*$exptime);
Loader_Tcp::callServer($mid)->setMoney($mid,0);//通知server 处理在房间玩牌的情况


//Base::factory()->sendMessage(4589, 13823615583, 1);

die();

/*
Loader_Redis::common()->lPush('atest1', json_encode(array('a','b','c')));
Loader_Redis::common()->lPush('atest2', json_encode(array('e','f','g')));

while (1){
	$result = Loader_Redis::common()->brPop(array('atest1','atest2'), 2);
	if(!$result){
		//die('no');
	}
	var_dump($result);
}



//var_dump($result);

die();
Base::factory()->sendMessage(4589, 13823615583, 1);
die();

/*
die();
Base::factory()->sendMessage(4589, 13823615583, 1);
die();
error_reporting(E_ALL ^ E_NOTICE);

Base::factory()->sendMessage(4589, 13823615583, 1);

die();

$f = Push::factory()->pushMsgBymid(8024);
var_dump($f);

die();

if(Loader_Redis::common()->hExists(Config_Keys::stars5(4), 121264)){
	echo "已经领取！";
}


die();
$sql = "SELECT *
FROM `log_win1`
WHERE `wmode` =3
AND `money` =100000";

$rows = Loader_Mysql::DBLogchip()->getAll($sql);

foreach ($rows as $row) {
	Loader_Redis::common()->hSet(Config_Keys::stars5(1), $row['mid'], 1);
	echo $row['mid'];
	echo "\n";
}


die();

Base::factory()->sendMessage(4589, 13823615583, 1);

die();
$f = Push::factory()->pushMsgBymid(202838);
var_dump($f);

die();
$start = microtime(true);

$ip = Helper::getip();
$ip_arr = Lib_Ip::find($ip);
//if($ip_arr[0] == '美国'){
	//不显示
//}

var_dump($ip_arr);

$end = microtime(true);

echo $end - $start;



die();
$date = date("Y-m-d",NOW);


for($mid = 200000;$mid<=200200;$mid++){
	$key = Config_Keys::dayreg(1, $date);
	Loader_Redis::account()->rPush($key, $mid,false,false);
}

for($mid = 200200;$mid<=200400;$mid++){
	$key = Config_Keys::dayreg(4, $date);
	Loader_Redis::account()->rPush($key, $mid,false,false);
}

die();
$f = Push::factory()->pushMsgBymid(1062,1);

var_dump($f);

die();


/*
$aMids     = Loader_Redis::push()->lGetRange("TPH|1|1|2|0", 81, 161,false,false);
		var_dump($aMids);


die();
//$f = Base::factory()->sendMessage(8888, 13823615583, 1);

//var_dump($f);
die();

/*
$f = Base::factory()->payMemberLogin(85389);

var_dump($f);

die();

//set_time_limit(0); //不超时

/*
$mid = 19000;


for($mid =190000;$mid<=193963;$mid++){

	$sql  = "SELECT mid,deviceno FROM ucenter.uc_sitemid2mid WHERE mid='$mid'";
	$rows = Loader_Mysql::DBMaster()->getAll($sql);
	
	if($rows){
		foreach ($rows as $row) {
			$mid = $row['mid'];
			$aUser = Member::factory()->getUserInfo($mid);
			
			if($aUser['mtime']){
				$aMtime = json_decode($aUser['mtime'],true);
				foreach ($aMtime as $gameid=>$gname) {
					Loader_Redis::account()->hSet("ANDROIDKEY|$gameid", $row['deviceno'], 2);
					echo $mid.'|'.$gameid.'|'.$row['deviceno'].'|2';
					echo "\n";
				}
			}
			
		}
	}
}


$keys = Loader_Redis::account()->hGetAll("ANDROIDKEY");

$i = 1;
foreach ($keys as $key=>$val) {
	
	if(!$key){
		continue;
	}

	$sql  = "SELECT mid FROM ucenter.uc_sitemid2mid WHERE deviceno='$key'";
	$rows = Loader_Mysql::DBMaster()->getAll($sql);
	
	if($rows){
		foreach ($rows as $row) {
			$mid = $row['mid'];
			$aUser = Member::factory()->getUserInfo($mid);
			
			if($aUser['mtime']){
				$aMtime = json_decode($aUser['mtime'],true);
				foreach ($aMtime as $gameid=>$gname) {
					Loader_Redis::account()->hSet("ANDROIDKEY|$gameid", $key, $val);
					echo $i.'|'.$gameid.'|'.$key.'|'.$val;
					echo "\n";
				}
			}
			
		}
	}else{
		Loader_Redis::account()->hSet("ANDROIDKEY|$gameid", $key, $val);
		echo $i.'|1|'.$key.'|'.$val;
		echo "\n";
	}
	
	$i ++ ;
	//usleep(10);
}

die();
*/

/*
$str = "'fhâ<80><9d>ç<9a><84> iPhone";

echo mysql_real_escape_string( $str );




die();
echo "<br/>";
echo "<br/>";
echo "<br/>";

$aUser['aMactivetime'] = $aMactivetime = json_decode($aUser['mactivetime'],true);
		$aUser['mactivetime']  = (int)$aMactivetime[3];
		
		$aUser['aMentercount'] = $aMentercount = json_decode($aUser['mentercount'],true);
		$aUser['mentercount']  = (int)$aMentercount[3];
		
		$aUser['aMtime']       = $aMtime = json_decode($aUser['mtime'],true);
		$aUser['mtime']        = (int)$aMtime[3];

var_dump($aUser);





die();

$param['pdealno'] = $return['pdealno'] = '4073814601488125';
	$flag = Pay::factory()->payOrder($param);
	if($flag){
		Pay::factory()->deliver($param);
	}


die();


//$flag = Logs::factory()->addWin(1,117, 1,102, 1, 1,1,0, 1000);

//var_dump($flag);

//die();

/*
$timehi = date('Hi');
$day = date("Y-m-d",strtotime("-8 day"));

$aData = Loader_Redis::common()->hGetAll(Config_Keys::stat($day, 55));
var_dump($aData);
echo "<br/>";
		if(!$aData){//兼容（新增用户项）没有数据的情况 2014-03-27
			var_dump($aData);
			$aData = Loader_Redis::common()->hGetAll(Config_Keys::stat($day, 9));
		}

die();

$reqParam = "eyJwb2QiPSIzIjsicHVyY2hhc2UtaW5mbyI9ImV5SmhjSEF0YVhSbGJTMXBaQ0k5SWpjNE9EUTROVE0yTWlJN0ltSjJjbk1pUFNJeExqSXVNQ0k3SW5CMWNtTm9ZWE5sTFdSaGRHVWlQU0l5TURFMExUQXpMVEkzSURFeE9qTXhPalEzSUVWMFl5OUhUVlFpT3lKeGRXRnVkR2wwZVNJOUlqRWlPeUppYVdRaVBTSmpiMjB1WkdsaGJteGxjaTV6ZFc5b1lTSTdJblpsY25OcGIyNHRaWGgwWlhKdVlXd3RhV1JsYm5ScFptbGxjaUk5SWpRMk1qQXlNalkwTUNJN0luQjFjbU5vWVhObExXUmhkR1V0Y0hOMElqMGlNakF4TkMwd015MHlOeUF3TkRvek1UbzBOeUJCYldWeWFXTmhMMHh2YzE5QmJtZGxiR1Z6SWpzaWNIVnlZMmhoYzJVdFpHRjBaUzF0Y3lJOUlqRXpPVFU1TVRrNU1EY3pNamNpT3lKcGRHVnRMV2xrSWowaU1DSTdJbTl5YVdkcGJtRnNMWFJ5WVc1ellXTjBhVzl1TFdsa0lqMGlORGMwTXpZME1UY3lNRFEwTkRjME5EWTNOaUk3SW05eWFXZHBibUZzTFhCMWNtTm9ZWE5sTFdSaGRHVXRiWE1pUFNJeE16azFPVEU1T1RBM016STNJanNpYjNKcFoybHVZV3d0Y0hWeVkyaGhjMlV0WkdGMFpTMXdjM1FpUFNJeU1ERTBMVEF6TFRJM0lEQTBPak14T2pRM0lFRnRaWEpwWTJFdlRHOXpYMEZ1WjJWc1pYTWlPeUp3Y205a2RXTjBMV2xrSWowaVkyOXRMbVJwWVc1c1pYSXVjM1Z2YUdFdWRHbGxjak0wSWpzaWRISmhibk5oWTNScGIyNHRhV1FpUFNJME56UXpOalF4TnpJd05EUTBOelEwTmpjMklqc2liM0pwWjJsdVlXd3RjSFZ5WTJoaGMyVXRaR0YwWlNJOUlqSXdNVFF0TURNdE1qY2dNVEU2TXpFNk5EY2dSWFJqTDBkTlZDSTdJblZ1YVhGMVpTMXBaR1Z1ZEdsbWFXVnlJajBpTm1JME5qUXhaR1l6WVRBNU56WTBPRFl4Tmpjek1EWTRaak5sTkROa05HWmlOVFJoWkRaa01pSTdmUT09Ijsic2lnbmF0dXJlIj0iQXBkeEpkdE53UFUyckE1L2NuM2tJTzFPVGsyNWZlREthMGFhZ3l5UnZlV2xjRmxnbHY2UkY2em5raUJTM3VtOVVjN3BWb2IrUHFaUjJUOHd5VnJITnBsb2YzRFgzSXFET2xXcSs5MGE3WWwrcXJSN0E3ald3dml3NzA4UFMrNjdQeUhSbmhPL0c3YlZxZ1JwRXI2RXVGeWJpVTFGWEFpWEpjNmxzMVlBc3NReEFBQURWekNDQTFNd2dnSTdvQU1DQVFJQ0NHVVVrVTNaV0FTMU1BMEdDU3FHU0liM0RRRUJCUVVBTUg4eEN6QUpCZ05WQkFZVEFsVlRNUk13RVFZRFZRUUtEQXBCY0hCc1pTQkpibU11TVNZd0pBWURWUVFMREIxQmNIQnNaU0JEWlhKMGFXWnBZMkYwYVc5dUlFRjFkR2h2Y21sMGVURXpNREVHQTFVRUF3d3FRWEJ3YkdVZ2FWUjFibVZ6SUZOMGIzSmxJRU5sY25ScFptbGpZWFJwYjI0Z1FYVjBhRzl5YVhSNU1CNFhEVEE1TURZeE5USXlNRFUxTmxvWERURTBNRFl4TkRJeU1EVTFObG93WkRFak1DRUdBMVVFQXd3YVVIVnlZMmhoYzJWU1pXTmxhWEIwUTJWeWRHbG1hV05oZEdVeEd6QVpCZ05WQkFzTUVrRndjR3hsSUdsVWRXNWxjeUJUZEc5eVpURVRNQkVHQTFVRUNnd0tRWEJ3YkdVZ1NXNWpMakVMTUFrR0ExVUVCaE1DVlZNd2daOHdEUVlKS29aSWh2Y05BUUVCQlFBRGdZMEFNSUdKQW9HQkFNclJqRjJjdDRJclNkaVRDaGFJMGc4cHd2L2NtSHM4cC9Sd1YvcnQvOTFYS1ZoTmw0WElCaW1LalFRTmZnSHNEczZ5anUrK0RyS0pFN3VLc3BoTWRkS1lmRkU1ckdYc0FkQkVqQndSSXhleFRldngzSExFRkdBdDFtb0t4NTA5ZGh4dGlJZERnSnYyWWFWczQ5QjB1SnZOZHk2U01xTk5MSHNETHpEUzlvWkhBZ01CQUFHamNqQndNQXdHQTFVZEV3RUIvd1FDTUFBd0h3WURWUjBqQkJnd0ZvQVVOaDNvNHAyQzBnRVl0VEpyRHRkREM1RllRem93RGdZRFZSMFBBUUgvQkFRREFnZUFNQjBHQTFVZERnUVdCQlNwZzRQeUdVakZQaEpYQ0JUTXphTittVjhrOVRBUUJnb3Foa2lHOTJOa0JnVUJCQUlGQURBTkJna3Foa2lHOXcwQkFRVUZBQU9DQVFFQUVhU2JQanRtTjRDL0lCM1FFcEszMlJ4YWNDRFhkVlhBZVZSZVM1RmFaeGMrdDg4cFFQOTNCaUF4dmRXLzNlVFNNR1k1RmJlQVlMM2V0cVA1Z204d3JGb2pYMGlreVZSU3RRKy9BUTBLRWp0cUIwN2tMczlRVWU4Y3pSOFVHZmRNMUV1bVYvVWd2RGQ0TndOWXhMUU1nNFdUUWZna1FRVnk4R1had1ZIZ2JFL1VDNlk3MDUzcEdYQms1MU5QTTN3b3hoZDNnU1JMdlhqK2xvSHNTdGNURXFlOXBCRHBtRzUrc2s0dHcrR0szR01lRU41LytlMVFUOW5wL0tsMW5qK2FCdzdDMHhzeTBiRm5hQWQxY1NTNnhkb3J5L0NVdk02Z3RLc21uT09kcVRlc2JwMGJzOHNuNldxczBDOWRnY3hSSHVPTVoydG04bnBMVW03YXJnT1N6UT09Ijsic2lnbmluZy1zdGF0dXMiPSIwIjt9";

$sandbox = 0;
$check_url = $sandbox == 1 ? "https://sandbox.itunes.apple.com/verifyReceipt" : "https://buy.itunes.apple.com/verifyReceipt";
    	$reqParam = json_encode(array('receipt-data'=>$receipt));
    	$ret = Helper::curl($check_url, $reqParam);
    	
    	var_dump($ret);


die();

		
die();		

$flag = Helper::curl('http://192.168.1.147/showhand/callback/paycallback.php', array(),'get');

var_dump($flag);

die();
Loader_Redis::common()->set('otpest',array(),false,false);

$flag = Loader_Redis::common()->get('otpest',false,false);

var_dump($flag);
//Member::factory()->changeMid2another(5641,9999);
die();

/*
$message = "yafuck";

$flag = Loader_Tcp::callServer2CheckName()->checkUserName($message);

if(strrpos($flag, '*')){
	var_dump('no');
}else{
	var_dump('yes');
}


var_dump($flag);





die();
$itemid = 11;
$day = "2014-01-08";


Loader_Redis::common()->hSet(Config_Keys::stat($day, $itemid), '2-1-1000', 2);
Loader_Redis::common()->hSet(Config_Keys::stat($day, $itemid), '2-1-1001', 3);
Loader_Redis::common()->hSet(Config_Keys::stat($day, $itemid), '1-2-1002', 4);
Loader_Redis::common()->hSet(Config_Keys::stat($day, $itemid), '1-2-1003', 5);


$f = Loader_Redis::common()->hGetAll(Config_Keys::stat(55, 66));

var_dump($f);


die();

/*

Push::factory()->pushMsgBymid(1013);
die();


$f = Loader_Redis::account()->set(Config_Keys::vip(1032), 1,false,false,24*3600*30);

var_dump($f);

die();


Push::factory()->pushMsgBymid(1013);
die();



/*
$url = "http://114.80.227.149/msg/HttpSendSM";

$data['account'] = 'szdlyzm';
$data['pswd'] = 'Szdlyzm123';
$data['mobile'] = '18676349753';
$data['msg']      = '你的验证码：123456【点乐梭哈游戏】';
$data['needstatus'] = 'true';
$data['product'] = '1500640';

$rtn = Helper::curl($url,$data,'get');

var_dump($rtn);

die();


/*

Loader_Tcp::callServer2PayNotice()->payNotice(1011, 1);

die();

/*
Loader_Redis::minfo(1008)->delete(Config_Keys::getUserInfo(1008));

die();


/*
for($i=1;$i<1000;$i++){
	$itemid = mt_rand(1, 25);
	$cid    = mt_rand(1, 5);
	$pid    = mt_rand(1000, 1003);
	$data_rank   = mt_rand(1, 10);
	$data = date("Y-m-d",strtotime("- $data_rank day"));
	$amount = mt_rand(5, 1000);
	$sql="INSERT INTO stat_sum VALUES('',$itemid,1,$cid,$pid,'$data',$amount)";
	Loader_Mysql::DBStat()->query($sql);
}

die();

//Loader_Redis::account()->set(Config_Keys::vip(1001), 1,false,false);
//die();

//Loader_Udp::stat()->sendData(1, 10, 1, 2, 100, 1000, '127.168.1.147');
//die();

/*

$f = Loader_Redis::account()->set(Config_Keys::vip(250), 1,false,false);

var_dump($f);

die();

/



for($i=1;$i<=200;$i++){
	Logs::factory()->addWin(260, 3, 100, 2, 1000, 1, 0, 100);
}
die();

//Loader_Tcp::callServer()->setLog(228, 1000, 100, 1, 1000, 1, 2, 0);


//Base::factory()->setMessageLogs(221, '13823615583', "验证码：123", 1, 0);





$url = "http://114.80.227.149/msg/HttpSendSM";

$data['account'] = 'szdlyzm';
$data['pswd'] = 'Szdlyzm123';
$data['mobile'] = '18676349753';
$data['msg']      = '你的验证码：123456【点乐梭哈游戏】';
$data['needstatus'] = 'true';
$data['product'] = '1500640';

$rtn = Helper::curl($url,$data,'get');

var_dump($rtn);

die();


/*
Loader_Redis::common()->hSet('test', 'aa', 'yang',false,false,10);
Loader_Redis::common()->hSet('test', 'bb', 'kaifeng',false,false,30);

$info = Loader_Redis::common()->hGetAll('test');

var_dump($info);




die();

for ($i = 1000; $i < 2000; $i++) {
	//Loader_Tcp::callServer()->delete($i, "注册赠送金币");
	Logs::factory()->addWin($i, 100, 3, 0, 30000, '', 'A_100_g_test', '', true);
	//Loader_Tcp::callServer()->create($i, 3000, "注册赠送金币");
}

//Loader_Tcp::callServer()->create(85, 3000, "注册赠送金币");
//Loader_Tcp::callServer()->get(85, 3000, "注册赠送金币");
//Loader_Tcp::callServer()->setGameInfo(85, -1000, -10, -20, -20, "注册赠送金币");
//Loader_Tcp::callServer()->delete(85, "注册赠送金币");
//Loader_Tcp::callServer()->delete(84,-100,-20,-10,-10, "注册赠送金币");

*/


/*
$_POST['api'] = '{"cid":1,"ctype":2,"gameid":4,"mid":99764,"mtkey":"f04e63d0f7bc67adbcd47ac510cc5187","param":{"sitemid":86588,"phoneno":"13823615583","username":"ykf20","password":"123456","type":2,"advertising":"E30E46D1-4301-4471-BF4C-C4F53C3BDA3F","deviceToken":"ef32d54b ced26e37 c4dc9db7 159af349 3440e5e3 e5a1729d 646303b0 7656febd","device_name":"Kaifeng\u7684 iPhone","device_no":"","mac_address":"02:00:00:00:00:00","method":"Clientbase.guestBingding","mnick":"Kaifeng\u7684 iPhone","mobileOperator":0,"net_type":2,"openudid":"e44a4a420dc1ea75a3f9e72985915632b1b01af8","os_version":"7.1.2","vendor":"8E419B6D-CFF6-4203-AB5D-6AA8651CA8A8"},"pid":1123,"sid":100,"sig":"8c1725359cce8147fad49ca96697cbc0","versions":"1.0.1"}';

$param = Config_Flag::param( $_POST['api']);

if( !Lib_Mobile::auth($param)){
	//Config_Flag::mkret(Config_Flag::status_check_error);
}
*/

$param['sid']        = 102;
$param['cid']        = 1;
$param['pid']        = '1000';
$param['ctype']      = 2;
$param['mid']        = 113821;
$param['versions']   = '1.1.0';		
$param['phonemodel'] = 'xiaomi';		
$param['gameid']     = '4';

$param['param']['phoneno']   = '13823615583';
$param['param']['mnick']   = 'abc';
$param['param']['sex']   = '1';
$param['param']['hometown']   = 'guangdong';
$param['param']['version']   = '-1';
$param['param']['device_no']   = 'sdfsdfsdfsdfsdw5';
$param['param']['sitemid']   = 1000;
$param['param']['phoneno']   = '13823615583';
$param['param']['content']  = "你的程序有BUG";
$param['param']['type']   = '2';
$param['param']['bankPWD'] = '123456';
$param['param']['money'] = '20000';
$param['param']['oldPWD'] = '10000';
$param['param']['newPWD'] = '10000';
$param['param']['gameid']    = mt_rand(1, 4);
$param['param']['pdealno']    = "dl138623376500000006";
$param['param']['username']   = 'sf88ff';
$param['param']['password']   = '123';
$param['param']['gameid']   = 1;
$param['param']['id_no']   = '440921198709286595';
$param['param']['bankPWD'] = '123';
$param['param']['gid'] = 'w-1';
$param['param']['giver'] = '113821';
$param['param']['roomid'] = '2';
$param['param']['prizeid'] = '10';
$param['param']['betMoney'] = '1000';
$param['param']['unsetCard'] = '26';

//$mehod = "Clientbase.getMatchConfig";
//$mehod = "Slots.bet";
//$mehod = "Slots.getConfig";
//$mehod = "Slots.changeCard";
$mehod = "Register.userName";

//Loader_Redis::account()->set(Config_Keys::vip($param['mid']), 1,false,false);

//Base::factory()->updateDownloadOtherGameStatus("E30E46D1-4301-4471-BF4C-C4F53C3BDA3F",3);

list($class, $method) = explode('.', $mehod);
	
$oClass = new $class();
method_exists($oClass, $method) or Config_Flag::mkret(Config_Flag::status_method_error);
	
$return = $oClass->$method($param);
	
Config_Flag::mkret(Config_Flag::status_ok, $return);



