<?php !defined('IN WEB') AND exit('Access Denied!');

class Data_Channel extends Data_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Data_Channel();
		}
		
		return self::$_instances;
	}
	
	public function getItemDetail($data){
		$aWhere = array();
		$gameid = Helper::uint($data['gameid']);
		$catid  = Helper::uint($data['catid']);
		$pid    = Helper::uint($data['pid']);
		$ctype  = Helper::uint($data['ctype']);
		$cid    = Setting_Cid::factory()->get(array('gameid'=>$gameid,'ctype'=>$ctype));
	    $etime  = $data['etime'] ? $data['etime'] : date('Y-m-d',strtotime("-1 days"));
	    $now   = date("Y-m-d");
	    
		$select = "date,amount,itemid";
		
		foreach ($cid as $aCid){
		    $channel[$aCid['id']] = $aCid['cname'];
		}
		
		foreach ($channel as $channelid=>$channelname){
		    
		    $aWhere   = array();
	    	$aWhere[] = "cid=".$channelid;
	    	$aWhere[] = "roomid=0";
	    	$aWhere[] = "date='$etime'";
	    	$filed = 'cid';
	    	$value = $cid;
	    	
    		//选出统计类型下的所有统计项
	        $aItemid = array(
            	           '0'=>array('itemid'=>'10','catid'=>'1','itemname'=>'总注册','status'=>'1','order'=>'0','catname'=>'用户数据'),
            	           '1'=>array('itemid'=>'55','catid'=>'1','itemname'=>'新增用户','status'=>'1','order'=>'2','catname'=>'用户数据'),
	                       '2'=>array('itemid'=>'11','catid'=>'1','itemname'=>'日活跃','status'=>'1','order'=>'6','catname'=>'用户数据'),
            	           '3'=>array('itemid'=>'12','catid'=>'1','itemname'=>'昨注回头率','status'=>'1','order'=>'9','catname'=>'用户数据'),
            	           '4'=>array('itemid'=>'13','catid'=>'1','itemname'=>'3注回头率','status'=>'1','order'=>'11','catname'=>'用户数据'),
                           '5'=>array('itemid'=>'24','catid'=>'4','itemname'=>'充值金额','status'=>'1','order'=>'0','catname'=>'充值数据'),
                           '6'=>array('itemid'=>'25','catid'=>'4','itemname'=>'充值人数','status'=>'1','order'=>'2','catname'=>'充值数据'),
                           '7'=>array('itemid'=>'46','catid'=>'4','itemname'=>'arppu值','status'=>'1','order'=>'4','catname'=>'充值数据'),
                           '8'=>array('itemid'=>'47','catid'=>'4','itemname'=>'付费渗透率','status'=>'1','order'=>'5','catname'=>'充值数据'),
	                       '9'=>array('itemid'=>'61','catid'=>'4','itemname'=>'注册ARPU值','status'=>'1','order'=>'8','catname'=>'充值数据'),
	                       '10'=>array('itemid'=>'188','catid'=>'3','itemname'=>'玩牌率','status'=>'1','order'=>'8','catname'=>'牌局数据')
	        );
    	    
    	     
    	    $itemids = array();
    	    foreach ($aItemid as $val) {
    	        $itemids[] = $val['itemid'];
    	    }
    	    
    	    $table = $gameid ? $this->dbsum.$gameid : $this->dbsum;
    	    $sql   = "SELECT $select FROM $table WHERE ".implode(" AND ", $aWhere)." AND itemid IN (".implode(" , ", $itemids).")";
    	    $rows  = $this->cache($sql);
    	    
    	    foreach ($itemids as $itemid){
    	        $rtn[$etime][$channelid][$itemid] = 0;
    	    }
    	     
	        foreach ($rows as $row){
	            $amount    = $row['amount'] ? $row['amount'] : 0;
	            $id        = (int)$row['itemid'];
	            $rtn[$etime][$channelid][$id] = $amount;
	        }
		}
		return array($rtn,$aItemid,$channel,$etime);
	}
	
	private function cache($sql){
	    $key = md5($sql);
	    $rows = Loader_Redis::common()->get($key);
	    if(!$rows){
	        $rows = Loader_Mysql::DBStat()->getAll($sql);
	        $rows && Loader_Redis::common()->set($key,$rows,false,true,15*24*3600);
	    }
	
	    return $rows;
	}
	
	public function get($data,$gameid=1,$con=array()){
	     
	    $aWhere = array();
	    $aWhere[] = $data['catid'] ? "catid={$data['catid']}" : "catid=1";
	    $aWhere[] = "status=1" ;
	    $aWhere[] = "itemid IN ('10','55','11','12','13','24','25','46','47','61','188')";
	    
	     
	    $sql = "SELECT * FROM $this->dbitem WHERE ".implode(" AND ", $aWhere)." ORDER BY `order` ASC";
	    $items = Loader_Mysql::DBStat()->getAll($sql);
	
	    $cats = Data_Category::factory()->getCategory();
	    $rtn = array();
	
	    $a    = explode('_', $data['navTabId']);
	    $flag = count($a);
	
	    foreach ($items as $k=>$item) {
	
	        if(!in_array($data['catid'],array(10,12,9,1)) && $con){
	            //判断 这个项目的数据是否为空 如果是 则不显示。
	            $is_null = Data_Stat::factory()->checkItemNull($item['itemid'],$gameid, $con);
	
	            //针对实时数据 不在这个条件范围里面
	            if(!$is_null && !in_array($item['itemid'],array('1','8','9','11','4','12','55','62',99,98,177,178,179,180,181,182,183))){
	                continue;
	            }
	        }
	
	        if($flag > 2 && $data['catid'] == 7 && $item['itemid'] == 67){
	            continue;
	        }
	        $rtn[$k]            = $item;
	        $rtn[$k]['catname'] = $cats[$item['catid']];
	    }
	    return $rtn;
	}
}