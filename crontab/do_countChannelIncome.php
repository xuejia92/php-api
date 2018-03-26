<?php

$dirName = dirname(dirname(__FILE__));
include $dirName.'/common.php';

$time = date("Hi",NOW);
if ($time == '0200'){
    $date   = strtotime(date("Y-m-d", strtotime("-1 days")));
    $_stime = $date;
    $_etime = $date + 86400;
    $today  = date("Y-m-d",$_stime);
    
    foreach (Config_Game::$game as $gameid=>$gamename){
    
        $awhere = array();
        $awhere[] = "gameid=".$gameid;
        $awhere[] = "ptime>=".$_stime;
        $awhere[] = "ptime<".$_etime;
        $awhere[] = "pstatus=2";
         
        $sql = "SELECT * FROM ucenter.uc_payment WHERE ".implode(" AND ", $awhere);
        $rows = Loader_Mysql::DBMaster()->getAll($sql);
    
        foreach ($rows as $row){
            $gameid     = $row['gameid'];
            $cid        = $row['cid'];
            $pid        = $row['pid'];
            $ctype      = $row['ctype'];
            $pmode      = $row['pmode'];
            $pamount    = $row['pamount'];
    
            Loader_Redis::common()->hIncrBy("channelIncome_".$gameid."_"."$today", "$cid|$pid|$ctype|$pmode", $pamount);
        }
        Loader_Redis::common()->setTimeout("channelIncome_".$gameid."_"."$today", 7*24*3600);
    }
    
    foreach (Config_Game::$game as $gameid=>$gamename){
        
        $table = "stat.stat_sum".$gameid;
    
        $rows = Loader_Redis::common()->hGetAll("channelIncome_".$gameid."_"."$today");
        
        foreach ($rows as $key=>$value){
            
            $type = explode("|", $key);
            
            $aWhere     = array();
            $aWhere[]   = "gameid=".$gameid;
            $aWhere[]   = "cid=".$type[0];
            $aWhere[]   = "pid=".$type[1];
            $aWhere[]   = "ctype=".$type[2];
            $aWhere[]   = "sid=".$type[3];
            
            /*
             * @$type[3]   pmode
             * @$itemid   统计项id
             */
            
            switch ($type[3]){
                case 1:
                    $itemid = 202;
                    break;
                case 3:
                    $itemid = 203;
                    break;
                case 8:
                    $itemid = 204;
                    break;
                case 11:
                    $itemid = 205;
                    break;
                case 12:
                    $itemid = 206;
                    break;
                case 16:
                    $itemid = 207;
                    break;
                case 17:
                    $itemid = 208;
                    break;
                case 18:
                    $itemid = 209;
                    break;
                case 19:
                    $itemid = 210;
                    break;
                case 20:
                    $itemid = 211;
                    break;
                case 21:
                    $itemid = 212;
                    break;
                case 23:
                    $itemid = 213;
                    break;
                case 28:
                    $itemid = 214;
                    break;
                case 30:
                    $itemid = 215;
                    break;
                case 32:
                    $itemid = 216;
                    break;
                case 33:
                    $itemid = 217;
                    break;
                case 35:
                    $itemid = 218;
                    break;
                case 38:
                    $itemid = 219;
                    break;
                case 39:
                    $itemid = 220;
                    break;
                case 40:
                    $itemid = 221;
                    break;
                case 41:
                    $itemid = 222;
                    break;
                case 43:
                    $itemid = 223;
                    break;
                case 45:
                    $itemid = 242;
                    break;
                case 51:
                    $itemid = 258;
                    break;
                case 55:
                    $itemid = 243;
                    break;
                case 56:
                    $itemid = 259;
                    break;
                case 57:
                    $itemid = 273;
                    break;
                case 63:
                    $itemid = 293;
                    break;
                default:
                    $itemid = 99999;
            }
            
            $aWhere[]   = "itemid=".$itemid;
            $aWhere[]   = "date='$today'";
            $aWhere[]   = "amount='$value'";
            
            if ($itemid != 99999){
                $sql = "INSERT INTO $table SET ".implode(" , ", $aWhere);
                Loader_Mysql::DBStat()->query($sql);
            }
        }       
    }
}