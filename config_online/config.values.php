<?php !defined('IN WEB') AND exit('Access Denied!');

class Config_Values{
   public static function getUserInfo(){ //单记录所有值
        return array(
                    0 => 'mid',
                    1 => 'sid', //站点名
                    2 => 'bid', 
                    3 => 'mnick', //用户真实姓名
                    4 => 'sitemid',
                    5 => 'sex',
                    6 => 'hometown',
                    7 => 'mstatus',
                    8 => 'mactivetime',
                    9=> 'mentercount', 
                    10 => 'mtime',
                    11 => 'versions',
                    12 => 'ip', 
                    13 => 'phonemodel',
                );
    }
    
   public static function getGameInfo(){
    	return array(
                    0 => 'mid',
                    1 => 'sid', 
                    2 => 'money',
                    3 => 'bycoins', 
                    4 => 'freezemoney',
                    5 => 'level', 
                    6 => 'score',
                    7 => 'wintimes',
                    8 => 'losetimes',
                );
    }
    
    /**
     * 把对应的值压入数组
     */
    static function combine($aKey, $aValue){
        foreach ((array)$aKey as $key => $value){
            $aTemp[$key] = $aValue[$value];
        }
        return $aTemp;
    }
    /**
     * 反转数组
     */
    static function uncombine($aKey, $aValue){
        foreach ((array)$aKey as $key => $value){
            $aTemp[$value] = isset( $aValue[$key] ) ? $aValue[$key] : '';
        }
        return $aTemp;
    }
}