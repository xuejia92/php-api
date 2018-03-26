<?php
$dirName = dirname(dirname(__FILE__));
include $dirName.'/common.php';

$hi = date("Hi",NOW);
$time = date("ymd",strtotime("-1 days"));
$yesterday =  date("ymd",strtotime("-2 days"));

$count = $mmt = array() ;//初始化次数和人数
if($hi == '1956'){
    $re = Loader_Redis::common()->get('lebal_results_'.$time);
    
    if (!$re){
        $keys = Loader_Redis::common()->getKeys("laobal_".$time."*");
        $mmt['dog']   = $mmt['bird']   = $mmt['fox']   = $mmt['giraffe']   = $mmt['panda']   = $mmt['sheep']  = 0;
        $count['dog'] = $count['bird'] = $count['fox'] = $count['giraffe'] = $count['panda'] = $count['sheep'] = 0;
        foreach ($keys as $key) {
            $which = Loader_Redis::common()->get($key);
            $count['dog']       = $which['dog']     + $count['dog'];
            $count['bird']      = $which['bird']    + $count['bird'];
            $count['fox']       = $which['fox']     + $count['fox'];
            $count['giraffe']   = $which['giraffe'] + $count['giraffe'];
            $count['panda']     = $which['panda']   + $count['panda'];
            $count['sheep']     = $which['sheep']   + $count['sheep'];
            
            $which['dog']     && $mmt['dog'] ++;
            $which['bird']    && $mmt['bird'] ++;
            $which['fox']     && $mmt['fox'] ++;
            $which['giraffe'] && $mmt['giraffe'] ++;
            $which['panda']   && $mmt['panda'] ++;
            $which['sheep']   && $mmt['sheep'] ++;
        }
        
        $data = $amount = $renshu = array();
    	$i = 0;
    	foreach ($count as $k=>$v) {
    	    $data[$i]['name'] = $k;
    		$data[$i]['shuliang'] = $v;
    		$data[$i]['renshu']   = $mmt[$k];
    		$amount[] = $v;
    		$renshu[] = $mmt[$k];
    		$i ++;
    	}
    	
    	array_multisort ($amount,SORT_ASC,$renshu, SORT_DESC, $data );//如果次数相同，则选择人数多的
    	$lottery = $data[0]['name'];//开奖结果
    	$yeslottery = Loader_Redis::common()->get('lebal_results_'.$yesterday);//获取前一天开奖结果
    	if ($lottery == $yeslottery){                                          //如果开奖结果与前一天结果相同，则选择第二个元素为开奖结果
    	    $lottery = $data[1]['name'];
    	}
        Loader_Redis::common()->set('lebal_results_'.$time,$lottery,false,true,5*24*3600);
    }
}
